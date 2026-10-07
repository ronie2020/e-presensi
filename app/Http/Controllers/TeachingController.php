<?php

namespace App\Http\Controllers;

use App\Models\TeachingSession;
use App\Models\Timetable; // <-- DIUBAH DARI SCHEDULE KE TIMETABLE
use App\Models\Student;
use App\Models\ClassAttendance;
use App\Models\DisciplineRecord; 
use App\Models\DisciplineType;
use App\Models\LmsAssignment; // Untuk sinkronisasi tugas jurnal → LMS
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TeachingController extends Controller
{
    /**
     * DASHBOARD JADWAL MENGAJAR HARI INI
     */
     public function index()
    {
        $teacherId = Auth::id();
        Carbon::setLocale('id');
        $todayName = Carbon::now('Asia/Jakarta')->translatedFormat('l'); 

        // MENGAMBIL DATA DARI MESIN GENERATOR (TIMETABLE)
        $rawSchedules = Timetable::with(['studentClass', 'subject', 'timeslot', 'todaySession'])
                    ->where('teacher_id', $teacherId)
                    ->where('day_of_week', $todayName)
                    ->get()
                    ->sortBy(function($jadwal) {
                        return $jadwal->timeslot->order_sequence ?? 0;
                    })->values(); 

        // LOGIKA BLOK JP: 
        $groupedSchedules = [];
        $currentGroup = null;

        foreach ($rawSchedules as $schedule) {
            if (!$currentGroup) {
                $currentGroup = collect([$schedule]);
            } else {
                $lastSchedule = $currentGroup->last();
                
                // Jika Kelas dan Mapel sama dengan JP sebelumnya, gabungkan!
                if ($lastSchedule->class_id == $schedule->class_id && $lastSchedule->subject_id == $schedule->subject_id) {
                    $currentGroup->push($schedule);
                } else {
                    $groupedSchedules[] = $currentGroup;
                    $currentGroup = collect([$schedule]);
                }
            }
        }
        
        // Masukkan grup terakhir
        if ($currentGroup) {
            $groupedSchedules[] = $currentGroup;
        }

        // Kirim $groupedSchedules ke view, bukan lagi $schedules satuan
        return view('teaching.index', compact('groupedSchedules'));
    }

    // --- MULAI KELAS ---
    public function start($timetable_id)
    {
        $existingSession = TeachingSession::where('schedule_id', $timetable_id)
                            ->whereDate('date', Carbon::today('Asia/Jakarta'))
                            ->first();

        if ($existingSession) {
            return redirect()->route('teaching.show', $existingSession->id);
        }

        $session = TeachingSession::create([
            'schedule_id' => $timetable_id, // Menyimpan ID Timetable
            'teacher_id' => Auth::id(),
            'date' => Carbon::today('Asia/Jakarta'),
            'started_at' => Carbon::now('Asia/Jakarta'),
            'status' => 'open'
        ]);

        return redirect()->route('teaching.show', $session->id);
    }

    // --- HALAMAN KELAS BERLANGSUNG (LIVE) ---
    public function show($id)
    {
        $session = TeachingSession::with(['timetable.studentClass.students', 'timetable.subject', 'timetable.timeslot', 'attendances'])
                    ->findOrFail($id);
        
        $allStudents = $session->timetable->studentClass->students->sortBy('name');
        $attendances = $session->attendances->keyBy('student_id');
        $isOpen = $session->status == 'open';

        $stats = [
            'present'    => $attendances->where('status', 'present')->count(),
            'sick'       => $attendances->where('status', 'sick')->count(),
            'permission' => $attendances->where('status', 'permission')->count(),
            'alpha'      => $attendances->where('status', 'alpha')->count(),
        ];

        // Ambil sesi sebelumnya dari timetable (jadwal) yang sama
        // Diurutkan berdasarkan tanggal terbaru SEBELUM sesi ini, lalu ambil yang paling terakhir
        $previousSession = TeachingSession::where('schedule_id', $session->schedule_id)
            ->where('id', '!=', $session->id)
            ->where('date', '<', $session->date)
            ->orderBy('date', 'desc')
            ->first();

        // Cek apakah ada tugas dari sesi sebelumnya yang deadlinenya belum lewat
        $pendingHomework = null;
        if ($previousSession && $previousSession->homework_title && $previousSession->homework_deadline) {
            $deadline = Carbon::parse($previousSession->homework_deadline);
            if ($deadline->isFuture()) {
                $pendingHomework = $previousSession; // Deadline belum lewat, tampilkan reminder
            }
        }

        return view('teaching.show', compact('session', 'allStudents', 'attendances', 'isOpen', 'stats', 'previousSession', 'pendingHomework'));
    }

    // --- HALAMAN EDIT (REVISI SETELAH TUTUP) ---
    public function edit($id)
    {
        $session = TeachingSession::with(['timetable.studentClass.students', 'timetable.subject', 'timetable.timeslot', 'attendances'])
                    ->findOrFail($id);
        
        $allStudents = $session->timetable->studentClass->students->sortBy('name');
        $attendances = $session->attendances->keyBy('student_id');

        return view('teaching.edit', compact('session', 'allStudents', 'attendances'));
    }

    // --- UPDATE JURNAL ---
    public function update(Request $request, $id)
    {
        $session = TeachingSession::with('timetable')->findOrFail($id);
        
        $request->validate([
            'topic'                => 'required|string|max:255', 
            'activities'           => 'nullable|string',
            'photo_proof'          => 'nullable|array|max:5', // Maksimal 5 foto
            'photo_proof.*'        => 'image|max:5120', 
            'video_link'           => 'nullable|url',
            'material_status'      => 'nullable|in:selesai,belum_selesai',
            'material_coverage'    => 'nullable|string|max:1000',
            // Validasi tugas
            'homework_title'       => 'nullable|string|max:255',
            'homework_description' => 'nullable|string',
            'homework_type'        => 'nullable|in:offline,file_upload,link',
            'homework_link_url'    => 'nullable|url',
            'homework_deadline'    => 'nullable|date',
        ]);

        $data = [
            'topic'             => $request->topic,
            'activities'        => $request->activities,
            'reference_link'    => $request->reference_link ?? null,
            'video_link'        => $request->video_link,
            'material_status'   => $request->material_status,
            'material_coverage' => ($request->material_status === 'belum_selesai') ? $request->material_coverage : null,
            // Simpan data tugas
            'homework_title'       => $request->homework_title,
            'homework_description' => $request->homework_description,
            'homework_type'        => $request->homework_title ? $request->homework_type : null,
            'homework_link_url'    => ($request->homework_type === 'link') ? $request->homework_link_url : null,
            'homework_deadline'    => $request->homework_title ? $request->homework_deadline : null,
        ];

        if ($request->hasFile('photo_proof')) {
            $photoPaths = [];
            
            // Hapus foto-foto lama
            if ($session->photo_proof) {
                $oldPhotos = json_decode($session->photo_proof, true);
                if (is_array($oldPhotos)) {
                    foreach ($oldPhotos as $old) {
                        Storage::disk('public')->delete($old);
                    }
                } else {
                    // Backward compatibility (jika dulu format string biasa)
                    Storage::disk('public')->delete($session->photo_proof);
                }
            }

            // Simpan foto-foto baru
            foreach ($request->file('photo_proof') as $file) {
                $photoPaths[] = $file->store('jurnal-proof', 'public');
            }
            
            $data['photo_proof'] = json_encode($photoPaths);
        }

        $session->update($data);

        // --- SINKRONISASI KE LMS ASSIGNMENT ---
        $this->syncHomeworkToLms($session, $request);

        return back()->with('success', 'Jurnal & Bukti Kegiatan berhasil disimpan.');
    }

    /**
     * Sinkronisasi tugas dari jurnal mengajar ke LmsAssignment.
     * - Jika ada homework_title → buat atau update LmsAssignment
     * - Jika homework_title dikosongkan → putus link saja (LmsAssignment tetap ada di LMS)
     */
    private function syncHomeworkToLms(TeachingSession $session, Request $request): void
    {
        // Jika tidak ada judul tugas → putus link (LmsAssignment tetap ada di LMS, tidak dihapus)
        if (!$request->homework_title) {
            if ($session->lms_assignment_id) {
                $session->update(['lms_assignment_id' => null]);
            }
            return;
        }

        $timetable = $session->timetable;
        if (!$timetable) return;

        // Tentukan description LMS
        $lmsDescription = $request->homework_description 
            ?: 'Tugas dari jurnal mengajar: ' . $request->topic;

        // Map tipe tugas
        $assignmentType = $request->homework_type ?? 'offline';
        $linkUrl = ($assignmentType === 'link') ? $request->homework_link_url : null;

        $deadline = $request->homework_deadline 
            ? Carbon::parse($request->homework_deadline)->format('Y-m-d H:i:s')
            : Carbon::now()->addDays(7)->format('Y-m-d H:i:s'); // Default 7 hari

        try {
            if ($session->lms_assignment_id) {
                // Update LmsAssignment yang sudah ada
                $lmsAssignment = LmsAssignment::find($session->lms_assignment_id);
                if ($lmsAssignment) {
                    $lmsAssignment->update([
                        'title'           => $request->homework_title,
                        'description'     => $lmsDescription,
                        'deadline'        => $deadline,
                        'assignment_type' => $assignmentType,
                        'link_url'        => $linkUrl,
                    ]);
                    return;
                }
            }

            // Buat LmsAssignment baru
            $lmsAssignment = LmsAssignment::create([
                'teacher_id'      => $session->teacher_id,
                'subject_id'      => $timetable->subject_id,
                'class_id'        => $timetable->class_id,
                'title'           => $request->homework_title,
                'description'     => $lmsDescription,
                'deadline'        => $deadline,
                'assignment_type' => $assignmentType,
                'link_url'        => $linkUrl,
                'allow_late_submission' => false,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // Simpan foreign key di teaching_sessions
            $session->update(['lms_assignment_id' => $lmsAssignment->id]);

        } catch (\Exception $e) {
            // Gagal sync LMS tidak perlu stop keseluruhan, hanya log
            \Log::warning('Gagal sinkronisasi tugas ke LMS: ' . $e->getMessage());
        }
    }

    // --- RIWAYAT MENGAJAR ---
    public function history(Request $request)
    {
        $teacherId = Auth::id();
        Carbon::setLocale('id'); 
        
        $filterType = $request->input('filter_type', 'monthly');
        $filterValue = $request->input("filter_value_{$filterType}");

        if (!$filterValue) {
            if ($filterType === 'daily') $filterValue = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            elseif ($filterType === 'weekly') $filterValue = Carbon::now('Asia/Jakarta')->format('Y-\WW'); 
            else $filterValue = Carbon::now('Asia/Jakarta')->format('Y-m');
        }

        $query = TeachingSession::with(['timetable.studentClass', 'timetable.subject', 'timetable.timeslot'])
                    ->withCount([
                        'attendances as hadir' => function($q){ $q->whereIn('status', ['present', 'Hadir']); },
                        'attendances as terlambat' => function($q){ $q->whereIn('status', ['late', 'Terlambat']); },
                        'attendances as alpha' => function($q){ $q->whereIn('status', ['alpha', 'Alfa', 'Alpha']); },
                        'attendances as sakit' => function($q){ $q->whereIn('status', ['sick', 'Sakit']); },
                        'attendances as izin' => function($q){ $q->whereIn('status', ['permission', 'Izin']); },
                    ])
                    ->where('teacher_id', $teacherId)
                    ->where('status', 'closed');

        $filterLabel = ''; 

        if ($filterType === 'daily') {
            $query->whereDate('date', $filterValue);
            $filterLabel = 'tanggal ' . Carbon::parse($filterValue)->translatedFormat('d F Y');
        } elseif ($filterType === 'weekly') {
            $parts = explode('-W', $filterValue);
            if (count($parts) == 2) {
                $startOfWeek = Carbon::now('Asia/Jakarta')->setISODate($parts[0], $parts[1])->startOfWeek();
                $endOfWeek = $startOfWeek->copy()->endOfWeek();
                $query->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')]);
                $filterLabel = 'minggu ' . $startOfWeek->translatedFormat('d M') . ' s/d ' . $endOfWeek->translatedFormat('d M Y');
            }
        } else { 
            $date = Carbon::parse($filterValue . '-01');
            $query->whereMonth('date', $date->month)->whereYear('date', $date->year);
            $filterLabel = 'bulan ' . $date->translatedFormat('F Y');
        }

        $histories = $query->orderBy('date', 'desc')->orderBy('started_at', 'desc')->paginate(10)->withQueryString();

        return view('teaching.history', compact('histories', 'filterType', 'filterValue', 'filterLabel'));
    }

    // --- SCAN RFID ---
    public function scan(Request $request)
    {
        $request->validate(['rfid' => 'required', 'session_id' => 'required']);

        $student = Student::where('student_id', $request->rfid)->orWhere('nis', $request->rfid)->first();

        if (!$student) return response()->json(['status' => 'error', 'message' => 'Kartu tidak dikenali']);

        $session = TeachingSession::with('timetable')->find($request->session_id);

        if ($student->class_id != $session->timetable->class_id) {
            return response()->json(['status' => 'error', 'message' => 'Siswa salah kelas!']);
        }

        $existing = ClassAttendance::where('teaching_session_id', $session->id)->where('student_id', $student->id)->first();

        if ($existing) return response()->json(['status' => 'warning', 'message' => 'Siswa sudah absen sebelumnya.', 'student' => $student]);

        ClassAttendance::create([
            'teaching_session_id' => $session->id,
            'student_id' => $student->id,
            'status' => 'present',
            'scanned_at' => Carbon::now('Asia/Jakarta')
        ]);

        return response()->json(['status' => 'success', 'message' => 'Absen berhasil', 'student' => $student, 'time' => Carbon::now('Asia/Jakarta')->format('H:i')]);
    }

    // --- ABSEN MANUAL & EDIT STATUS ---
    public function storeManual(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:teaching_sessions,id',
            'student_id' => 'required|exists:students,id',
            'status'     => 'nullable|in:present,sick,permission,alpha', 
        ]);

        if(is_null($request->status)) {
             ClassAttendance::where('teaching_session_id', $request->session_id)->where('student_id', $request->student_id)->delete();
             $data = null; $status = null;
        } else {
            $attendance = ClassAttendance::updateOrCreate(
                ['teaching_session_id' => $request->session_id, 'student_id' => $request->student_id],
                ['status' => $request->status, 'scanned_at' => now('Asia/Jakarta'),]
            );
            $data = $attendance; $status = $request->status;
        }

        return response()->json(['status' => 'success', 'message' => 'Status siswa berhasil diperbarui.', 'data' => $data, 'new_status' => $status]);
    }

    // --- TANDAI SISANYA ALPHA (BULK ACTION) ---
    public function bulkAlpha(Request $request)
    {
        $request->validate(['session_id' => 'required|exists:teaching_sessions,id']);

        $session = TeachingSession::with('timetable.studentClass')->find($request->session_id);
        if (!$session || $session->status !== 'open') {
            return response()->json(['status' => 'error', 'message' => 'Sesi tidak valid atau sudah ditutup.']);
        }

        $classId = $session->timetable->class_id;
        $allStudents = Student::where('class_id', $classId)->pluck('id')->toArray();
        $presentIds = ClassAttendance::where('teaching_session_id', $session->id)->pluck('student_id')->toArray();

        $unmarkedIds = array_diff($allStudents, $presentIds);
        $updatedIds = [];

        foreach ($unmarkedIds as $studentId) {
            ClassAttendance::create(['teaching_session_id' => $session->id, 'student_id' => $studentId, 'status' => 'alpha', 'scanned_at' => null]);
            $updatedIds[] = $studentId;
        }

        return response()->json(['status' => 'success', 'message' => count($updatedIds) . ' siswa berhasil ditandai Alpha.', 'updated_ids' => array_values($updatedIds)]);
    }

    // --- TUTUP KELAS & DISIPLIN  ---
    public function close($id)
    {
        DB::beginTransaction();
        try {
            $session = TeachingSession::with(['timetable.studentClass', 'timetable.subject'])->findOrFail($id);
            
            if ($session->status == 'closed') return redirect()->route('teaching.index')->with('info', 'Kelas sudah ditutup sebelumnya.');

            $classId = $session->timetable->class_id;
            $allStudents = Student::where('class_id', $classId)->get();
            $presentIds = ClassAttendance::where('teaching_session_id', $id)->pluck('student_id')->toArray();

            $alphaDiscipline = DisciplineType::firstOrCreate(
                ['name' => 'Bolos Pelajaran (Alpha)'],
                ['type' => 'Pelanggaran', 'point_value' => 10, 'description' => 'Siswa tidak berada di kelas saat jam pelajaran.'] 
            );

            $alphaCount = 0;
            foreach ($allStudents as $student) {
                if (!in_array($student->id, $presentIds)) {
                    ClassAttendance::create(['teaching_session_id' => $id, 'student_id' => $student->id, 'status' => 'alpha', 'scanned_at' => null]);

                    DisciplineRecord::create([
                        'student_id' => $student->id,
                        'discipline_type_id' => $alphaDiscipline->id,
                        'date' => Carbon::today('Asia/Jakarta'),
                        'notes' => 'Tidak mengikuti KBM: ' . $session->timetable->subject->name . ' (' . $session->topic . ')', 
                        'recorded_by_user_id' => Auth::id() 
                    ]);

                    $alphaCount++;
                }
            }

            $session->update(['ended_at' => Carbon::now('Asia/Jakarta'), 'status' => 'closed']);
            DB::commit();
            return redirect()->route('teaching.index')->with('success', "Kelas ditutup. $alphaCount siswa ditandai Alpha & mendapat poin.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menutup kelas: ' . $e->getMessage());
        }
    }
}