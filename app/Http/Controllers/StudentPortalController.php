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
use App\Models\LiteracyJournal;
use App\Models\Schedule; 
use App\Models\CbtExam; 
use App\Models\CbtStudentExam; 
use App\Models\AcademicCalendar;
use Illuminate\Support\Facades\Storage;

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
        
        $student = Student::with([
            'schoolClass.schedules.subject', 
            'schoolClass.schedules.teacher', 
            'alumniProfile'
        ])->findOrFail($id);
        
        $isAlumni = $student->status === 'graduated';
        $classId = $student->class_id ?? $student->school_class_id ?? optional($student->schoolClass)->id;

        // ==========================================
        //  1. LOGIKA DASHBOARD OPERASIONAL (PRIORITY)
        // ==========================================
        
       // A. PRIORITY EXAMS (CBT)
        $priorityExams = collect([]);
        
        if (class_exists(\App\Models\CbtExam::class)) {           
            $studentLevel = $student->schoolClass->level ?? filter_var($student->schoolClass->name, FILTER_SANITIZE_NUMBER_INT) ?? null;
           
            $now = Carbon::now('Asia/Jakarta');            
            $activeExams = \App\Models\CbtExam::where('is_active', true)
                ->where(function($query) use ($now) {
                    $query->whereNull('start_time')
                          ->orWhere('start_time', '<=', $now);
                })
                ->where(function($query) use ($now) {
                    $query->whereNull('end_time')
                          ->orWhere('end_time', '>=', $now);
                })
                ->get();
            
            $examAttempts = \App\Models\CbtStudentExam::where('student_id', $student->id)
                ->whereIn('cbt_exam_id', $activeExams->pluck('id'))
                ->pluck('status', 'cbt_exam_id'); 
            
            $priorityExams = $activeExams->filter(function($exam) use ($studentLevel, $examAttempts) {                
                if(isset($exam->class_level) && $studentLevel && $exam->class_level != $studentLevel) {
                    return false; 
                }
                $status = $examAttempts[$exam->id] ?? null;
                return !$status || $status !== 'finished';
            });                        
        }

        // B. JADWAL HARI INI
        $todaysSchedule = collect([]);
        if (class_exists(\App\Models\Schedule::class) && $classId) {
            $dayName = Carbon::now()->isoFormat('dddd');
            $todaysSchedule = \App\Models\Schedule::with(['subject', 'teacher'])
                ->where('school_class_id', $classId)
                ->where('day', $dayName)
                ->orderBy('start_time')
                ->get();
        }

        // C. TUGAS PENDING (LMS)
        $pendingTasks = collect([]);
        if (class_exists(LmsAssignment::class) && $classId) {
            $pendingTasks = LmsAssignment::with('subject')
                ->where('class_id', $classId)
                ->where(function($q) {
                    $q->where('deadline', '>=', Carbon::now())
                      ->orWhere('allow_late_submission', true);
                })                
                ->whereDoesntHave('submissions', function($q) use ($id) {
                    $q->where('student_id', $id);
                })
                ->orderBy('deadline', 'asc') 
                ->take(3) 
                ->get();
        }

        // ==========================================
        //  2. DATA PENDUKUNG LAINNYA
        // ==========================================

         // --- DATA RAMADHAN ---
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $ramadanStartStr = \App\Http\Controllers\RamadanLogController::RAMADAN_START_DATE ?? config('school.ramadan_start', '2026-02-19');
        $ramadanDay = Carbon::parse($ramadanStartStr)->diffInDays(Carbon::parse($today)) + 1;
        $isRamadanEnded = $ramadanDay > 30; // Boolean selesai
        
        $todayRamadanLog = RamadanLog::where('student_id', $student->id)->whereDate('date', $today)->first();
        $lastVerifiedLog = RamadanLog::where('student_id', $student->id)->whereNotNull('teacher_verified_at')->orderBy('date', 'desc')->first();
        $topRamadanStudents = Student::with('schoolClass')->whereHas('schoolClass')->orderByDesc('ramadan_points')->take(10)->get();

        // --- DATA PENGHUBUNG (LIAISON) ---
        $liaison_messages = collect([]);
        if (class_exists(LiaisonBook::class)) { 
            try { $liaison_messages = LiaisonBook::with('teacher')->where('student_id', $student->id)->latest()->take(10)->get(); } catch (\Exception $e) {}
        }

        // --- DATA KEHADIRAN & ABSENSI --- 
        $hadir = 0; $terlambat = 0; $sakit = 0; $izin = 0; $alpa = 0;
        $attendance_history = collect([]);
        $rawAttendanceRecords = collect([]); 
        
        if (class_exists(AttendanceSiswa::class)) {
            $currentYearStart = Carbon::now()->startOfYear(); 
            $rawAttendanceRecords = AttendanceSiswa::where('student_id', $id)
                                    ->whereDate('attendance_date', '>=', $currentYearStart)
                                    ->orderBy('attendance_date', 'desc')->get();
            $attendance_history = $rawAttendanceRecords->take(10);            
          
            $allTimeAttendance = AttendanceSiswa::where('student_id', $id)->get(); 
            $hadir = $allTimeAttendance->whereInStrict('status', ['Hadir', 'Masuk', 'Terlambat', 'hadir', 'masuk', 'terlambat'])->count();
            $terlambat = $allTimeAttendance->whereInStrict('status', ['Terlambat', 'terlambat'])->count();
            $sakit = $allTimeAttendance->whereInStrict('status', ['Sakit', 'sakit'])->count();
            $izin = $allTimeAttendance->whereInStrict('status', ['Izin', 'izin'])->count();
            $alpa = $allTimeAttendance->whereInStrict('status', ['Alfa', 'Alpa', 'Alpha', 'alfa', 'alpa', 'alpha', 'Tanpa Keterangan'])->count();
        }
        
        $attendanceChart = ['hadir' => $hadir, 'sakit' => $sakit, 'izin' => $izin, 'alpa' => $alpa];
        $total_hari_efektif = $hadir + $sakit + $izin + $alpa;
        $attendancePercentage = $total_hari_efektif > 0 ? round(($hadir / $total_hari_efektif) * 100) : 0;

        // --- LOGIKA DISIPLIN & PRESTASI ---
        $violations = collect([]);
        $achievements = collect([]); 
        
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

        $manualAlpaDates = $manualViolations->filter(function($record) {
            $text = strtolower(($record->notes ?? '') . ' ' . optional($record->disciplineType)->name);
            return str_contains($text, 'alfa') || str_contains($text, 'alpa') || str_contains($text, 'bolos') || str_contains($text, 'tidak masuk');
        })->map(function($record) { return Carbon::parse($record->date)->toDateString(); })->toArray();

        $alpaViolations = $rawAttendanceRecords
            ->filter(function ($att) use ($manualAlpaDates) {
                $isAlfa = in_array(strtolower($att->status), ['alfa', 'alpa', 'alpha']);
                $dateString = Carbon::parse($att->attendance_date)->toDateString();
                return $isAlfa && !in_array($dateString, $manualAlpaDates);
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

        $lateViolations = $rawAttendanceRecords
            ->filter(function ($att) { return in_array(strtolower($att->status), ['terlambat']); })
            ->map(function ($att) {
                return (object) [
                    'date' => $att->attendance_date,
                    'notes' => $att->notes ?? 'Terlambat Datang Sekolah',
                    'point' => 5,
                    'type' => 'auto_late',
                    'recorder' => (object) ['name' => 'Sistem Otomatis'],
                    'disciplineType' => (object) ['name' => 'Keterlambatan', 'point_value' => 5]
                ];
            });

        $violations = $manualViolations->concat($alpaViolations)->concat($lateViolations)->sortByDesc('date');

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

        $total_violation_points = $violations->sum(fn($v) => $v->point ?? $v->disciplineType->point_value ?? 0);
        $total_merit_points = $manualMerits->sum(fn($a) => $a->point ?? $a->disciplineType->point_value ?? 0) + $prayerAchievements->sum(fn($a) => $a->point ?? 0);
        $finalScore = 100 - $total_violation_points + $total_merit_points;

        // --- JURNAL 7 KEBIASAAN ---
        $todayEntry = null; $habits = collect([]); 
        $totalPoints = 0;
        if (class_exists(StudentHabit::class)) {
            $todayEntry = StudentHabit::where('student_id', $id)->whereDate('report_date', Carbon::today())->first();
            $habits = StudentHabit::where('student_id', $id)->orderBy('report_date', 'desc')->get();
            $totalPoints = $habits->count() * 100; 
        }

        // --- DATA LMS (FULL) ---
        // PERBAIKAN: Gunakan collect([]) agar count() di view tidak error
        $lms_assignments_grouped = collect([]); 
        $lms_materials_grouped = collect([]); 
        $lms_grades = [];
        
        if ($classId) {
            if (class_exists(LmsAssignment::class)) {
                $assignments = LmsAssignment::with('subject')->where('class_id', $classId)->latest()->get();
                $lms_assignments_grouped = $assignments->groupBy(fn($i) => $i->subject->name ?? 'Umum');
                if (class_exists(LmsSubmission::class)) {
                    $submissions = LmsSubmission::where('student_id', $id)->get();                    
                    foreach($submissions as $sub) { $lms_grades[$sub->assignment_id] = $sub->grade; }
                }
            }
            if (class_exists(LmsMaterial::class)) {
                $materials = LmsMaterial::with('subject')->where('class_id', $classId)->latest()->get();
                $lms_materials_grouped = $materials->groupBy(fn($i) => $i->subject->name ?? 'Umum');
            }
        }
        
        // --- PERPUSTAKAAN ---
        $library_visits = 0; $library_history = collect([]);
        $ebooks = collect([]); $ebookHistory = collect([]); 
  
        if (class_exists(Borrowing::class)) {       
             $library_history = Borrowing::with('book')->where('student_id', $id)->orderBy('borrow_date', 'desc')->take(5)->get();
             $library_visits = Borrowing::where('student_id', $id)->count();
        }
        if (class_exists(Book::class)) {
            $ebooks = Book::whereNotNull('ebook_path')->latest()->get();
            if(class_exists(EbookRead::class)) {
                $ebookHistory = EbookRead::where('student_id', $id)->with('book')->latest()->get()->unique('book_id')->take(5);
            }
        }

        // --- DATA AKADEMIK ---
        $academic_record = null; $chartData = ['labels' => [], 'scores' => []];
        if (class_exists(AcademicRecord::class)) {
             $academic_record = AcademicRecord::with(['items.subject'])->where('student_id', $id)->latest()->first();
             if ($academic_record) {
                foreach($academic_record->items as $item) {
                    $chartData['labels'][] = $item->subject->name ?? 'Mapel';
                    $chartData['scores'][] = $item->score;
                }
            }
        }

        // --- JURNAL KBM ---
        // PERBAIKAN UTAMA: Harus berupa collect() bukan array biasa
        $teaching_journals = collect([]); 
        if (class_exists(TeachingSession::class) && $classId) {
             $teaching_journals = TeachingSession::with(['schedule.subject', 'schedule.teacher', 'attendances'])
                ->whereHas('schedule', fn($q) => $q->where('school_class_id', $classId))
                ->latest('date')->latest('started_at')->get();
        }

        // --- PENGADUAN & BK ---
        $complaints = collect([]);
        if (class_exists(Complaint::class)) {
            $complaints = Complaint::where('student_id', $student->id)->latest()->get();
        }
        $bkSessions = collect([]);
        if (class_exists(BkSession::class)) {
            $bkSessions = BkSession::where('student_id', $student->id)->with(['category', 'teacher'])->latest()->get();
        }

        // --- TABS CONFIGURATION ---
        $tabs = ['ringkasan' => ['icon' => 'squares-four', 'label' => 'Ringkasan']];
        if ($isAlumni) {
            $tabs['prestasi'] = ['icon' => 'trophy', 'label' => 'Riwayat Prestasi'];
            $tabs['perpustakaan'] = ['icon' => 'books', 'label' => 'Riwayat Pustaka'];
        } else {
            $tabs = array_merge($tabs, [
                'kebiasaan' => ['icon' => 'sun-horizon', 'label' => '7 Kebiasaan', 'badge' => !$todayEntry ? 1 : 0],
                'literasi_mandiri' => ['icon' => 'pencil-circle', 'label' => 'Jurnal Literasi'],
                'ramadan_jurnal' => ['icon' => 'moon-stars', 'label' => 'Mutabaah Ramadhan', 'badge' => (!$isRamadanEnded && !$todayRamadanLog) ? 1 : 0], 
                'ramadan_rank'   => ['icon' => 'trophy', 'label' => 'Peringkat Kebaikan'], 
                'bk' => ['icon' => 'heart-beat', 'label' => 'Konseling BK'],
                'penghubung' => ['icon' => 'notebook', 'label' => 'Buku Penghubung'],
                'pengaduan' => ['icon' => 'megaphone', 'label' => 'Lapor Masalah'],   
                'jadwal' => ['icon' => 'calendar-blank', 'label' => 'Jadwal Pelajaran'],
                'lms' => ['icon' => 'clipboard-text', 'label' => 'Tugas Online', 'badge' => $pendingTasks->count()],
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

         // --- DATA LITERASI MANDIRI ---
        $literacy_journals = collect([]);
        $literacy_stats = ['total_books' => 0, 'total_pages' => 0, 'points' => 0, 'level' => 'Warga Biasa', 'progress' => 0, 'next_target' => 100];

        if (class_exists(LiteracyJournal::class)) {
            $literacy_journals = LiteracyJournal::where('student_id', $id)->latest()->take(20)->get();
            $total_entries = LiteracyJournal::where('student_id', $id)->count();
            $total_pages = LiteracyJournal::where('student_id', $id)->sum('pages_read');
            $points = ($total_entries * 50) + $total_pages;
            
            $level = 'Pemula'; $next_target = 100;
            if ($points >= 2500) { $level = 'Sultan Pustaka'; $next_target = 5000; }
            elseif ($points >= 1000) { $level = 'Pujangga Muda'; $next_target = 2500; }
            elseif ($points >= 500) { $level = 'Kutu Buku'; $next_target = 1000; }
            elseif ($points >= 100) { $level = 'Gemar Baca'; $next_target = 500; }

            $progress = ($points / $next_target) * 100;
            if($progress > 100) $progress = 100;

            $literacy_stats = ['total_books' => $total_entries, 'total_pages' => $total_pages, 'points' => $points, 'level' => $level, 'progress' => round($progress), 'next_target' => $next_target];
        }

        // ==========================================
        //  3. LOGIKA KALENDER AKADEMIK (NEW INTEGRATION)
        // ==========================================
        $calendarEvents = collect([]);
        $upcomingAgendas = collect([]); 

        // A. Ambil Data Ujian (CBT) sebagai Event
        if (class_exists(\App\Models\CbtExam::class)) {     
            $nowCalendar = Carbon::now('Asia/Jakarta')->subMonths(3);
            
            $exams = \App\Models\CbtExam::where('is_active', true)
                ->where(function($query) use ($nowCalendar) {
                    $query->whereNull('start_time')
                          ->orWhere('start_time', '>=', $nowCalendar);
                })
                ->get();

            foreach ($exams as $exam) {
                $calendarEvents->push([
                    'title'     => 'Ujian: ' . $exam->title,
                    'start'     => $exam->start_time->toIso8601String(),
                    'end'       => $exam->end_time->toIso8601String(),
                    'className' => 'event-ujian', 
                    'extendedProps' => [
                        'type' => 'Ujian',
                        'desc' => 'Pelaksanaan ujian berbasis komputer.'
                    ]
                ]);
            }
        }

        // B. Ambil Data dari Tabel Kalender Pendidikan
        if (class_exists(\App\Models\AcademicCalendar::class)) {
            $academicEvents = \App\Models\AcademicCalendar::all();

            foreach ($academicEvents as $event) {
                $calendarEvents->push($event->toCalendarEvent());
            }

            $upcomingAgendas = \App\Models\AcademicCalendar::where(function($q) {
                    $q->whereDate('end_date', '>=', Carbon::today())
                      ->orWhere(function($q2) {
                          $q2->whereNull('end_date')
                             ->whereDate('start_date', '>=', Carbon::today());
                      });
                })
                ->orderBy('start_date', 'asc')
                ->take(3)
                ->get();
        }

        // ==========================================
        //  14. COMPACT DATA 
        // ==========================================
        $data = compact(
            'student', 'isAlumni', 'tabs', 'attendancePercentage',
            'liaison_messages', 'complaints', 
            'todayEntry', 'habits', 'totalPoints',
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
            'topRamadanStudents',
            'isRamadanEnded',
            'literacy_journals', 
            'literacy_stats',
            'priorityExams', 
            'todaysSchedule',
            'pendingTasks',
            'calendarEvents',
            'upcomingAgendas' 
        );

        if (view()->exists('students.portal.show')) {
            return view('students.portal.show', $data);
        }
        
        return view('portal.show', $data);
    }

    public function storeLiteracy(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:150',
            'pages' => 'required|numeric|min:1',
            'summary' => 'required|string|min:10',
            'proof' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if (Auth::guard('student')->id() != $request->student_id) {
            return back()->with('error', 'Akses tidak valid.');
        }

        try {
            $data = [
                'student_id' => $request->student_id,
                'title' => $request->title,
                'author' => $request->author,
                'pages_read' => $request->pages,
                'summary' => $request->summary,
                'verified_at' => null 
            ];

            if ($request->hasFile('proof')) {
                $path = $request->file('proof')->store('literacy_proofs', 'public');
                $data['proof_image'] = $path;
            }

            LiteracyJournal::create($data);

            return back()->with('success', 'Hebat! Jurnal literasi berhasil disimpan. Poinmu bertambah!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan jurnal. Coba lagi.');
        }
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