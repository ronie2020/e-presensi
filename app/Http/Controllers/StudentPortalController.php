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
use App\Models\GradeRecord;  
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
use App\Models\StudentPointHistory;
use Illuminate\Support\Facades\Storage;

// --- IMPORT SERVICES ---
use App\Services\AttendanceAnalyticService;
use App\Services\DisciplineService;

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

        // Redirect ke Alumni jika status graduated
        if ($student->status === 'graduated') {
            return redirect()->route('alumni.dashboard')
                             ->with('success', 'Selamat datang di Dashboard Alumni.');
        }
        
        return redirect()->route('portal.show', $student->id)
                         ->with('success', 'Berhasil masuk ke Portal Informasi Siswa.');
    }

    // =======================================================================
    // FUNGSI SHOW YANG SUDAH DIREFACTOR MENGGUNAKAN SERVICE PATTERN
    // =======================================================================
    public function show($id, AttendanceAnalyticService $attendanceService, DisciplineService $disciplineService)
    {
        // 1. VALIDASI AKSES
        if (!Auth::guard('student')->check() || Auth::guard('student')->id() != $id) {
             return redirect()->route('portal.index')->with('error', 'Akses ditolak.');
        }

        Carbon::setLocale('id');
        $student = Student::with([
            'schoolClass.schedules.subject', 
            'schoolClass.schedules.teacher', 
            'alumniProfile', 
            'pointHistories'
        ])->findOrFail($id);

        if ($student->status === 'graduated') return redirect()->route('alumni.dashboard');
        
        if (method_exists($student, 'checkBkThresholds')) {
            $student->checkBkThresholds();
        }
        
        $isAlumni = $student->status === 'graduated';
        $classId = $student->class_id ?? $student->school_class_id ?? optional($student->schoolClass)->id;

        // ==========================================
        //  EKSEKUSI REFACTOR SERVICES
        // ==========================================
        // 1. Eksekusi Service Kehadiran & Keagamaan
        $academicYearStart = $attendanceService->getAcademicYearStart();
        $attStats = $attendanceService->getCurrentYearStats($id, $academicYearStart);
        $attArchive = $attendanceService->getPastYearsArchive($id, $academicYearStart);

        // 2. Eksekusi Service Disiplin & Prestasi
        $discProfile = $disciplineService->getDisciplineProfile($id, $academicYearStart, $attStats['raw_records']);


        // ==========================================
        //  LOGIKA DASHBOARD OPERASIONAL (PRIORITY)
        // ==========================================
        
        // A. PRIORITY EXAMS (CBT)
        $priorityExams = collect([]);
        if (class_exists(\App\Models\CbtExam::class)) {  
            $studentLevel = $student->schoolClass?->level ?? filter_var($student->schoolClass?->name, FILTER_SANITIZE_NUMBER_INT) ?? null;
            $now = Carbon::now('Asia/Jakarta');            
            
            $activeExams = \App\Models\CbtExam::where('is_active', true)
                ->where(function($query) use ($now) {
                    $query->whereNull('start_time')->orWhere('start_time', '<=', $now);
                })
                ->where(function($query) use ($now) {
                    $query->whereNull('end_time')->orWhere('end_time', '>=', $now);
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
            $dayName = Carbon::now('Asia/Jakarta')->isoFormat('dddd');
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
                    $q->where('deadline', '>=', Carbon::now('Asia/Jakarta'))
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
        //  DATA PENDUKUNG LAINNYA
        // ==========================================

        // --- DATA RAMADHAN ---
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $ramadanStartStr = \App\Http\Controllers\RamadanLogController::RAMADAN_START_DATE ?? config('school.ramadan_start', '2026-02-19');
        $ramadanDay = Carbon::parse($ramadanStartStr)->diffInDays(Carbon::parse($today)) + 1;
        $isRamadanEnded = $ramadanDay > 30; 
        
        $todayRamadanLog = RamadanLog::where('student_id', $student->id)->whereDate('date', $today)->first();
        $lastVerifiedLog = RamadanLog::where('student_id', $student->id)->whereNotNull('teacher_verified_at')->orderBy('date', 'desc')->first();
        $topRamadanStudents = Student::with('schoolClass')->whereHas('schoolClass')->orderByDesc('ramadan_points')->take(10)->get();

        // --- DATA PENGHUBUNG (LIAISON) ---
        $liaison_messages = collect([]);
        if (class_exists(LiaisonBook::class)) { 
            try { $liaison_messages = LiaisonBook::with('teacher')->where('student_id', $student->id)->latest()->take(10)->get(); } catch (\Exception $e) {}
        }

        // --- JURNAL 7 KEBIASAAN ---
        $todayEntry = null; $habits = collect([]); 
        $totalPoints = 0;
        if (class_exists(StudentHabit::class)) {
            $todayEntry = StudentHabit::where('student_id', $id)->whereDate('report_date', Carbon::today('Asia/Jakarta'))->first();
            $habits = StudentHabit::where('student_id', $id)->whereDate('report_date', '>=', $academicYearStart)->orderBy('report_date', 'desc')->get();
            $totalPoints = $habits->count() * 100; 
        }

        // --- DATA LMS (FULL) ---        
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
        if (class_exists(GradeRecord::class)) {
             $academic_record = GradeRecord::with(['items.subject'])->where('student_id', $id)->latest()->first();
             if ($academic_record) {
                foreach($academic_record->items as $item) {
                    $chartData['labels'][] = $item->subject->name ?? 'Mapel';
                    $chartData['scores'][] = $item->score;
                }
            }
        }

        // --- JURNAL KBM ---       
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
        $unreadSystemBk = 0;
        if (class_exists(BkSession::class)) {
            $bkSessions = BkSession::where('student_id', $student->id)->with(['category', 'teacher'])->latest()->get();
            $unreadSystemBk = $bkSessions->where('is_system_generated', true)->whereIn('status', ['pending', 'ongoing'])->count();
        }

        // --- DATA LITERASI MANDIRI ---
        $literacy_journals = collect([]);
        $literacy_stats = ['total_books' => 0, 'total_pages' => 0, 'points' => 0, 'level' => 'Warga Biasa', 'progress' => 0, 'next_target' => 100];

        if (class_exists(LiteracyJournal::class)) {
            $literacy_journals = LiteracyJournal::where('student_id', $id)->whereDate('created_at', '>=', $academicYearStart)->latest()->take(20)->get();
            $total_entries = LiteracyJournal::where('student_id', $id)->whereDate('created_at', '>=', $academicYearStart)->count();
            $total_pages = LiteracyJournal::where('student_id', $id)->whereDate('created_at', '>=', $academicYearStart)->sum('pages_read');
            
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
        //  LOGIKA KALENDER AKADEMIK
        // ==========================================
        $calendarEvents = collect([]);
        $upcomingAgendas = collect([]); 

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

        if (class_exists(\App\Models\AcademicCalendar::class)) {
            $academicEvents = \App\Models\AcademicCalendar::all();

            foreach ($academicEvents as $event) {
                $calendarEvents->push($event->toCalendarEvent());
            }

            $upcomingAgendas = \App\Models\AcademicCalendar::where(function($q) {
                    $q->whereDate('end_date', '>=', Carbon::today('Asia/Jakarta'))
                      ->orWhere(function($q2) {
                          $q2->whereNull('end_date')
                             ->whereDate('start_date', '>=', Carbon::today('Asia/Jakarta'));
                      });
                })
                ->orderBy('start_date', 'asc')
                ->take(3)
                ->get();
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
                'bk' => ['icon' => 'heart-beat', 'label' => 'Konseling BK', 'badge' => $unreadSystemBk > 0 ? $unreadSystemBk : 0],
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

        // ==========================================
        //  COMPACT DATA 
        // ==========================================
        $data = [
            'student' => $student, 'isAlumni' => $isAlumni, 'tabs' => $tabs, 'today' => $today,
            
            // Variabel Hasil Ekstrak Service Kehadiran
            'hadir' => $attStats['hadir'], 
            'terlambat' => $attStats['terlambat'], 
            'sakit' => $attStats['sakit'], 
            'izin' => $attStats['izin'], 
            'alpa' => $attStats['alpa'],
            'attendance_history' => $attStats['attendance_history'], 
            'attendanceChart' => $attStats['chart_data'], 
            'attendancePercentage' => $attStats['percentage'],
            'sholat_dhuha' => $attStats['sholat_dhuha'], 
            'sholat_dhuhur' => $attStats['sholat_dhuhur'],
            'attendanceHistoryYears' => $attArchive['attendance'], 
            'religionHistoryYears' => $attArchive['religion'],

            // Variabel Hasil Ekstrak Service Disiplin
            'violations' => $discProfile['violations'], 
            'achievements' => $discProfile['achievements'],
            'total_violation_points' => $discProfile['total_violation_points'], 
            'total_merit_points' => $discProfile['total_merit_points'],
            'finalScore' => $discProfile['finalScore'], 
            'priorityAlerts' => $discProfile['alerts'], 
            'amnestyTasks' => $discProfile['amnestyTasks'],

            // Variabel Original Lainnya yang Belum di Service-kan
            'liaison_messages' => $liaison_messages, 
            'complaints' => $complaints, 
            'todayEntry' => $todayEntry, 
            'habits' => $habits, 
            'totalPoints' => $totalPoints,
            'lms_assignments_grouped' => $lms_assignments_grouped, 
            'lms_grades' => $lms_grades, 
            'lms_materials_grouped' => $lms_materials_grouped, 
            'library_visits' => $library_visits, 
            'library_history' => $library_history, 
            'ebooks' => $ebooks, 
            'ebookHistory' => $ebookHistory, 
            'academic_record' => $academic_record, 
            'chartData' => $chartData, 
            'teaching_journals' => $teaching_journals,
            'bkSessions' => $bkSessions,
            'todayRamadanLog' => $todayRamadanLog, 
            'lastVerifiedLog' => $lastVerifiedLog,
            'topRamadanStudents' => $topRamadanStudents,
            'isRamadanEnded' => $isRamadanEnded,
            'literacy_journals' => $literacy_journals, 
            'literacy_stats' => $literacy_stats,
            'priorityExams' => $priorityExams, 
            'todaysSchedule' => $todaysSchedule,
            'pendingTasks' => $pendingTasks,
            'calendarEvents' => $calendarEvents,
            'upcomingAgendas' => $upcomingAgendas
        ];

        if (view()->exists('students.portal.show')) {
            return view('students.portal.show', $data);
        }
        
        return view('portal.show', $data);
    }

    /**
     * Menyimpan Rating & Feedback Sesi BK dari Siswa.
     */
    public function storeBkFeedback(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:500',
        ]);

        $session = BkSession::where('student_id', Auth::guard('student')->id())->findOrFail($id);

        if ($session->status !== 'finished') {
            return back()->with('error', 'Penilaian hanya dapat diberikan untuk sesi yang telah Selesai.');
        }

        if ($session->rating) {
            return back()->with('error', 'Kamu sudah memberikan penilaian untuk sesi ini.');
        }

        $session->update([
            'rating' => $request->rating,
            'student_feedback' => $request->feedback,
            'feedback_at' => now(),
        ]);

        return back()->with('success', 'Terima kasih! Ulasanmu membantu kami meningkatkan kualitas layanan bimbingan sekolah.');
    }
    
    /**
     * METHOD UNTUK MENYIMPAN JURNAL LITERASI
     */
    public function storeLiteracy(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:150',
            'pages' => 'required|numeric|min:1',
            'rating' => 'required|integer|min:1|max:5',
            'favorite_character' => 'nullable|string|max:255',
            'new_vocabulary' => 'nullable|string|max:255',
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
                'rating' => $request->rating,
                'favorite_character' => $request->favorite_character,
                'new_vocabulary' => $request->new_vocabulary,
                'summary' => $request->summary,
                'status' => 'pending', 
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

    /**
     * FUNGSI LAPOR PRESTASI MANDIRI
     */
    public function storeStudentAchievement(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'required|string|max:255',
            'level' => 'required|string',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Maks 5MB
            'certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120', // Maks 5MB
        ]);

        if (Auth::guard('student')->id() != $request->student_id) {
            return back()->with('error', 'Akses tidak diizinkan.');
        }

        try {
            $data = [
                'type' => 'Siswa',
                'student_id' => $request->student_id,
                'title' => $request->title,
                'level' => $request->level,
                'date' => $request->date,
                'description' => $request->description,
                'status' => 'pending',
            ];

            // Upload Foto Dokumentasi
            if ($request->hasFile('photo')) {
                $data['photo_path'] = $request->file('photo')->store('achievements', 'public');
            }

            // Upload File Sertifikat
            if ($request->hasFile('certificate')) {
                $data['certificate_path'] = $request->file('certificate')->store('achievement_certificates', 'public');
            }

            Achievement::create($data);

            return back()->with('success', 'Prestasi berhasil dilaporkan! Data telah disimpan dan sedang menunggu verifikasi admin.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melaporkan prestasi. Silakan coba beberapa saat lagi.');
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

   public function biodata($id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $settings = []; 

        // 1. AMBIL DATA DARI TABEL ABSENSI HARIAN (AttendanceSiswa)
        // Kita hitung total status absensi khusus untuk tipe 'Harian' atau 'Masuk'
        $sakit = \App\Models\AttendanceSiswa::where('student_id', $id)
                    ->where('status', 'Sakit')
                    ->whereIn('type', ['Harian', 'Masuk'])
                    ->count();

        $izin = \App\Models\AttendanceSiswa::where('student_id', $id)
                    ->where('status', 'Izin')
                    ->whereIn('type', ['Harian', 'Masuk'])
                    ->count();

        $alfa = \App\Models\AttendanceSiswa::where('student_id', $id)
                    ->whereIn('status', ['Alfa', 'Alpa', 'Alpha']) // Antisipasi beda penulisan
                    ->whereIn('type', ['Harian', 'Masuk'])
                    ->count();

        $attendanceStats = [
            'sakit' => $sakit,
            'izin' => $izin,
            'alfa' => $alfa
        ];

        // 2. Kirim data attendanceStats ke view
        return view('students.portal.biodata', compact('student', 'settings', 'attendanceStats'));
    }
}