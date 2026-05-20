<?php

namespace App\Http\Controllers;

use App\Models\TeachingSession;
use App\Models\Schedule;
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
     * DASHBOARD JADWAL
     * Optimasi: Menggunakan Eager Loading 'todaySession'
     */
    public function index()
    {
        $teacherId = Auth::id();
        
        Carbon::setLocale('id');
        $todayName = Carbon::now()->translatedFormat('l'); 

        // Load 'todaySession' agar tidak query database di dalam loop Blade
        $schedules = Schedule::with(['schoolClass', 'subject', 'todaySession'])
                    ->where('teacher_id', $teacherId)
                    ->where('day', $todayName)
                    ->orderBy('start_time', 'asc')
                    ->get();

        return view('teaching.index', compact('schedules'));
    }

    // --- MULAI KELAS ---
    public function start($schedule_id)
    {
        $existingSession = TeachingSession::where('schedule_id', $schedule_id)
                            ->whereDate('date', Carbon::today())
                            ->first();

        if ($existingSession) {
            return redirect()->route('teaching.show', $existingSession->id);
        }

        $session = TeachingSession::create([
            'schedule_id' => $schedule_id,
            'teacher_id' => Auth::id(),
            'date' => Carbon::today(),
            'started_at' => Carbon::now(),
            'status' => 'open'
        ]);

        return redirect()->route('teaching.show', $session->id);
    }

    // --- HALAMAN KELAS BERLANGSUNG (LIVE) ---
    public function show($id)
    {
        // Load semua relasi yang dibutuhkan
        $session = TeachingSession::with(['schedule.schoolClass.students', 'schedule.subject', 'attendances'])
                    ->findOrFail($id);
        
        // 1. Ambil Data Siswa & Urutkan
        $allStudents = $session->schedule->schoolClass->students->sortBy('name');
        
        // 2. Ambil Absensi & Key By ID agar mudah diakses di Blade
        $attendances = $session->attendances->keyBy('student_id');
        
        // 3. Cek Status Kelas
        $isOpen = $session->status == 'open';

        // 4. LOGIKA STATISTIK
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
        // Sama seperti show, tapi diarahkan ke view teaching.edit
        $session = TeachingSession::with(['schedule.schoolClass.students', 'schedule.subject', 'attendances'])
                    ->findOrFail($id);
        
        $allStudents = $session->schedule->schoolClass->students->sortBy('name');
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
        Carbon::setLocale('id'); // Pastikan bahasa Indonesia untuk Carbon
        
        // 1. Tangkap tipe filter (Harian, Mingguan, Bulanan)
        $filterType = $request->input('filter_type', 'monthly');
        
        // 2. Tangkap nilai filter sesuai tipe yang dipilih (Hanya menangkap input yang aktif)
        $filterValue = $request->input("filter_value_{$filterType}");

        // Jika nilai filter kosong (akses pertama kali), set ke default hari ini/minggu ini/bulan ini
        if (!$filterValue) {
            if ($filterType === 'daily') $filterValue = Carbon::now()->format('Y-m-d');
            elseif ($filterType === 'weekly') $filterValue = Carbon::now()->format('Y-\WW'); // Format: 2026-W21
            else $filterValue = Carbon::now()->format('Y-m');
        }

        $query = TeachingSession::with(['schedule.schoolClass', 'schedule.subject'])
                    ->withCount([
                        'attendances as hadir' => function($q){ $q->whereIn('status', ['present', 'Hadir']); },
                        'attendances as terlambat' => function($q){ $q->whereIn('status', ['late', 'Terlambat']); },
                        'attendances as alpha' => function($q){ $q->whereIn('status', ['alpha', 'Alfa', 'Alpha']); },
                        'attendances as sakit' => function($q){ $q->whereIn('status', ['sick', 'Sakit']); },
                        'attendances as izin' => function($q){ $q->whereIn('status', ['permission', 'Izin']); },
                    ])
                    ->where('teacher_id', $teacherId)
                    ->where('status', 'closed');

        $filterLabel = ''; // Label dinamis untuk ditampilkan di View ketika data kosong

        // 3. Aplikasikan Query Berdasarkan Tipe Waktu
        if ($filterType === 'daily') {
            $query->whereDate('date', $filterValue);
            $filterLabel = 'tanggal ' . Carbon::parse($filterValue)->translatedFormat('d F Y');
            
        } elseif ($filterType === 'weekly') {
            // Parsing format 2026-W21 dari input type="week"
            $parts = explode('-W', $filterValue);
            if (count($parts) == 2) {
                $startOfWeek = Carbon::now()->setISODate($parts[0], $parts[1])->startOfWeek();
                $endOfWeek = $startOfWeek->copy()->endOfWeek();
                
                $query->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')]);
                $filterLabel = 'minggu ' . $startOfWeek->translatedFormat('d M') . ' s/d ' . $endOfWeek->translatedFormat('d M Y');
            }
            
        } else { // monthly
            $date = Carbon::parse($filterValue . '-01');
            $query->whereMonth('date', $date->month)
                  ->whereYear('date', $date->year);
            $filterLabel = 'bulan ' . $date->translatedFormat('F Y');
        }

        $histories = $query->orderBy('date', 'desc')
                           ->orderBy('started_at', 'desc')
                           ->paginate(10)
                           ->withQueryString();

        return view('teaching.history', compact('histories', 'filterType', 'filterValue', 'filterLabel'));
    }

    // --- SCAN RFID ---
    public function scan(Request $request)
    {
        $request->validate([
            'rfid' => 'required',
            'session_id' => 'required'
        ]);

        // Cari siswa berdasarkan RFID atau NIS
        $student = Student::where('student_id', $request->rfid) 
                   ->orWhere('nis', $request->rfid) 
                   ->first();

        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'Kartu tidak dikenali']);
        }

        $session = TeachingSession::with('schedule')->find($request->session_id);

        if ($student->class_id != $session->schedule->school_class_id) {
            return response()->json(['status' => 'error', 'message' => 'Siswa salah kelas!']);
        }

        $existing = ClassAttendance::where('teaching_session_id', $session->id)
                    ->where('student_id', $student->id)
                    ->first();

        if ($existing) {
            return response()->json(['status' => 'warning', 'message' => 'Siswa sudah absen sebelumnya.', 'student' => $student]);
        }

        ClassAttendance::create([
            'teaching_session_id' => $session->id,
            'student_id' => $student->id,
            'status' => 'present',
            'scanned_at' => Carbon::now()
        ]);

        return response()->json([
            'status' => 'success', 
            'message' => 'Absen berhasil', 
            'student' => $student,
            'time' => Carbon::now()->format('H:i')
        ]);
    }

    // --- ABSEN MANUAL & EDIT STATUS ---
    public function storeManual(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:teaching_sessions,id',
            'student_id' => 'required|exists:students,id',
            'status'     => 'nullable|in:present,sick,permission,alpha', // Nullable jika ingin reset
        ]);

        if(is_null($request->status)) {
             // Jika status dikirim null (Reset), hapus data absensi
             ClassAttendance::where('teaching_session_id', $request->session_id)
                            ->where('student_id', $request->student_id)
                            ->delete();
             $data = null;
             $status = null;
        } else {
            // Gunakan updateOrCreate agar bisa untuk absen baru ATAU revisi
            $attendance = ClassAttendance::updateOrCreate(
                [
                    'teaching_session_id' => $request->session_id,
                    'student_id' => $request->student_id
                ],
                [
                    'status' => $request->status,
                    'scanned_at' => now(), 
                ]
            );
            $data = $attendance;
            $status = $request->status;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Status siswa berhasil diperbarui.',
            'data' => $data,
            'new_status' => $status
        ]);
    }

    // --- TANDAI SISANYA ALPHA (BULK ACTION) ---
    public function bulkAlpha(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:teaching_sessions,id'
        ]);

        $session = TeachingSession::with('schedule.schoolClass')->find($request->session_id);
        if (!$session || $session->status !== 'open') {
            return response()->json(['status' => 'error', 'message' => 'Sesi tidak valid atau sudah ditutup.']);
        }

        $classId = $session->schedule->school_class_id;
        $allStudents = Student::where('class_id', $classId)->pluck('id')->toArray();
        
        $presentIds = ClassAttendance::where('teaching_session_id', $session->id)
                      ->pluck('student_id')->toArray();

        $unmarkedIds = array_diff($allStudents, $presentIds);
        $updatedIds = [];

        foreach ($unmarkedIds as $studentId) {
            ClassAttendance::create([
                'teaching_session_id' => $session->id,
                'student_id' => $studentId,
                'status' => 'alpha',
                'scanned_at' => null
            ]);
            $updatedIds[] = $studentId;
        }

        return response()->json([
            'status' => 'success',
            'message' => count($updatedIds) . ' siswa berhasil ditandai Alpha.',
            'updated_ids' => array_values($updatedIds) // Reset index array agar bersih di JS
        ]);
    }

    // --- TUTUP KELAS & DISIPLIN  ---
    public function close($id)
    {
        DB::beginTransaction();
        try {
            $session = TeachingSession::with('schedule.schoolClass')->findOrFail($id);
            
            if ($session->status == 'closed') {
                return redirect()->route('teaching.index')->with('info', 'Kelas sudah ditutup sebelumnya.');
            }

            $classId = $session->schedule->school_class_id;
            $allStudents = Student::where('class_id', $classId)->get();
            $presentIds = ClassAttendance::where('teaching_session_id', $id)
                          ->pluck('student_id')->toArray();

            $alphaDiscipline = DisciplineType::firstOrCreate(
                ['name' => 'Bolos Pelajaran (Alpha)'],
                [
                    'type' => 'Pelanggaran', 
                    'point_value' => 10,     
                    'description' => 'Siswa tidak berada di kelas saat jam pelajaran.'
                ] 
            );

            $alphaCount = 0;
            foreach ($allStudents as $student) {
                if (!in_array($student->id, $presentIds)) {
                    
                    ClassAttendance::create([
                        'teaching_session_id' => $id,
                        'student_id' => $student->id,
                        'status' => 'alpha',
                        'scanned_at' => null
                    ]);

                    DisciplineRecord::create([
                        'student_id' => $student->id,
                        'discipline_type_id' => $alphaDiscipline->id,
                        'date' => Carbon::today(),
                        'notes' => 'Tidak mengikuti KBM: ' . $session->schedule->subject->name . ' (' . $session->topic . ')', 
                        'recorded_by_user_id' => Auth::id() 
                    ]);

                    $alphaCount++;
                }
            }

            $session->update([
                'ended_at' => Carbon::now(),
                'status' => 'closed'
            ]);

            DB::commit();
            
            return redirect()->route('teaching.index')->with('success', "Kelas ditutup. $alphaCount siswa ditandai Alpha & mendapat poin.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menutup kelas: ' . $e->getMessage());
        }
    }
}