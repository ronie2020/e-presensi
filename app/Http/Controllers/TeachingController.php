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
     * MODIFIKASI: Hanya menampilkan jadwal HARI INI milik guru yang login.
     */
    public function index()
    {
        $teacherId = Auth::id();
        
        // 1. Set Locale ke Indonesia agar cocok dengan data 'day' di database ('Senin', 'Selasa', dst)
        Carbon::setLocale('id');
        $todayName = Carbon::now()->translatedFormat('l'); // Output: "Senin", "Selasa", dst.

        $schedules = Schedule::with(['schoolClass', 'subject'])
                    ->where('teacher_id', $teacherId)
                    ->where('day', $todayName) // <--- FILTER TAMBAHAN (Hanya Hari Ini)
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

    // --- HALAMAN KELAS BERLANGSUNG ---
    public function show($id)
    {
        $session = TeachingSession::with(['schedule.schoolClass.students', 'schedule.subject', 'attendances.student'])
                    ->findOrFail($id);
        
        $presentCount = $session->attendances->whereIn('status', ['present', 'late'])->count();
        $totalStudents = $session->schedule->schoolClass->students->count();

        return view('teaching.show', compact('session', 'presentCount', 'totalStudents'));
    }

    // --- UPDATE JURNAL ---
    public function update(Request $request, $id)
    {
        $session = TeachingSession::findOrFail($id);
        
        $request->validate([
            'topic' => 'nullable|string|max:255',
            'activities' => 'nullable|string',
            'photo_proof' => 'nullable|image|max:5120', 
            'video_link' => 'nullable|url',
        ]);

        $data = [
            'topic' => $request->topic,
            'activities' => $request->activities,
            'reference_link' => $request->reference_link,
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
        $month = $request->input('month', Carbon::now()->format('Y-m'));

        $histories = TeachingSession::with(['schedule.schoolClass', 'schedule.subject'])
                    ->withCount([
                        'attendances as hadir' => function($q){ $q->whereIn('status', ['present', 'late', 'masuk']); },
                        'attendances as alpha' => function($q){ $q->where('status', 'alpha'); },
                        'attendances as sakit' => function($q){ $q->where('status', 'sick'); },
                        'attendances as izin' => function($q){ $q->where('status', 'permission'); },
                    ])
                    ->where('teacher_id', $teacherId)
                    ->where('status', 'closed')
                    ->where('date', 'like', "$month%")
                    ->orderBy('date', 'desc')
                    ->orderBy('started_at', 'desc')
                    ->paginate(10)
                    ->withQueryString();

        return view('teaching.history', compact('histories', 'month'));
    }

    // --- SCAN RFID ---
    public function scan(Request $request)
    {
        $request->validate([
            'rfid' => 'required',
            'session_id' => 'required'
        ]);

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

    // --- ABSEN MANUAL ---
    public function storeManual(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:teaching_sessions,id',
            'student_id' => 'required|exists:students,id',
            'status'     => 'required|in:present,sick,permission,alpha',
        ]);

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

        return response()->json([
            'status' => 'success',
            'message' => 'Status siswa berhasil diperbarui.',
            'data' => $attendance
        ]);
    }

    // --- TUTUP KELAS & DISIPLIN  ---
    public function close($id)
    {
        DB::beginTransaction();
        try {
            $session = TeachingSession::with('schedule.schoolClass')->findOrFail($id);
            
            if ($session->status == 'closed') {
                return redirect()->route('dashboard')->with('info', 'Kelas sudah ditutup sebelumnya.');
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
            return redirect()->route('dashboard')->with('success', "Kelas ditutup. $alphaCount siswa ditandai Alpha & mendapat poin.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menutup kelas: ' . $e->getMessage());
        }
    }
}