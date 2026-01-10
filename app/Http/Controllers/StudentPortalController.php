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

// Model Opsional (Gunakan if class_exists untuk keamanan)
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
        // Sesuaikan juga index jika ada di folder students/portal
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
        // 1. Validasi Akses
        if (!Auth::guard('student')->check() || Auth::guard('student')->id() != $id) {
             return redirect()->route('portal.index')->with('error', 'Akses ditolak.');
        }

        Carbon::setLocale('id');
        $student = Student::with(['schoolClass', 'alumniProfile'])->findOrFail($id);
        $isAlumni = $student->status === 'graduated';

        // 2. DATA PENGHUBUNG (LIAISON)
        $liaison_messages = collect([]);
        if (class_exists(LiaisonBook::class)) { 
            try {
                $liaison_messages = LiaisonBook::with('teacher')
                    ->where('student_id', $student->id)
                    ->latest()
                    ->take(10) // Ambil 10 pesan terakhir
                    ->get();
            } catch (\Exception $e) {}
        }

        // 3. DATA KEHADIRAN
        $hadir = 0; $sakit = 0; $izin = 0; $alpa = 0;
        $attendance_history = collect([]);
        
        if (class_exists(AttendanceSiswa::class)) {
            $attQuery = AttendanceSiswa::where('student_id', $id);
            $hadir = (clone $attQuery)->whereIn('status', ['Hadir', 'Masuk', 'Terlambat'])->count();
            $sakit = (clone $attQuery)->where('status', 'Sakit')->count();
            $izin  = (clone $attQuery)->where('status', 'Izin')->count();
            $alpa  = (clone $attQuery)->where('status', 'Alpa')->count();
            $attendance_history = (clone $attQuery)->latest('attendance_date')->take(5)->get();
        }
        
        $attendanceChart = ['hadir' => $hadir, 'sakit' => $sakit, 'izin' => $izin, 'alpa' => $alpa];
        $total_hari = $hadir + $sakit + $izin + $alpa;
        $attendancePercentage = $total_hari > 0 ? round(($hadir / $total_hari) * 100) : 0;

        // 4. DATA POIN KEBAIKAN & PELANGGARAN
        $violations = collect([]);
        $achievements = collect([]);
        $total_violation_points = 0;
        $total_merit_points = 0;

        if (class_exists(DisciplineRecord::class)) {
            try {
                // Ambil Pelanggaran
                $violations = DisciplineRecord::with('disciplineType')
                    ->where('student_id', $id)
                    ->whereHas('disciplineType', fn($q) => $q->where('type', 'violation')) 
                    ->latest()->get();
                
                // Ambil Kebaikan / Prestasi
                $achievements = DisciplineRecord::with('disciplineType')
                    ->where('student_id', $id)
                    ->whereHas('disciplineType', fn($q) => $q->where('type', 'merit')) 
                    ->latest()->get();

                $total_violation_points = $violations->sum(fn($v) => $v->disciplineType->point_value ?? 0);
                $total_merit_points = $achievements->sum(fn($a) => $a->disciplineType->point_value ?? 0);

            } catch (QueryException $e) { }
        }

        // 5. JURNAL 7 KEBIASAAN HARI INI
        $todayEntry = null;
        if (class_exists(StudentHabit::class)) {
            $todayEntry = StudentHabit::where('student_id', $id)
                            ->whereDate('report_date', Carbon::today()) 
                            ->first();
        }

        // 6. DATA LAINNYA (LMS, Perpus, Akademik, KBM)
        $lms_assignments_grouped = []; $lms_grades = [];
        if ($student->school_class_id && class_exists(LmsAssignment::class)) {
             $assignments = LmsAssignment::with('subject')->where('class_id', $student->school_class_id)->latest()->get();
             $lms_assignments_grouped = $assignments->groupBy(fn($i) => $i->subject->name ?? 'Umum');
             
             if (class_exists(LmsSubmission::class)) {
                $submissions = LmsSubmission::where('student_id', $id)->get();
                foreach($submissions as $sub) { $lms_grades[$sub->assignment_id] = $sub->score; }
            }
        }
        
        $library_visits = 0; $library_history = collect([]);
        if (class_exists(LibraryLoan::class)) {
             $library_history = LibraryLoan::with('book')->where('student_id', $id)->latest()->take(5)->get();
             $library_visits = $library_history->count();
        }

        $academic_record = null; 
        $chartData = ['labels' => [], 'scores' => []];
        if (class_exists(AcademicRecord::class)) {
             $academic_record = AcademicRecord::with(['items.subject'])->where('student_id', $id)->latest()->first();
             if ($academic_record) {
                foreach($academic_record->items as $item) {
                    $chartData['labels'][] = $item->subject->name ?? 'Mapel';
                    $chartData['scores'][] = $item->score;
                }
            }
        }

        $teaching_journals = [];
        if (class_exists(TeachingJournal::class) && $student->schoolClass) {
             $teaching_journals = TeachingJournal::whereHas('schedule', fn($q)=>$q->where('class_id',$student->school_class_id))->latest()->take(5)->get();
        }

        // 7. PENGADUAN
        $complaints = collect([]);
        if (class_exists(Complaint::class)) {
            $complaints = Complaint::where('student_id', $student->id)->latest()->get();
        }

        // 8. DEFINISI TABS MENU
        $tabs = ['ringkasan' => ['icon' => 'squares-four', 'label' => 'Ringkasan']];

        if ($isAlumni) {
            $tabs['prestasi'] = ['icon' => 'trophy', 'label' => 'Riwayat Prestasi'];
            $tabs['perpustakaan'] = ['icon' => 'books', 'label' => 'Riwayat Pustaka'];
        } else {
            // Urutan Tab yang disarankan
            $tabs = array_merge($tabs, [
                'kebiasaan' => ['icon' => 'sun-horizon', 'label' => '7 Kebiasaan'],
                'poin_kebaikan' => ['icon' => 'scales', 'label' => 'Poin Kebaikan'], // Gabungan Disiplin & Merit
                'penghubung' => ['icon' => 'notebook', 'label' => 'Buku Penghubung'],
                'pengaduan' => ['icon' => 'megaphone', 'label' => 'Lapor Masalah'],
                'jadwal' => ['icon' => 'calendar-blank', 'label' => 'Jadwal & KBM'], // Digabung biar ringkas
                'akademik' => ['icon' => 'exam', 'label' => 'Nilai Rapor'],
                'lms' => ['icon' => 'clipboard-text', 'label' => 'Tugas Online'],
                'kehadiran' => ['icon' => 'calendar-check', 'label' => 'Riwayat Absen'],
            ]);
        }

        // Statistik Sholat (Opsional)
        $sholat_dhuha = 0; $sholat_dhuhur = 0;
        if (class_exists(AttendanceSiswa::class)) {
            $sholat_dhuha = AttendanceSiswa::where('student_id', $id)->where('type', 'Keagamaan')->where('activity', 'Dhuha')->count();
            $sholat_dhuhur = AttendanceSiswa::where('student_id', $id)->where('type', 'Keagamaan')->where('activity', 'Dhuhur')->count();
        }

        // --- PERBAIKAN LOKASI VIEW ---
        // Jika file ada di resources/views/students/portal/show.blade.php
        if (view()->exists('students.portal.show')) {
            return view('students.portal.show', compact(
                'student', 'isAlumni', 'tabs', 'attendancePercentage',
                'liaison_messages', 'complaints', 'todayEntry',
                'hadir', 'sakit', 'izin', 'alpa', 'attendance_history', 'attendanceChart',
                'lms_assignments_grouped', 'lms_grades',
                'violations', 'total_violation_points', 
                'achievements', 'total_merit_points',
                'library_visits', 'library_history',
                'academic_record', 'chartData', 'teaching_journals',
                'sholat_dhuha', 'sholat_dhuhur'
            ));
        }
        
        // Fallback (Jaga-jaga)
        return view('portal.show', compact(
            'student', 'isAlumni', 'tabs', 'attendancePercentage',
            'liaison_messages', 'complaints', 'todayEntry',
            'hadir', 'sakit', 'izin', 'alpa', 'attendance_history', 'attendanceChart',
            'lms_assignments_grouped', 'lms_grades',
            'violations', 'total_violation_points', 
            'achievements', 'total_merit_points',
            'library_visits', 'library_history',
            'academic_record', 'chartData', 'teaching_journals',
            'sholat_dhuha', 'sholat_dhuhur'
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