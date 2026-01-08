<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

// --- IMPORT MODELS ---
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\AttendanceSiswa; 
use App\Models\LmsAssignment;
use App\Models\LmsSubmission;
use App\Models\Complaint;       
use App\Models\LiaisonBook;     
use App\Models\StudentHabit;    

// Model Opsional 
use App\Models\LibraryLoan;      
use App\Models\DisciplineRecord; 
use App\Models\AcademicRecord;   
use App\Models\TeachingJournal; 

class StudentPortalController extends Controller
{
    /**
     * Halaman Dashboard Portal (PUBLIC LANDING)
     */
    public function index()
    {
        if (view()->exists('students.portal.index')) {
            return view('students.portal.index');
        }
        return view('portal.index');
    }

    /**
     * Proses Pencarian Siswa berdasarkan NISN
     */
    public function search(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
        ]);

        $student = Student::where('student_id', $request->student_id)
                    ->orWhere('nis', $request->student_id)
                    ->first();

        if (!$student) {
            return back()->with('error', 'Data siswa tidak ditemukan. Periksa kembali Nomor Induk Anda.');
        }

        Auth::guard('student')->login($student);

        return redirect()->route('portal.show', $student->id)
                         ->with('success', 'Berhasil masuk ke Portal Informasi.');
    }

    /**
     * Halaman Utama Dashboard Siswa (SHOW)
     */
    public function show($id)
    {
        if (!Auth::guard('student')->check() || Auth::guard('student')->id() != $id) {
             return redirect()->route('portal.index')->with('error', 'Akses ditolak.');
        }

        // Set Locale Indonesia
        Carbon::setLocale('id');

        $student = Student::with(['schoolClass', 'alumniProfile'])->findOrFail($id);

        // --- [LOGIKA VIEW DIPINDAHKAN KE SINI] ---
        $isAlumni = $student->status === 'graduated';

        // 1. DATA PENGHUBUNG
        $liaison_messages = collect([]);
        if (class_exists(LiaisonBook::class)) { 
            try {
                $liaison_messages = LiaisonBook::with('teacher')
                    ->where('student_id', $student->id)
                    ->latest()
                    ->paginate(5);
            } catch (\Exception $e) {}
        }

        // 2. DATA KEHADIRAN (ATTENDANCE)
        $hadir = 0; $sakit = 0; $izin = 0; $alpa = 0;
        $attendance_history = collect([]);

        $attendanceModel = class_exists(AttendanceSiswa::class) ? AttendanceSiswa::class : (class_exists(\App\Models\Attendance::class) ? \App\Models\Attendance::class : null);

        if ($attendanceModel) {
            $attendanceQuery = $attendanceModel::where('student_id', $id);
            
            $hadir = (clone $attendanceQuery)->whereIn('status', ['Hadir', 'Masuk', 'Terlambat'])->count();
            $sakit = (clone $attendanceQuery)->where('status', 'Sakit')->count();
            $izin  = (clone $attendanceQuery)->where('status', 'Izin')->count();
            $alpa  = (clone $attendanceQuery)->where('status', 'Alpa')->count();
            
            $dateCol = \Schema::hasColumn((new $attendanceModel)->getTable(), 'attendance_date') ? 'attendance_date' : 'created_at';
            $attendance_history = (clone $attendanceQuery)->latest($dateCol)->take(5)->get();
        }
        
        $attendanceChart = [
            'hadir' => $hadir, 'sakit' => $sakit, 'izin' => $izin, 'alpa' => $alpa
        ];

        // [BARU] Hitung Persentase Kehadiran
        $total_hari = $hadir + $sakit + $izin + $alpa;
        $attendancePercentage = $total_hari > 0 ? round(($hadir / $total_hari) * 100) : 0;

        // 3. DATA LMS (TUGAS & KUIS)
        $lms_assignments_grouped = [];
        $lms_grades = [];
        
        if ($student->school_class_id && class_exists(LmsAssignment::class)) {
            $colClassId = \Schema::hasColumn('students', 'school_class_id') ? 'school_class_id' : 'class_id';
            
            $assignments = LmsAssignment::with('subject')
                            ->where('class_id', $student->$colClassId)
                            ->latest()
                            ->get();
            
            $lms_assignments_grouped = $assignments->groupBy(function($item) {
                return $item->subject->name ?? 'Umum';
            });

            if (class_exists(LmsSubmission::class)) {
                $submissions = LmsSubmission::where('student_id', $id)->get();
                foreach($submissions as $sub) {
                    $lms_grades[$sub->assignment_id] = $sub->score;
                }
            }
        }

        // 4. DATA DISIPLIN & PRESTASI
        $violations = collect([]);
        $achievements = collect([]);
        $total_violation_points = 0;
        $total_merit_points = 0;

        if (class_exists(DisciplineRecord::class)) {
            try {
                $baseQuery = DisciplineRecord::with('disciplineType')->where('student_id', $id);

                $violations = (clone $baseQuery)
                    ->whereHas('disciplineType', function($q) { $q->where('type', 'violation'); })
                    ->latest()->get();
                    
                $achievements = (clone $baseQuery)
                    ->whereHas('disciplineType', function($q) { $q->where('type', 'merit'); })
                    ->latest()->get();

                $total_violation_points = $violations->sum(fn($v) => $v->disciplineType->point_value ?? 0);
                $total_merit_points = $achievements->sum(fn($a) => $a->disciplineType->point_value ?? 0);

            } catch (QueryException $e) { }
        }

        // 5. PERPUSTAKAAN
        $library_visits = 0; 
        $library_history = collect([]);
        
        if (class_exists(LibraryLoan::class)) {
            try {
                $library_history = LibraryLoan::with('book')
                    ->where('student_id', $id)
                    ->latest()
                    ->take(10)
                    ->get();
                $library_visits = $library_history->count(); 
            } catch (\Exception $e) {}
        }

        // 6. AKADEMIK (NILAI RAPOR)
        $academic_record = null;
        $chartData = ['labels' => [], 'scores' => []];

        if (class_exists(AcademicRecord::class)) {
            try {
                $academic_record = AcademicRecord::with(['items.subject'])
                    ->where('student_id', $id)
                    ->latest()
                    ->first();
                
                if ($academic_record) {
                    foreach($academic_record->items as $item) {
                        $chartData['labels'][] = $item->subject->name ?? 'Mapel';
                        $chartData['scores'][] = $item->score;
                    }
                }
            } catch (\Exception $e) {}
        }

        // 7. JURNAL KBM & KEAGAMAAN
        $teaching_journals = [];
        $sholat_dhuha = 0;
        $sholat_dhuhur = 0;

        if (class_exists(TeachingJournal::class) && $student->schoolClass) {
            $teaching_journals = TeachingJournal::whereHas('schedule', function($q) use ($student) {
                                    $q->where('class_id', $student->school_class_id ?? $student->class_id);
                                 })->latest()->take(5)->get();
        }

        // 8. DATA PENGADUAN
        $complaints = collect([]);
        if (class_exists(Complaint::class)) {
            try {
                $complaints = Complaint::where('student_id', $student->id)->latest()->get();
            } catch (\Exception $e) {}
        }

        // 9. CEK JURNAL 7 KEBIASAAN HARI INI
        $todayEntry = null;
        if (class_exists(StudentHabit::class)) {
            $todayEntry = StudentHabit::where('student_id', $id)
                            ->whereDate('created_at', Carbon::today())
                            ->first();
        }

        // --- [BARU] DEFINISI TABS MENU ---
        $tabs = [
            'ringkasan' => ['icon' => 'squares-four', 'label' => 'Ringkasan'],
        ];

        if ($isAlumni) {
            $tabs['prestasi'] = ['icon' => 'trophy', 'label' => 'Riwayat Prestasi'];
            $tabs['perpustakaan'] = ['icon' => 'books', 'label' => 'Riwayat Pustaka'];
        } else {
            $tabs = array_merge($tabs, [
                'kebiasaan' => ['icon' => 'sun-horizon', 'label' => '7 Kebiasaan'],
                'penghubung' => ['icon' => 'notebook', 'label' => 'Buku Penghubung'],
                'pengaduan' => ['icon' => 'megaphone', 'label' => 'Pengaduan'],
                'jadwal' => ['icon' => 'calendar-blank', 'label' => 'Jadwal'], 
                'lms' => ['icon' => 'clipboard-text', 'label' => 'Tugas & Kuis'],
                'kbm' => ['icon' => 'chalkboard-teacher', 'label' => 'Jurnal KBM'],
                'akademik' => ['icon' => 'exam', 'label' => 'Nilai Rapor'],
                'kehadiran' => ['icon' => 'calendar-check', 'label' => 'Kehadiran'],
                'disiplin' => ['icon' => 'warning-circle', 'label' => 'Disiplin'],
                'prestasi' => ['icon' => 'trophy', 'label' => 'Prestasi'],
                'perpustakaan' => ['icon' => 'books', 'label' => 'Pustaka'],
            ]);
        }

        // 10. RETURN VIEW
        $viewName = 'portal.show';
        if (view()->exists('students.portal.show')) {
            $viewName = 'students.portal.show';
        } elseif (view()->exists('student.show')) {
            $viewName = 'student.show';
        }
        
        return view($viewName, compact(
            'student', 
            'isAlumni', // Dikirim dari controller
            'tabs', // Dikirim dari controller
            'attendancePercentage', // Dikirim dari controller
            'liaison_messages', 
            'hadir', 'sakit', 'izin', 'alpa', 'attendance_history', 'attendanceChart',
            'lms_assignments_grouped', 'lms_grades',
            'violations', 'total_violation_points', 
            'achievements', 'total_merit_points',
            'library_visits', 'library_history',
            'academic_record', 'chartData',
            'teaching_journals',
            'sholat_dhuha', 'sholat_dhuhur',
            'complaints', 
            'todayEntry'
        ));
    }

    /**
     * Cetak Kartu OSIS / Biodata
     */
    public function printCard($id)
    {
        if (!Auth::guard('student')->check() || Auth::guard('student')->id() != $id) {
             return redirect()->route('portal.index');
        }

        $student = Student::with('schoolClass')->findOrFail($id);
        
        if (view()->exists('students.osis_card')) {
            return view('students.osis_card', compact('student'));
        }
        
        return back()->with('error', 'Template kartu belum tersedia.');
    }
}