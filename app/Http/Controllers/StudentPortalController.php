<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

// --- IMPORT MODELS ---
use App\Models\Student;
use App\Models\RamadanLog;
use App\Models\SchoolClass;
use App\Models\AttendanceSiswa; 
use App\Models\LmsAssignment;
use App\Models\LmsSubmission;
use App\Models\LmsMaterial; 
use App\Models\Complaint;       
use App\Models\LiaisonBook;     
use App\Models\StudentHabit; 
use App\Models\Borrowing;        
use App\Models\DisciplineRecord; 
use App\Models\AcademicRecord;  
use App\Models\TeachingSession;
use App\Models\Achievement; 
use App\Models\BkSession;
use App\Models\Book; 
use App\Models\EbookRead; 

class StudentPortalController extends Controller
{
    public function index()
    {
        if (view()->exists('students.portal.index')) {
            return view('students.portal.index');
        }
        return view('portal.index');
    }

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

    public function show($id)
    {
        // 1. VALIDASI AKSES
        if (!Auth::guard('student')->check() || Auth::guard('student')->id() != $id) {
             return redirect()->route('portal.index')->with('error', 'Akses ditolak.');
        }

        Carbon::setLocale('id');
        $student = Student::with(['schoolClass', 'alumniProfile'])->findOrFail($id);
        $isAlumni = $student->status === 'graduated';
        
        $classId = $student->class_id ?? $student->school_class_id ?? optional($student->schoolClass)->id;

         // --- 2. DATA RAMADHAN ---
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        
        // A. Log Hari Ini (Untuk Form Input)
        $todayRamadanLog = RamadanLog::where('student_id', $student->id)
                            ->whereDate('date', $today)
                            ->first();

        // B. Log Terakhir yang Sudah Dinilai (Untuk Feedback)
        $lastVerifiedLog = RamadanLog::where('student_id', $student->id)
                            ->whereNotNull('teacher_verified_at')
                            ->orderBy('date', 'desc')
                            ->first();

        // C. LEADERBOARD (LOGIKA DETAIL - AMALAN)
        // Menghitung poin berdasarkan kualitas ibadah, bukan sekedar jumlah log
        $topRamadanStudents = Student::with(['ramadanLogs', 'schoolClass'])
            ->get()
            ->map(function($s) {
                // Hitung total poin dari seluruh log siswa
                $totalPoints = $s->ramadanLogs->sum(function($log) {
                    $dailyScore = 0;
                    
                    // 1. Poin Puasa (50 Poin)
                    if ($log->is_fasting) $dailyScore += 50;
                    
                    // 2. Poin Shalat Wajib (10 Poin per waktu -> Max 50)
                    $prayers = is_string($log->prayers) ? json_decode($log->prayers, true) : ($log->prayers ?? []);
                    if(is_array($prayers)) {
                        $dailyScore += count(array_filter($prayers)) * 10;
                    }

                    // 3. Poin Sunnah (Tarawih, Witir, dll -> 10 Poin per item)
                    $sunnahs = is_string($log->sunnah_deeds) ? json_decode($log->sunnah_deeds, true) : ($log->sunnah_deeds ?? []);
                    if(is_array($sunnahs)) {
                        $dailyScore += count(array_filter($sunnahs)) * 10;
                    }

                    // 4. Poin Tilawah (20 Poin)
                    if (!empty($log->tadarus_surah)) $dailyScore += 20;
                    
                    // 5. Poin Laporan Jumat (30 Poin)
                    if (!empty($log->friday_khotib)) $dailyScore += 30;

                    // 6. Bonus Nilai Guru
                    if ($log->teacher_score) {
                        $dailyScore += round($log->teacher_score / 5); 
                    }

                    return $dailyScore;
                });

                $s->ramadan_points = $totalPoints;
                return $s;
            })
            ->sortByDesc('ramadan_points')
            ->take(10)
            ->values();

        // --- 3. DATA PENGHUBUNG (LIAISON) ---
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

        // --- 4. DATA KEHADIRAN & ABSENSI ---
        $hadir = 0; $terlambat = 0; $sakit = 0; $izin = 0; $alpa = 0;
        $attendance_history = collect([]);
        $rawAttendanceRecords = collect([]); 
        
        if (class_exists(AttendanceSiswa::class)) {
            $attQuery = AttendanceSiswa::where('student_id', $id);
            
            $statusHadir = ['Hadir', 'Masuk', 'Terlambat', 'hadir', 'masuk', 'terlambat'];
            $statusTelat = ['Terlambat', 'terlambat'];
            $statusSakit = ['Sakit', 'sakit'];
            $statusIzin  = ['Izin', 'izin'];
            $statusAlpa  = ['Alfa', 'Alpa', 'Alpha', 'alfa', 'alpa', 'alpha', 'Tanpa Keterangan'];

            $hadir = (clone $attQuery)->whereIn('status', $statusHadir)->count();
            $terlambat = (clone $attQuery)->whereIn('status', $statusTelat)->count();
            $sakit = (clone $attQuery)->whereIn('status', $statusSakit)->count();
            $izin  = (clone $attQuery)->whereIn('status', $statusIzin)->count();
            $alpa  = (clone $attQuery)->whereIn('status', $statusAlpa)->count(); 
            
            $attendance_history = (clone $attQuery)->latest('attendance_date')->take(10)->get();

            $currentYearStart = Carbon::now()->startOfYear(); 
            $rawAttendanceRecords = (clone $attQuery)
                                    ->whereDate('attendance_date', '>=', $currentYearStart)
                                    ->orderBy('attendance_date', 'desc')
                                    ->get();
        }
        
        $attendanceChart = ['hadir' => $hadir, 'sakit' => $sakit, 'izin' => $izin, 'alpa' => $alpa];
        $total_hari_efektif = $hadir + $sakit + $izin + $alpa;
        $attendancePercentage = $total_hari_efektif > 0 ? round(($hadir / $total_hari_efektif) * 100) : 0;

        // --- 5. LOGIKA DISIPLIN & PRESTASI ---
        $violations = collect([]);
        $achievements = collect([]); 
        
        // A. Pelanggaran Manual
        $manualViolations = collect([]);
        if (class_exists(DisciplineRecord::class)) {
            try {
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

        // B. Pelanggaran Otomatis (ALPA)
        $manualAlpaDates = $manualViolations->filter(function($record) {
            $text = strtolower(($record->notes ?? '') . ' ' . optional($record->disciplineType)->name);
            return str_contains($text, 'alfa') || 
                   str_contains($text, 'alpa') || 
                   str_contains($text, 'bolos') || 
                   str_contains($text, 'tidak masuk');
        })->map(function($record) {
            return Carbon::parse($record->date)->toDateString();
        })->toArray();

        $alpaViolations = $rawAttendanceRecords
            ->filter(function ($att) use ($manualAlpaDates) {
                $isAlfa = in_array(strtolower($att->status), ['alfa', 'alpa', 'alpha']);
                $dateString = Carbon::parse($att->attendance_date)->toDateString();
                $notDuplicate = !in_array($dateString, $manualAlpaDates);
                return $isAlfa && $notDuplicate;
            })
            ->map(function ($att) {
                return (object) [
                    'date' => $att->attendance_date,
                    'notes' => 'Ketidakhadiran Tanpa Keterangan (Alpa)',
                    'point' => 10,
                    'type' => 'auto',
                    'recorder' => (object) ['name' => 'Sistem Otomatis'],
                    'disciplineType' => (object) ['name' => 'Absensi (Alpha)', 'point_value' => 10]
                ];
            });

        // C. [BARU] Pelanggaran Otomatis (TERLAMBAT)
        // Logika: Ambil status 'Terlambat', mapping ke object violation dengan poin 5
        $lateViolations = $rawAttendanceRecords
            ->filter(function ($att) {
                return in_array(strtolower($att->status), ['terlambat']);
            })
            ->map(function ($att) {
                return (object) [
                    'date' => $att->attendance_date,
                    // Gunakan notes dari DB (yang sudah kita fix di controller sebelumnya) atau default
                    'notes' => $att->notes ?? 'Terlambat Datang Sekolah',
                    'point' => 5, // Poin Konsisten dengan AttendanceSiswaController
                    'type' => 'auto_late',
                    'recorder' => (object) ['name' => 'Sistem Otomatis'],
                    'disciplineType' => (object) ['name' => 'Keterlambatan', 'point_value' => 5]
                ];
            });

        // GABUNGKAN SEMUA PELANGGARAN
        $violations = $manualViolations
                        ->concat($alpaViolations)
                        ->concat($lateViolations) // Gabungkan data terlambat
                        ->sortByDesc('date');

        // C. Prestasi
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
                            'level' => null,
                            'photo' => null,
                            'recorder' => $item->recorder ?? (object)['name' => 'Admin/Guru'],
                            'disciplineType' => $item->disciplineType ?? (object)['name' => 'Prestasi', 'point_value' => 0]
                        ];
                    });
            } catch (QueryException $e) { }
        }

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
                    'point' => 5,
                    'type' => 'auto_prayer',
                    'level' => null,
                    'photo' => null,
                    'recorder' => (object) ['name' => 'Sistem Otomatis'],
                    'disciplineType' => (object) ['name' => 'Kegiatan Keagamaan', 'point_value' => 5]
                ];
            });

        $realAchievements = collect([]);
        if (class_exists(Achievement::class)) {
            try {
                $realAchievements = Achievement::where('student_id', $id)->get()
                    ->map(function($item) {
                        return (object) [
                            'date' => $item->date,
                            'notes' => $item->description ?? $item->title,
                            'point' => 0, 
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

        // HITUNG TOTAL POIN (Violations akan otomatis menyertakan poin terlambat)
        $total_violation_points = $violations->sum(fn($v) => $v->point ?? $v->disciplineType->point_value ?? 0);
        $total_merit_points = $manualMerits->sum(fn($a) => $a->point ?? $a->disciplineType->point_value ?? 0) + $prayerAchievements->sum(fn($a) => $a->point ?? 0);
        $finalScore = 100 - $total_violation_points + $total_merit_points;

        // --- 6. JURNAL 7 KEBIASAAN ---
        $todayEntry = null; $habits = collect([]); 
        if (class_exists(StudentHabit::class)) {
            $todayEntry = StudentHabit::where('student_id', $id)
                            ->whereDate('report_date', Carbon::today()) 
                            ->first();
            $habits = StudentHabit::where('student_id', $id)
                        ->orderBy('report_date', 'desc')
                        ->get();
        }

        // --- 7. DATA LMS (DIPERBAIKI: Menggunakan 'grade') ---
        $lms_assignments_grouped = []; 
        $lms_materials_grouped = []; 
        $lms_grades = [];
        
        if ($classId) {
            if (class_exists(LmsAssignment::class)) {
                $assignments = LmsAssignment::with('subject')
                                ->where('class_id', $classId)
                                ->latest()
                                ->get();
                $lms_assignments_grouped = $assignments->groupBy(fn($i) => $i->subject->name ?? 'Umum');
                
                if (class_exists(LmsSubmission::class)) {
                    $submissions = LmsSubmission::where('student_id', $id)->get();
                    // [FIX] Gunakan kolom 'grade'
                    foreach($submissions as $sub) { $lms_grades[$sub->assignment_id] = $sub->grade; }
                }
            }
            if (class_exists(LmsMaterial::class)) {
                $materials = LmsMaterial::with('subject')
                                ->where('class_id', $classId)
                                ->latest()
                                ->get();
                $lms_materials_grouped = $materials->groupBy(fn($i) => $i->subject->name ?? 'Umum');
            }
        }
        
        // --- 8. PERPUSTAKAAN ---
        $library_visits = 0; $library_history = collect([]);
        $ebooks = collect([]); 
        $ebookHistory = collect([]); 

        // [PERBAIKAN] Menggunakan Model Borrowing yang benar
        if (class_exists(Borrowing::class)) {
             // Ambil 5 riwayat peminjaman terakhir
             $library_history = Borrowing::with('book')
                                ->where('student_id', $id)
                                ->orderBy('borrow_date', 'desc')
                                ->take(5)
                                ->get();
             
             // Hitung total semua peminjaman (bukan hanya 5 terakhir)
             $library_visits = Borrowing::where('student_id', $id)->count();
        }

        if (class_exists(Book::class)) {
            $ebooks = Book::whereNotNull('ebook_path')->latest()->get();
            if(class_exists(EbookRead::class)) {
                $ebookHistory = EbookRead::where('student_id', $id)
                                ->with('book')
                                ->latest()
                                ->get()
                                ->unique('book_id')
                                ->take(5);
            }
        }

        // --- 9. DATA AKADEMIK ---
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

        // --- 10. JURNAL KBM (ALL DATA - NO LIMIT) ---
        $teaching_journals = [];
        if (class_exists(TeachingSession::class) && $classId) {
             $teaching_journals = TeachingSession::with(['schedule.subject', 'schedule.teacher', 'attendances'])
                ->whereHas('schedule', fn($q) => $q->where('school_class_id', $classId))
                ->latest('date')
                ->latest('started_at')
                ->get();
        }

        // --- 11. PENGADUAN & BK ---
        $complaints = collect([]);
        if (class_exists(Complaint::class)) {
            $complaints = Complaint::where('student_id', $student->id)->latest()->get();
        }
        $bkSessions = collect([]);
        if (class_exists(BkSession::class)) {
            $bkSessions = BkSession::where('student_id', $student->id)
                ->with(['category', 'teacher'])
                ->latest()
                ->get();
        }

        // --- 12. TABS CONFIGURATION ---
        $tabs = ['ringkasan' => ['icon' => 'squares-four', 'label' => 'Ringkasan']];

        if ($isAlumni) {
            $tabs['prestasi'] = ['icon' => 'trophy', 'label' => 'Riwayat Prestasi'];
            $tabs['perpustakaan'] = ['icon' => 'books', 'label' => 'Riwayat Pustaka'];
        } else {
            $tabs = array_merge($tabs, [
                'kebiasaan' => ['icon' => 'sun-horizon', 'label' => '7 Kebiasaan'],
                'ramadan_jurnal' => ['icon' => 'moon-stars', 'label' => 'Jurnal Ramadhan'], 
                'ramadan_rank'   => ['icon' => 'trophy', 'label' => 'Peringkat Kebaikan'], 
                'bk' => ['icon' => 'heart-beat', 'label' => 'Konseling BK'],
                'penghubung' => ['icon' => 'notebook', 'label' => 'Buku Penghubung'],
                'pengaduan' => ['icon' => 'megaphone', 'label' => 'Lapor Masalah'],   
                'jadwal' => ['icon' => 'calendar-blank', 'label' => 'Jadwal Pelajaran'],
                'lms' => ['icon' => 'clipboard-text', 'label' => 'Tugas Online'],
                'kbm' => ['icon' => 'chalkboard-teacher', 'label' => 'Jurnal KBM'],
                'akademik' => ['icon' => 'exam', 'label' => 'Nilai Rapor'],
                'kehadiran' => ['icon' => 'calendar-check', 'label' => 'Riwayat Absen'],
                'disiplin' => ['icon' => 'warning-octagon', 'label' => 'Disiplin & Poin'], 
                'prestasi' => ['icon' => 'medal', 'label' => 'Prestasi'],   
                'keagamaan' => ['icon' => 'mosque', 'label' => 'Keagamaan'],
                'perpustakaan' => ['icon' => 'books', 'label' => 'E-Library'], 
            ]);
        }

        // Statistik Sholat
        $sholat_dhuha = 0; $sholat_dhuhur = 0;
        if (class_exists(AttendanceSiswa::class)) {
            $sholat_dhuha = AttendanceSiswa::where('student_id', $id)->where('type', 'Keagamaan')->where('activity', 'Dhuha')->count();
            $sholat_dhuhur = AttendanceSiswa::where('student_id', $id)->where('type', 'Keagamaan')->where('activity', 'Dhuhur')->count();
        }

        // --- 13. COMPACT DATA ---
        $data = compact(
            'student', 'isAlumni', 'tabs', 'attendancePercentage',
            'liaison_messages', 'complaints', 
            'todayEntry', 'habits',
            'hadir', 'terlambat', 'sakit', 'izin', 'alpa', 
            'attendance_history', 'attendanceChart',
            'lms_assignments_grouped', 'lms_grades', 
            'lms_materials_grouped', 
            'violations', 'total_violation_points', 
            'achievements', 'total_merit_points',
            'library_visits', 'library_history', 
            'ebooks', 'ebookHistory', 
            'academic_record', 'chartData', 'teaching_journals',
            'sholat_dhuha', 'sholat_dhuhur',
            'finalScore',
            'bkSessions',
            'today', 
            'todayRamadanLog', 
            'lastVerifiedLog',
            'topRamadanStudents'
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