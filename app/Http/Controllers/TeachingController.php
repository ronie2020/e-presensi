<?php

namespace App\Http\Controllers;

use App\Models\TeachingSession;
use App\Models\Timetable; // <-- DIUBAH DARI SCHEDULE KE TIMETABLE
use App\Models\Student;
use App\Models\ClassAttendance;
use App\Models\DisciplineRecord; 
use App\Models\DisciplineType;
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

        return view('teaching.show', compact('session', 'allStudents', 'attendances', 'isOpen', 'stats'));
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
        $session = TeachingSession::findOrFail($id);
        
        $request->validate([
            'topic' => 'required|string|max:255', 
            'activities' => 'nullable|string',
            'photo_proof' => 'nullable|image|max:5120', 
            'video_link' => 'nullable|url',
        ]);

        $data = [
            'topic' => $request->topic,
            'activities' => $request->activities,
            'reference_link' => $request->reference_link ?? null,
            'video_link' => $request->video_link,
        ];

        if ($request->hasFile('photo_proof')) {
            if ($session->photo_proof) {
                Storage::disk('public')->delete($session->photo_proof);
            }
            $data['photo_proof'] = $request->file('photo_proof')->store('jurnal-proof', 'public');
        }

        $session->update($data);

        return back()->with('success', 'Jurnal & Bukti Kegiatan berhasil disimpan.');
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