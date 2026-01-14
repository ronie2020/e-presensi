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
                    ->take(10) 
                    ->get();
            } catch (\Exception $e) {}
        }

        // 3. DATA KEHADIRAN
        // [PERBAIKAN LOGIKA HITUNG STATISTIK]
        $hadir = 0; $terlambat = 0; $sakit = 0; $izin = 0; $alpa = 0;
        $attendance_history = collect([]);
        $rawAttendanceRecords = collect([]); // Penampung data untuk poin otomatis
        
        if (class_exists(AttendanceSiswa::class)) {
            $attQuery = AttendanceSiswa::where('student_id', $id);
            
            // Variasi penulisan status untuk query SQL yang lebih robust
            $statusHadir = ['Hadir', 'Masuk', 'Terlambat', 'hadir', 'masuk', 'terlambat'];
            $statusTelat = ['Terlambat', 'terlambat'];
            $statusSakit = ['Sakit', 'sakit'];
            $statusIzin  = ['Izin', 'izin'];
            // [FIX] Menambahkan variasi huruf kecil untuk Alfa/Alpa
            $statusAlpa  = ['Alfa', 'Alpa', 'Alpha', 'alfa', 'alpa', 'alpha', 'Tanpa Keterangan'];

            // Hitung Statistik dengan array status yang diperluas
            $hadir = (clone $attQuery)->whereIn('status', $statusHadir)->count();
            $terlambat = (clone $attQuery)->whereIn('status', $statusTelat)->count();
            $sakit = (clone $attQuery)->whereIn('status', $statusSakit)->count();
            $izin  = (clone $attQuery)->whereIn('status', $statusIzin)->count();
            $alpa  = (clone $attQuery)->whereIn('status', $statusAlpa)->count(); 
            
            // History untuk tab kehadiran
            $attendance_history = (clone $attQuery)->latest('attendance_date')->take(10)->get();

            // [PENTING] Ambil data tahun ini untuk diolah jadi Pelanggaran/Prestasi Otomatis
            $currentYearStart = Carbon::now()->startOfYear(); 
            $rawAttendanceRecords = (clone $attQuery)
                                    ->whereDate('attendance_date', '>=', $currentYearStart)
                                    ->orderBy('attendance_date', 'desc')
                                    ->get();
        }
        
        $attendanceChart = ['hadir' => $hadir, 'sakit' => $sakit, 'izin' => $izin, 'alpa' => $alpa];
        $total_hari_efektif = $hadir + $sakit + $izin + $alpa;
        $attendancePercentage = $total_hari_efektif > 0 ? round(($hadir / $total_hari_efektif) * 100) : 0;

        // ==========================================
        // 4. DATA POIN KEBAIKAN & PELANGGARAN (FIXED)
        // ==========================================
        $violations = collect([]);
        $achievements = collect([]);
        
        // A. Pelanggaran Manual (Dari Database)
        $manualViolations = collect([]);
        if (class_exists(DisciplineRecord::class)) {
            try {
                $manualViolations = DisciplineRecord::with('disciplineType')
                    ->where('student_id', $id)
                    ->get() 
                    ->filter(function($record) {
                        $type = strtolower(optional($record->disciplineType)->type ?? $record->type ?? '');
                        return in_array($type, ['violation', 'pelanggaran']);
                    });
            } catch (QueryException $e) { }
        }

        // B. Pelanggaran Otomatis (ALPA dari Absensi)
        // Kita format agar strukturnya MIRIP dengan DisciplineRecord (punya 'notes' dan 'disciplineType')
        $alpaViolations = $rawAttendanceRecords
            ->filter(function ($att) {
                // Filter PHP ini sudah case-insensitive dan berhasil menangkap data
                return in_array(strtolower($att->status), ['alfa', 'alpa', 'alpha']);
            })
            ->map(function ($att) {
                // Return sebagai Object stdClass dengan struktur yang diharapkan View
                return (object) [
                    'date' => $att->attendance_date,
                    'notes' => 'Ketidakhadiran Tanpa Keterangan (Alpa)', // View pakai ->notes
                    'disciplineType' => (object) [ // Mock relasi disciplineType
                        'name' => 'Absensi (Alpha)',
                        'point_value' => 10, // Poin Alpa
                        'type' => 'violation'
                    ]
                ];
            });

        // Gabungkan Manual & Otomatis
        $violations = $manualViolations->concat($alpaViolations)->sortByDesc('date');

        // C. Prestasi/Kebaikan Manual (Dari Database)
        $manualAchievements = collect([]);
        if (class_exists(DisciplineRecord::class)) {
            try {
                $manualAchievements = DisciplineRecord::with('disciplineType')
                    ->where('student_id', $id)
                    ->get()
                    ->filter(function($record) {
                        $type = strtolower(optional($record->disciplineType)->type ?? $record->type ?? '');
                        return in_array($type, ['merit', 'prestasi', 'kebaikan']);
                    });
            } catch (QueryException $e) { }
        }

        // D. Poin Kebaikan Otomatis (SHALAT dari Absensi)
        // Logika: Type='Keagamaan' DAN Activity mengandung kata 'Dhuha'/'Dhuhur'
        $prayerAchievements = $rawAttendanceRecords
            ->filter(function ($att) {
                $isReligious = isset($att->type) && strtolower($att->type) === 'keagamaan';
                $activity = strtolower($att->activity ?? '');
                $isPrayer = str_contains($activity, 'dhuha') || str_contains($activity, 'dhuhur') || str_contains($activity, 'dzuhur');
                return $isReligious && $isPrayer;
            })
            ->map(function ($att) {
                $actName = ucfirst($att->activity ?? 'Ibadah');
                return (object) [
                    'date' => $att->attendance_date,
                    'notes' => "Melaksanakan " . $actName . " Berjamaah", // View pakai ->notes
                    'disciplineType' => (object) [ // Mock relasi
                        'name' => 'Kegiatan Keagamaan',
                        'point_value' => 5, // Poin Shalat
                        'type' => 'merit'
                    ]
                ];
            });

        // Gabungkan Kebaikan
        $achievements = $manualAchievements->concat($prayerAchievements)->sortByDesc('date');

        // Hitung Total Poin (Akses properti point_value dari disciplineType, baik object asli maupun mock)
        $total_violation_points = $violations->sum(function($v) {
            return $v->disciplineType->point_value ?? 0;
        });
        
        $total_merit_points = $achievements->sum(function($a) {
            return $a->disciplineType->point_value ?? 0;
        });

        // Skor Akhir (Opsional jika dipakai di view)
        $finalScore = 100 - $total_violation_points + $total_merit_points;

        // 5. JURNAL 7 KEBIASAAN (LOGIKA LAMA DIPERTAHANKAN)
        $todayEntry = null;
        $habits = collect([]); 
        if (class_exists(StudentHabit::class)) {
            $todayEntry = StudentHabit::where('student_id', $id)
                            ->whereDate('report_date', Carbon::today()) 
                            ->first();
            $habits = StudentHabit::where('student_id', $id)
                        ->orderBy('report_date', 'desc')
                        ->get();
        }

        // 6. DATA LAINNYA (LOGIKA LAMA DIPERTAHANKAN)
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

        // 7. PENGADUAN (LOGIKA LAMA DIPERTAHANKAN)
        $complaints = collect([]);
        if (class_exists(Complaint::class)) {
            $complaints = Complaint::where('student_id', $student->id)->latest()->get();
        }

        // 8. TABS MENU (LOGIKA LAMA DIPERTAHANKAN)
        $tabs = ['ringkasan' => ['icon' => 'squares-four', 'label' => 'Ringkasan']];

        if ($isAlumni) {
            $tabs['prestasi'] = ['icon' => 'trophy', 'label' => 'Riwayat Prestasi'];
            $tabs['perpustakaan'] = ['icon' => 'books', 'label' => 'Riwayat Pustaka'];
        } else {
            $tabs = array_merge($tabs, [
                'kebiasaan' => ['icon' => 'sun-horizon', 'label' => '7 Kebiasaan'],
                'poin_kebaikan' => ['icon' => 'scales', 'label' => 'Poin Kebaikan'], 
                'penghubung' => ['icon' => 'notebook', 'label' => 'Buku Penghubung'],
                'pengaduan' => ['icon' => 'megaphone', 'label' => 'Lapor Masalah'],
                'jadwal' => ['icon' => 'calendar-blank', 'label' => 'Jadwal & KBM'],
                'akademik' => ['icon' => 'exam', 'label' => 'Nilai Rapor'],
                'lms' => ['icon' => 'clipboard-text', 'label' => 'Tugas Online'],
                'kehadiran' => ['icon' => 'calendar-check', 'label' => 'Riwayat Absen'],
            ]);
        }

        // Statistik Sholat (Counter Widget)
        $sholat_dhuha = 0; $sholat_dhuhur = 0;
        if (class_exists(AttendanceSiswa::class)) {
            $sholat_dhuha = AttendanceSiswa::where('student_id', $id)->where('type', 'Keagamaan')->where('activity', 'Dhuha')->count();
            $sholat_dhuhur = AttendanceSiswa::where('student_id', $id)->where('type', 'Keagamaan')->where('activity', 'Dhuhur')->count();
        }

        // Compact Data
        $data = compact(
            'student', 'isAlumni', 'tabs', 'attendancePercentage',
            'liaison_messages', 'complaints', 
            'todayEntry', 'habits',
            'hadir', 'terlambat', 'sakit', 'izin', 'alpa', 
            'attendance_history', 'attendanceChart',
            'lms_assignments_grouped', 'lms_grades',
            'violations', 'total_violation_points', 
            'achievements', 'total_merit_points',
            'library_visits', 'library_history',
            'academic_record', 'chartData', 'teaching_journals',
            'sholat_dhuha', 'sholat_dhuhur',
            'finalScore'
        );

        if (view()->exists('students.portal.show')) {
            return view('students.portal.show', $data);
        }
        
        return view('portal.show', $data);
    }

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