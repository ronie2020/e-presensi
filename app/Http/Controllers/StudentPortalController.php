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
use App\Models\Achievement; // [BARU] Import Model Achievement

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
        $hadir = 0; $terlambat = 0; $sakit = 0; $izin = 0; $alpa = 0;
        $attendance_history = collect([]);
        $rawAttendanceRecords = collect([]); // Penampung data tahun ini untuk poin otomatis
        
        if (class_exists(AttendanceSiswa::class)) {
            $attQuery = AttendanceSiswa::where('student_id', $id);
            
            // Variasi penulisan status untuk query SQL yang lebih robust
            $statusHadir = ['Hadir', 'Masuk', 'Terlambat', 'hadir', 'masuk', 'terlambat'];
            $statusTelat = ['Terlambat', 'terlambat'];
            $statusSakit = ['Sakit', 'sakit'];
            $statusIzin  = ['Izin', 'izin'];
            $statusAlpa  = ['Alfa', 'Alpa', 'Alpha', 'alfa', 'alpa', 'alpha', 'Tanpa Keterangan'];

            // Hitung Statistik
            $hadir = (clone $attQuery)->whereIn('status', $statusHadir)->count();
            $terlambat = (clone $attQuery)->whereIn('status', $statusTelat)->count();
            $sakit = (clone $attQuery)->whereIn('status', $statusSakit)->count();
            $izin  = (clone $attQuery)->whereIn('status', $statusIzin)->count();
            $alpa  = (clone $attQuery)->whereIn('status', $statusAlpa)->count(); 
            
            // History Timeline
            $attendance_history = (clone $attQuery)->latest('attendance_date')->take(10)->get();

            // [TAMBAHAN] Ambil data tahun ini untuk perhitungan poin otomatis (Alpa & Shalat)
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
        // 4. DATA POIN KEBAIKAN & PELANGGARAN (LENGKAP)
        // ==========================================
        $violations = collect([]);
        $achievements = collect([]);
        
        // --- A. PELANGGARAN (Manual + Otomatis Alpa) ---
        $manualViolations = collect([]);
        if (class_exists(DisciplineRecord::class)) {
            try {
                // Eager Load 'recorder' agar tidak query N+1 dan data tersedia
                $manualViolations = DisciplineRecord::with(['disciplineType', 'recorder']) 
                    ->where('student_id', $id)
                    ->get() 
                    ->filter(function($record) {
                        $type = strtolower(optional($record->disciplineType)->type ?? $record->type ?? '');
                        return in_array($type, ['violation', 'pelanggaran']);
                    })
                    ->map(function($item) {
                        return (object) [
                            'date' => $item->date,
                            'notes' => $item->notes ?? optional($item->disciplineType)->name ?? 'Pelanggaran',
                            'point' => optional($item->disciplineType)->point_value ?? $item->point ?? 0,
                            'type' => 'manual',
                            'recorder' => $item->recorder ?? (object)['name' => 'Admin/Guru'],
                            'disciplineType' => $item->disciplineType ?? (object)['name' => 'Pelanggaran', 'point_value' => 0]
                        ];
                    });
            } catch (QueryException $e) { }
        }

        $alpaViolations = $rawAttendanceRecords
            ->filter(function ($att) {
                return in_array(strtolower($att->status), ['alfa', 'alpa', 'alpha']);
            })
            ->map(function ($att) {
                return (object) [
                    'date' => $att->attendance_date,
                    'notes' => 'Ketidakhadiran Tanpa Keterangan (Alpa)',
                    'point' => 10, // Poin Default Alpa
                    'type' => 'auto',
                    'recorder' => (object) ['name' => 'Sistem Otomatis'],
                    'disciplineType' => (object) ['name' => 'Absensi (Alpha)', 'point_value' => 10]
                ];
            });

        $violations = $manualViolations->concat($alpaViolations)->sortByDesc('date');

        // --- B. PRESTASI / KEBAIKAN (Manual + Shalat + Achievement Model) ---
        
        // 1. Prestasi Manual (DisciplineRecord)
        $manualMerits = collect([]);
        if (class_exists(DisciplineRecord::class)) {
            try {
                $manualMerits = DisciplineRecord::with(['disciplineType', 'recorder'])
                    ->where('student_id', $id)
                    ->get()
                    ->filter(function($record) {
                        $type = strtolower(optional($record->disciplineType)->type ?? $record->type ?? '');
                        return in_array($type, ['merit', 'prestasi', 'kebaikan']);
                    })
                    ->map(function($item) {
                        return (object) [
                            'date' => $item->date,
                            'notes' => $item->notes ?? optional($item->disciplineType)->name ?? 'Prestasi',
                            'point' => optional($item->disciplineType)->point_value ?? $item->point ?? 0,
                            'type' => 'manual_merit',
                            'level' => null, // Tidak ada level
                            'photo' => null,
                            'recorder' => $item->recorder ?? (object)['name' => 'Admin/Guru'],
                            'disciplineType' => $item->disciplineType ?? (object)['name' => 'Prestasi', 'point_value' => 0]
                        ];
                    });
            } catch (QueryException $e) { }
        }

        // 2. Poin Shalat Otomatis
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
                    'notes' => "Melaksanakan Shalat " . $actName . " Berjamaah",
                    'point' => 5, // Poin Shalat
                    'type' => 'auto_prayer',
                    'level' => null,
                    'photo' => null,
                    'recorder' => (object) ['name' => 'Sistem Otomatis'],
                    'disciplineType' => (object) ['name' => 'Kegiatan Keagamaan', 'point_value' => 5]
                ];
            });

        // 3. Data Prestasi Utama (Model Achievement)
        $realAchievements = collect([]);
        if (class_exists(Achievement::class)) {
            try {
                $realAchievements = Achievement::where('student_id', $id)->get()
                    ->map(function($item) {
                        return (object) [
                            'date' => $item->date,
                            'notes' => $item->description ?? $item->title,
                            'point' => 0, // Poin 0 di list ini (karena poin real sudah masuk DisciplineRecord via Job)
                            'type' => 'achievement_record',
                            'title' => $item->title,
                            'level' => $item->level,
                            'photo' => $item->photo_path,
                            'recorder' => (object) ['name' => 'Panitia/Sekolah'],
                            'disciplineType' => (object) ['name' => 'Kejuaraan / Prestasi', 'point_value' => 0]
                        ];
                    });
            } catch (\Exception $e) {}
        }

        $achievements = $realAchievements->concat($manualMerits)->concat($prayerAchievements)->sortByDesc('date');

        // Hitung Total Skor
        // Total poin dihitung dari DisciplineRecord (manualMerits) + Shalat + Alpa
        // AchievementRecord tidak dihitung poinnya disini karena asumsinya poinnya sudah di-entry ke DisciplineRecord saat input prestasi
        $total_violation_points = $violations->sum(fn($v) => $v->point ?? $v->disciplineType->point_value ?? 0);
        $total_merit_points = $manualMerits->sum(fn($a) => $a->point ?? $a->disciplineType->point_value ?? 0) + $prayerAchievements->sum(fn($a) => $a->point ?? 0);
        
        $finalScore = 100 - $total_violation_points + $total_merit_points;

        // 5. JURNAL 7 KEBIASAAN
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

        // 6. DATA LAINNYA (LMS, PERPUS, AKADEMIK)
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

        // ==========================================
        // 8. TABS MENU (PERBAIKAN KUNCI TAB)
        // ==========================================
        $tabs = ['ringkasan' => ['icon' => 'squares-four', 'label' => 'Ringkasan']];

        if ($isAlumni) {
            $tabs['prestasi'] = ['icon' => 'trophy', 'label' => 'Riwayat Prestasi'];
            $tabs['perpustakaan'] = ['icon' => 'books', 'label' => 'Riwayat Pustaka'];
        } else {
            $tabs = array_merge($tabs, [
                'kebiasaan' => ['icon' => 'sun-horizon', 'label' => '7 Kebiasaan'],
                
                // [FIX] Memisahkan Disiplin dan Prestasi agar masing-masing punya Tab sendiri
                'disiplin' => ['icon' => 'warning-octagon', 'label' => 'Disiplin & Poin'], 
                'prestasi' => ['icon' => 'medal', 'label' => 'Prestasi'], 
                
                'penghubung' => ['icon' => 'notebook', 'label' => 'Buku Penghubung'],
                'pengaduan' => ['icon' => 'megaphone', 'label' => 'Lapor Masalah'],
                'jadwal' => ['icon' => 'calendar-blank', 'label' => 'Jadwal & KBM'],
                'akademik' => ['icon' => 'exam', 'label' => 'Nilai Rapor'],
                'lms' => ['icon' => 'clipboard-text', 'label' => 'Tugas Online'],
                'kehadiran' => ['icon' => 'calendar-check', 'label' => 'Riwayat Absen'],
            ]);
        }

        $sholat_dhuha = 0; $sholat_dhuhur = 0;
        if (class_exists(AttendanceSiswa::class)) {
            $sholat_dhuha = AttendanceSiswa::where('student_id', $id)->where('type', 'Keagamaan')->where('activity', 'Dhuha')->count();
            $sholat_dhuhur = AttendanceSiswa::where('student_id', $id)->where('type', 'Keagamaan')->where('activity', 'Dhuhur')->count();
        }

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