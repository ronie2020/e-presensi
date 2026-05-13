<?php

use Illuminate\Support\Facades\Route;

// =========================================================================
//  IMPORT CONTROLLERS
// =========================================================================

// Utama
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LandingPageController; 
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\GuestBookController; 
use App\Http\Controllers\KioskController;
use App\Http\Controllers\AdminPpdbController;

// Akademik & Siswa
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceSiswaController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\DisciplineTypeController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\ExtracurricularController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\SchoolActivityController;
use App\Http\Controllers\TeachingController;
use App\Http\Controllers\GraduationController; 
use App\Http\Controllers\PpdbController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\StudentPermitController; 
use App\Http\Controllers\ClassReportController;
use App\Http\Controllers\PromotionController;

// LMS (Learning Management System)
use App\Http\Controllers\LmsMaterialController;
use App\Http\Controllers\LmsAssignmentController;
use App\Http\Controllers\LmsGradeController; 
use App\Http\Controllers\StudentLmsController;
use App\Http\Controllers\StudentQuizController; 

// CBT & Ujian
use App\Http\Controllers\CbtController;
use App\Http\Controllers\CbtEventController;
use App\Http\Controllers\CbtQuestionController;
use App\Http\Controllers\CbtMonitoringController; 
use App\Http\Controllers\CbtAnalysisController; 
use App\Http\Controllers\SebController;
use App\Http\Controllers\CbtBankController;


// Portal Siswa
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\StudentExamController;
use App\Http\Controllers\StudentScheduleController;
use App\Http\Controllers\StudentHabitController; 

// Perpustakaan
use App\Http\Controllers\BookController;
use App\Http\Controllers\LibraryDashboardController;
use App\Http\Controllers\LibraryCirculationController;
use App\Http\Controllers\LibraryKioskController;
use App\Http\Controllers\LibraryToolsController;

// Persuratan & Dinas
use App\Http\Controllers\LetterIncomingController;
use App\Http\Controllers\LetterOutgoingController;
use App\Http\Controllers\SptController;
use App\Http\Controllers\SppdController;

// CheckSebMode
use App\Http\Middleware\CheckSebMode;

use App\Http\Controllers\AdminAlumniController;
use App\Http\Controllers\AcademicCalendarController; 

// Buku Penghubung, Pengaduan & Kebiasaan Guru
use App\Http\Controllers\LiaisonBookController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\TeacherHabitController; 


// CONTROLLER BIMBINGAN KONSELING (BK)
use App\Http\Controllers\BkStudentController; 
use App\Http\Controllers\BkTeacherController; 
use App\Http\Controllers\RecoveryController;

// CONTROLLER RAMADAN LOG
use App\Http\Controllers\RamadanLogController;
use App\Http\Controllers\RamadanReportController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================================================================
//  1. HALAMAN PUBLIK & KIOSK (Tanpa Login)
// =========================================================================

Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::get('/kegiatan', [LandingPageController::class, 'activities'])->name('public.activities');
Route::get('/prestasi', [LandingPageController::class, 'achievements'])->name('public.achievements');
Route::get('/testimoni', [LandingPageController::class, 'testimonials'])->name('public.testimonials'); 
Route::get('/artikel', [LandingPageController::class, 'articles'])->name('articles.index'); 
Route::get('/guru', [LandingPageController::class, 'teachers'])->name('teachers.index');
Route::get('/guru/{id}', [LandingPageController::class, 'teacherDetail'])->name('teachers.show');
Route::get('/guru/{id}/cv', [LandingPageController::class, 'downloadCv'])->name('teachers.cv');
Route::post('/guestbook', [GuestBookController::class, 'store'])->name('guestbook.store');

// --- ROUTE PPDB PUBLIK ---
Route::prefix('ppdb')->name('ppdb.')->group(function () {    
    Route::get('/register', [PpdbController::class, 'create'])->name('create');
    Route::post('/store', [PpdbController::class, 'store'])->name('store');
    Route::get('/success/{code}', [PpdbController::class, 'success'])->name('success');  
    Route::get('/check', [PpdbController::class, 'index'])->name('check');
    Route::post('/check', [PpdbController::class, 'search'])->name('search');
    Route::get('/print-letter/{id}', [PpdbController::class, 'printLetter'])->name('print.letter');

    // Fitur Pendaftaran Kolektif (Guru SD)
    Route::get('/kolektif', [PpdbController::class, 'collective'])->name('collective');
    Route::post('/import', [PpdbController::class, 'importExcel'])->name('import');
    Route::get('/template', [PpdbController::class, 'downloadTemplate'])->name('download_template');
});

Route::get('/kiosk', [KioskController::class, 'showKiosk'])->name('kiosk.show');
Route::post('/kiosk/process', [KioskController::class, 'processKioskScan'])->name('kiosk.process');
Route::get('/portal', [StudentPortalController::class, 'index'])->name('portal.index');
Route::post('/portal/search', [StudentPortalController::class, 'search'])->name('portal.search');
Route::get('/portal/{student_id}', [StudentPortalController::class, 'show'])->name('portal.show');
Route::get('/portal/student/{id}/card', [StudentPortalController::class, 'printCard'])->name('portal.card');
Route::get('/kelulusan', [GraduationController::class, 'index'])->name('graduation.index');
Route::post('/kelulusan/cek', [GraduationController::class, 'check'])->name('graduation.check');
Route::get('/kelulusan/cetak/{id}', [GraduationController::class, 'printSkl'])->name('graduation.print');
Route::get('/library/kiosk', [LibraryKioskController::class, 'index'])->name('library.kiosk.index');
Route::post('/library/kiosk/process', [LibraryKioskController::class, 'process'])->name('library.kiosk.process');
Route::get('/katalog', [BookController::class, 'catalogue'])->name('library.catalogue');
Route::get('/katalog/baca/{book}', [BookController::class, 'read'])->name('library.books.read');

// =========================================================================
//  2. SISTEM SISWA (LOGIN & AREA SISWA)
// =========================================================================

// Auth Siswa
Route::middleware('guest:student')->group(function() {   
    Route::get('/seb-login', function () {
        return view('auth.seb_login');
    })->name('seb.login');
    Route::get('/student/login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
    Route::post('/student/login', [StudentAuthController::class, 'login'])->name('student.login.post');
});
Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');

// =========================================================================
//  AREA SISWA GENERAL (Tanpa SEB Restriction)
// =========================================================================
Route::middleware(['auth:student'])->group(function () {    
    
    // --- 1. ROUTE BUKU PENGHUBUNG (SISI SISWA) ---
    Route::get('/student/penghubung', [LiaisonBookController::class, 'indexStudent'])
        ->name('student.liaison.index');   

    // --- 2. ROUTE PENGADUAN / LAPOR (SISI SISWA) ---
    Route::prefix('student/pengaduan')->name('student.complaints.')->group(function() {
        Route::get('/', [ComplaintController::class, 'history'])->name('index'); 
        Route::get('/buat', [ComplaintController::class, 'create'])->name('create'); 
        Route::post('/', [ComplaintController::class, 'store'])->name('store'); 
    });
    // --- 3. Route Jadwal Pelajaran Siswa ---
    Route::get('/student/jadwal', [StudentScheduleController::class, 'index'])->name('student.schedule.index');  
    Route::get('/student/api/chat/messages', [LiaisonBookController::class, 'getStudentChatMessages'])->name('student.liaison.chat.messages');
    Route::post('/student/api/chat/send', [LiaisonBookController::class, 'sendStudentChatMessage'])->name('student.liaison.chat.send');

    // --- 4. JURNAL KEBIASAAN BAIK (7 HABITS) ---
    Route::prefix('student/kebiasaan')->name('student.habits.')->group(function() { 
        Route::get('/dashboard', [StudentHabitController::class, 'dashboard'])->name('dashboard');
        Route::get('/isi-jurnal', [StudentHabitController::class, 'index'])->name('index');
        Route::post('/simpan', [StudentHabitController::class, 'store'])->name('store');
    });

    // --- 5. JURNAL Literasi Mandiri ---
     Route::post('/portal/literacy', [StudentPortalController::class, 'storeLiteracy'])
        ->name('portal.literacy.store');

    // -- 6. ROUTE FEEDBACK & RATING BK
    Route::post('/student/portal/bk-feedback/{id}', [StudentPortalController::class, 'storeBkFeedback'])
        ->name('student.portal.bk_feedback');

     //---- 7. SISTEM RAMADHAN SISWA ---
    Route::prefix('student/ramadan')->name('student.ramadan.')->group(function() {
        Route::get('/tracker', [RamadanLogController::class, 'studentIndex'])->name('index');
        Route::post('/save', [RamadanLogController::class, 'store'])->name('save');
    });
    Route::get('/ramadan/leaderboard', [RamadanLogController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/ramadan/leaderboard-alias', [RamadanLogController::class, 'leaderboard'])->name('ramadan.leaderboard');
    
    // --- 8. LAYANAN E-COUNSELING (BK) UNTUK SISWA ---
    Route::prefix('student/bk')->name('student.bk.')->group(function() {
        Route::get('/', [BkStudentController::class, 'index'])->name('index'); 
        Route::get('/konsultasi', [BkStudentController::class, 'create'])->name('create'); 
        Route::post('/', [BkStudentController::class, 'store'])->name('store'); 
        Route::get('/{id}', [BkStudentController::class, 'show'])->name('show'); 

    // --- ROUTE CHAT BK (SISI SISWA) ---
        Route::get('/chat/{id}', [BkStudentController::class, 'getMessages'])->name('chat.get');
        Route::post('/chat/{id}', [BkStudentController::class, 'sendMessage'])->name('chat.send');
    });
    
    // --- 9. LAPOR PRESTASI MANDIRI ---
    Route::post('/student/achievements', [StudentPortalController::class, 'storeStudentAchievement'])->name('student.achievements.store');
});

// =========================================================================
//  AREA ALUMNI (SETELAH LULUS)
// =========================================================================
Route::middleware(['auth:student'])->prefix('alumni')->name('alumni.')->group(function () {
    Route::get('/dashboard', [AlumniController::class, 'index'])->name('dashboard');
    Route::get('/tracer', [AlumniController::class, 'tracer'])->name('tracer');
    Route::post('/tracer', [AlumniController::class, 'storeTracer'])->name('store_tracer');
});

// =========================================================================
//  AREA PRIVAT SISWA AKTIF (LMS & UJIAN - DENGAN SEB CHECK)
// =========================================================================
Route::middleware(['auth:student', CheckSebMode::class])->group(function () {
    
     // A. LMS SISWA (Belajar & Tugas)  
    Route::prefix('students/learning')->name('students.learning.')->group(function () {
        Route::get('/', [StudentLmsController::class, 'index'])->name('index');
        Route::get('/subject/{subject_id}', [StudentLmsController::class, 'showSubject'])->name('subject.show');
        Route::get('/material/{id}/download', [StudentLmsController::class, 'downloadMaterial'])->name('material.download');
        
        // Upload Tugas & Kuis
        Route::post('/assignment/{id}/submit', [StudentLmsController::class, 'submitAssignment'])->name('assignment.submit');
        Route::get('/assignment/{id}/quiz', [StudentLmsController::class, 'startQuiz'])->name('assignment.quiz');
        Route::post('/assignment/{id}/quiz', [StudentQuizController::class, 'submit'])->name('assignment.quiz.submit');
        
        // Route untuk memanggil halaman Learning Player Moodle-style
        Route::get('/{subjectId}/play', [\App\Http\Controllers\StudentLearningController::class, 'play'])->name('play');
       
        // Route AJAX untuk auto-save / menandai materi selesai dibaca
        Route::post('/mark-material', [\App\Http\Controllers\StudentLearningController::class, 'markMaterialComplete'])->name('mark-material');
    });

    // B. UJIAN SISWA (CBT)    
    Route::prefix('student/exam')->name('student.exam.')->group(function () {
        Route::get('/', [StudentExamController::class, 'index'])->name('index');   
        
        // Upload Foto Kamera
        Route::post('/photo', [StudentExamController::class, 'uploadPhoto'])->name('photo');

        Route::middleware(['seb'])->group(function () {
            Route::get('/{exam}/start', [StudentExamController::class, 'showStart'])->name('show');
            Route::post('/{exam}/start', [StudentExamController::class, 'start'])->name('start');
            Route::get('/{exam}/run', [StudentExamController::class, 'run'])->name('run');
            Route::post('/answer', [StudentExamController::class, 'saveAnswer'])->name('saveAnswer');
            Route::post('/{exam}/finish', [StudentExamController::class, 'finish'])->name('finish');
        });
    });
});

Route::get('/exam/{exam}/seb-landing', [SebController::class, 'landing'])->name('cbt.seb_landing');
Route::get('/exam/{id}/download-seb', [CbtController::class, 'download_seb'])->name('cbt.download_seb');

// =========================================================================
//  3. DASHBOARD ADMIN & GURU (Perlu Login)
// =========================================================================
Route::middleware('auth')->group(function () {
           
    // Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
     Route::get('/profile', function() {
        return redirect()->route('users.edit', auth()->id());
    })->name('profile.edit');
    
    //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
   // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // =========================================================================
    //  ROUTE REKAPITULASI KELAS (SUMMARY)    
    // =========================================================================
    Route::get('/reports/classes', [ReportController::class, 'indexClass'])->name('reports.class');
    
    Route::get('/reports/classes/print', [ClassReportController::class, 'print'])->name('reports.class.print');
    Route::get('/reports/classes/excel', [ClassReportController::class, 'exportExcel'])->name('reports.class.excel');
    
    Route::get('/reports/class-recap', [ClassReportController::class, 'index'])->name('reports.classReport');

    Route::get('/reports/class-detail', [ReportController::class, 'classReport'])->name('reports.class.detail');
    Route::get('/reports/class-detail/print', [ReportController::class, 'printClassReport'])->name('reports.printClassReport');

    // ===> LMS GURU (Materi, Tugas, & Nilai) <===
    Route::prefix('lms')->name('lms.')->group(function () {
        Route::get('/preview-player/{subject}/{class}', [\App\Http\Controllers\LmsMaterialController::class, 'previewPlayer'])->name('preview.player');

        Route::resource('materials', LmsMaterialController::class);
        Route::resource('assignments', LmsAssignmentController::class);
         // --- ROUTE BARU: PREVIEW MODE GURU ---
        Route::get('/preview/{subject_id}', [\App\Http\Controllers\StudentLearningController::class, 'teacherPreview'])->name('preview');      
        Route::get('/assignments/{assignment}/submissions', [LmsAssignmentController::class, 'submissions'])->name('assignments.submissions');
        Route::post('/submissions/{submission}/grade', [LmsAssignmentController::class, 'grade'])->name('submissions.grade');
        Route::delete('/submissions/{id}', [LmsAssignmentController::class, 'destroySubmission'])->name('submissions.destroy');
        
        Route::get('/grades/recap', [LmsGradeController::class, 'index'])->name('grades.index');
        Route::get('/grades/export', [LmsGradeController::class, 'exportExcel'])->name('grades.export');
        Route::get('/grades/print', [LmsGradeController::class, 'printReport'])->name('grades.print');

    });

    // Jurnal Mengajar
    Route::prefix('teaching')->name('teaching.')->group(function () {
        Route::get('/', [TeachingController::class, 'index'])->name('index');
        Route::get('/history', [TeachingController::class, 'history'])->name('history');
        Route::post('/start/{schedule_id}', [TeachingController::class, 'start'])->name('start');
        Route::post('/attendance/manual', [TeachingController::class, 'storeManual'])->name('manual');
        Route::get('/session/{id}', [TeachingController::class, 'show'])->name('show');            
        Route::get('/session/{id}/edit', [TeachingController::class, 'edit'])->name('edit');        
        Route::put('/session/{id}', [TeachingController::class, 'update'])->name('update');
        Route::post('/scan', [TeachingController::class, 'scan'])->name('scan');
        Route::post('/close/{id}', [TeachingController::class, 'close'])->name('close');
    });

    // =========================================================================
    // Manajemen Master Data (SISWA & KELAS)
    // =========================================================================
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/students/export', [StudentController::class, 'export'])->name('students.export'); 
    Route::get('/students/print-batch', [StudentController::class, 'printBatch'])->name('students.printBatch');
    Route::delete('/students/destroy-batch', [StudentController::class, 'destroyBatch'])->name('students.destroyBatch');
    Route::get('/students/{student}/card', [StudentController::class, 'card'])->name('students.card');   
    Route::resource('students', StudentController::class); 
    Route::resource('classes', SchoolClassController::class);


    // ROUTE MUTASI & KENAIKAN KELAS DI SINI ---
    Route::prefix('promotions')->name('promotions.')->group(function () {
        Route::get('/', [PromotionController::class, 'index'])->name('index');
        Route::post('/process', [PromotionController::class, 'process'])->name('process');
    });
    
    // --- MANAJEMEN USER (GURU & STAFF) ---
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');
    Route::post('users/import', [UserController::class, 'import'])->name('users.import');
    Route::resource('users', UserController::class);
    Route::resource('subjects', SubjectController::class)->only(['index', 'store', 'update', 'destroy']);
    
    // =========================================================================
    // RESOURCE ADMIN (PRESTASI & AKTIVITAS)
    // =========================================================================
    Route::get('/achievements/export', [AchievementController::class, 'export'])->name('achievements.export');
    Route::patch('/achievements/{id}/verify', [AchievementController::class, 'verify'])->name('achievements.verify'); // TAMBAHAN: Route Verifikasi Laporan Siswa
    Route::resource('achievements', AchievementController::class);
    Route::resource('school-activities', SchoolActivityController::class);

    // Jadwal
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::post('/schedules/regular', [ScheduleController::class, 'storeRegular'])->name('schedules.regular.store');
    Route::post('/schedules/special', [ScheduleController::class, 'storeSpecial'])->name('schedules.special.store');
    Route::delete('/schedules/special/{schedule}', [ScheduleController::class, 'destroySpecial'])->name('schedules.special.destroy');

   
    // =========================================================================
    //  CBT & UJIAN (ADMIN/GURU) - REFACTORED ROUTES
    // =========================================================================
    Route::prefix('cbt')->name('cbt.')->group(function () {    
        
        // --- 1. EVENT / FOLDER (Ditangani oleh CbtEventController) ---
        Route::get('/', [CbtEventController::class, 'index'])->name('index');
        Route::post('/events', [CbtEventController::class, 'store'])->name('events.store');
        Route::get('/events/{id}', [CbtEventController::class, 'show'])->name('events.show');
        Route::put('/events/{id}', [CbtEventController::class, 'update'])->name('events.update'); 

        // --- 2. UJIAN UTAMA (CRUD & Status) ---
        Route::get('/exam/create', [CbtController::class, 'create'])->name('create');
        Route::post('/exam', [CbtController::class, 'store'])->name('store');
        Route::get('/exam/{exam}/edit', [CbtController::class, 'edit'])->name('edit');
        Route::put('/exam/{exam}', [CbtController::class, 'update'])->name('update');
        Route::delete('/exam/{exam}', [CbtController::class, 'destroy'])->name('destroy');
        Route::post('/exams/{id}/toggle-status', [CbtController::class, 'toggleStatus'])->name('toggle_status');
        Route::post('/exams/{id}/clone', [CbtController::class, 'cloneExam'])->name('clone');

        // --- 3. KARTU & HASIL GLOBAL ---
        Route::get('/cards', [CbtController::class, 'cardIndex'])->name('cards.index');
        Route::get('/cards/print', [CbtController::class, 'printCards'])->name('cards.print');
        Route::get('/results', [CbtAnalysisController::class, 'results'])->name('results');

        // --- 4. REKAP & ANALISIS ---
        Route::get('/recap/{id}', [CbtAnalysisController::class, 'recap'])->name('recap');
        Route::get('/analysis/{id}', [CbtAnalysisController::class, 'analysis'])->name('analysis');
        Route::get('/analysis/{id}/print', [CbtAnalysisController::class, 'printAnalysis'])->name('analysis.print');        
        Route::get('/recap/{exam}/{student}/detail', [CbtAnalysisController::class, 'resultDetail'])->name('result.detail');
        Route::post('/recap/{exam}/retake/{student}', [CbtMonitoringController::class, 'allowRetake'])->name('student.retake'); // Menggunakan Monitoring Controller karena ini aksi proctoring       
        Route::post('/recap/{id}/sync', [CbtAnalysisController::class, 'syncToGradebook'])->name('sync_grades');
        Route::get('/export/{id}/{type}', [CbtAnalysisController::class, 'export'])->name('export');
        Route::post('/grade-essay', [CbtAnalysisController::class, 'gradeEssay'])->name('grade_essay');
        
        // --- 5. MANAJEMEN SOAL ---
        Route::get('/exam/{exam}/questions', [CbtQuestionController::class, 'manageQuestions'])->name('questions.manage');
        Route::post('/exam/{exam}/questions', [CbtQuestionController::class, 'storeQuestion'])->name('questions.store');
        Route::put('/questions/{question}/update', [CbtQuestionController::class, 'updateQuestion'])->name('questions.update');
        Route::delete('/questions/{id}', [CbtQuestionController::class, 'destroyQuestion'])->name('questions.destroy');
        Route::get('/exam/{exam}/questions/print', [CbtQuestionController::class, 'printQuestions'])->name('questions.print');
        Route::get('/exam/{exam}/export-questions', [CbtQuestionController::class, 'exportQuestions'])->name('questions.export_excel');
        Route::post('/exam/{exam}/import', [CbtQuestionController::class, 'importQuestions'])->name('questions.import');
        Route::get('/questions/template', [CbtQuestionController::class, 'downloadTemplate'])->name('questions.template');
        Route::post('/exam/{exam}/refresh-token', [CbtController::class, 'refresh_token'])->name('refresh_token');
        Route::delete('/exam/{exam}/questions/bulk-delete', [CbtQuestionController::class, 'bulkDelete'])->name('questions.bulk_delete');
        Route::put('/exam/{exam}/questions/bulk-weight', [CbtQuestionController::class, 'bulkWeight'])->name('questions.bulk_weight');

        // --- 6. PREVIEW & WORD ---
        Route::get('/exam/{exam}/preview', [CbtController::class, 'preview'])->name('preview');
        Route::get('/exam/{exam}/export-word', [CbtController::class, 'exportWord'])->name('export_word');
        
        // --- 7. MONITORING ---
        Route::get('/monitoring/{exam_id}', [CbtMonitoringController::class, 'monitoring'])->name('monitoring');
        Route::post('/reset/{exam}/{student}', [CbtMonitoringController::class, 'resetExam'])->name('reset');
        Route::get('/monitoring/{id}/data', [CbtMonitoringController::class, 'getMonitoringData'])->name('monitoring_data');
        Route::post('/monitoring/{id}/auto-token', [CbtMonitoringController::class, 'autoRotateToken'])->name('auto_token');
        Route::get('/monitoring/{exam}/{student}/photos', [CbtMonitoringController::class, 'getStudentPhotos'])->name('monitoring.photos');

        // --- 8. BANK SOAL INTEGRASI ---        
        Route::post('/exam/{exam}/pull-from-bank', [CbtBankController::class, 'importToExam'])->name('import_from_bank');
        Route::post('/exam/{exam}/export-to-bank', [CbtBankController::class, 'storeFromExam'])->name('export_to_bank');
    
        // --- 9. ADMINISTRASI (CETAK HADIR & BERITA ACARA) ---
        Route::get('/{id}/attendance', [CbtController::class, 'attendanceList'])->name('attendance');
        Route::get('/{id}/minutes', [CbtController::class, 'minutes'])->name('minutes');
    });

     // === BANK SOAL TERPUSAT (Gudang Soal) Berbasis Folder ===
    Route::prefix('bank-soal')->name('bank.')->group(function() {
        
        // 1. MANAJEMEN FOLDER
        Route::get('/', [CbtBankController::class, 'indexFolder'])->name('index'); // Dashboard Folder
        Route::post('/folder', [CbtBankController::class, 'storeFolder'])->name('folder.store');
        Route::put('/folder/{id}', [CbtBankController::class, 'updateFolder'])->name('folder.update'); 
        Route::delete('/folder/{id}', [CbtBankController::class, 'destroyFolder'])->name('folder.destroy');
        Route::get('/folder/{id}', [CbtBankController::class, 'showFolder'])->name('show'); // Isi Folder (Daftar Mapel)

        // 2. MANAJEMEN BANK SOAL (MAPEL) DI DALAM FOLDER
        Route::get('/folder/{folder_id}/create-mapel', [CbtBankController::class, 'createBank'])->name('create');
        Route::post('/store-mapel', [CbtBankController::class, 'store'])->name('store');
        Route::put('/mapel/{id}', [CbtBankController::class, 'update'])->name('update'); 
        Route::delete('/mapel/{id}', [CbtBankController::class, 'destroy'])->name('destroy');

        // 3. MANAJEMEN BUTIR SOAL (Manage, Print, Export, Import)
        Route::get('/mapel/{id}/manage', [CbtBankController::class, 'manage'])->name('manage');
        Route::post('/mapel/{id}/questions', [CbtBankController::class, 'storeQuestion'])->name('questions.store');       
        Route::put('/questions/{id}', [CbtBankController::class, 'updateQuestion'])->name('questions.update');     
        Route::delete('/questions/{id}', [CbtBankController::class, 'destroyQuestion'])->name('questions.destroy');  
        
        // --- BULK ACTION ---
        Route::delete('/mapel/{id}/questions/bulk-delete', [CbtBankController::class, 'bulkDelete'])->name('questions.bulk_delete');
        Route::put('/mapel/{id}/questions/bulk-weight', [CbtBankController::class, 'bulkWeight'])->name('questions.bulk_weight');
        
        // --- PREVIEW & EXPORT ---
        Route::get('/mapel/{id}/preview', [CbtBankController::class, 'preview'])->name('preview');
        Route::get('/mapel/{id}/export-word', [CbtBankController::class, 'exportWord'])->name('export_word');
        Route::get('/mapel/{id}/questions/print', [CbtBankController::class, 'printQuestions'])->name('questions.print');
        Route::get('/mapel/{id}/export', [CbtBankController::class, 'exportQuestions'])->name('questions.export');
        
        // --- IMPORT ---
        Route::post('/mapel/{id}/import', [CbtBankController::class, 'importQuestions'])->name('questions.import');
        Route::get('/questions/template', [CbtBankController::class, 'downloadTemplate'])->name('questions.template');
    });

    // Kedisiplinan & Penilaian
    Route::get('/scan', [AttendanceSiswaController::class, 'showScanner'])->name('scan.show');
    Route::post('/scan', [AttendanceSiswaController::class, 'processScan'])->name('scan.process');
    
    // =========================================================================
    // --- SISTEM IZIN KELUAR (GURU PIKET) ---
    // =========================================================================
    Route::prefix('permit')->name('permit.')->group(function() {
        Route::get('/', [StudentPermitController::class, 'index'])->name('index');
        Route::get('/history', [StudentPermitController::class, 'history'])->name('history');
        
        // [PERBAIKAN] Tambahkan Route untuk Analytics di sini!
        Route::get('/analytics', [StudentPermitController::class, 'analytics'])->name('analytics');
        
        //--- Cetak & Export
        Route::get('/print', [StudentPermitController::class, 'print'])->name('print');
        Route::get('/export', [StudentPermitController::class, 'export'])->name('export');        
        Route::post('/scan', [StudentPermitController::class, 'scan'])->name('scan');
        Route::post('/store', [StudentPermitController::class, 'store'])->name('store');    
    });

      // Rute Monitoring Pemulihan Poin (Amnesti & Decay)
    Route::prefix('admin/discipline/recovery')->name('recovery.')->group(function () {
        Route::get('/', [RecoveryController::class, 'index'])->name('index');
        Route::post('/store', [RecoveryController::class, 'store'])->name('store');
        Route::post('/trigger-decay', [RecoveryController::class, 'triggerAutoDecay'])->name('trigger_decay');
    });

    // Catatan Disiplin Utama
    Route::resource('discipline', DisciplineController::class)->only(['index', 'store', 'destroy']);
    Route::resource('discipline-types', DisciplineTypeController::class);
    Route::get('/discipline/analytics', [DisciplineController::class, 'analytics'])->name('discipline.analytics');
    Route::get('/discipline/sp-print/{id}', [DisciplineController::class, 'spPrint'])->name('discipline.sp_print');
    
     // E-RAPOR & PENILAIAN
        Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
        Route::get('/grades/input', [GradeController::class, 'create'])->name('grades.create');
        Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
        Route::get('/grades/template', [GradeController::class, 'downloadTemplate'])->name('grades.template');
        Route::get('/grades/template-student', [GradeController::class, 'downloadStudentTemplate'])->name('grades.template_student');
        Route::get('/grades/template-leger', [GradeController::class, 'downloadTemplateLeger'])->name('grades.template_leger');
        Route::post('/grades/import', [GradeController::class, 'importGrades'])->name('grades.import');
        Route::post('/grades/import-student', [GradeController::class, 'importStudentGrades'])->name('grades.import_student');
        Route::post('/grades/import-leger', [GradeController::class, 'importLeger'])->name('grades.import_leger');
        Route::get('/grades/students/{class_id}', [GradeController::class, 'getStudentsByClass'])->name('grades.get_students');
        Route::get('/grades/input-student', [GradeController::class, 'createByStudent'])->name('grades.create_by_student');
        Route::post('/grades/store-student', [GradeController::class, 'storeByStudent'])->name('grades.store_by_student');
        Route::get('/grades/list', [GradeController::class, 'listStudents'])->name('grades.list');
        Route::get('/grades/print-all/{class_id}', [GradeController::class, 'printAll'])->name('grades.print_all');
        Route::get('/report-card/{student_id}', [GradeController::class, 'reportCard'])->name('grades.report');

      // Perpustakaan
    Route::prefix('library')->name('library.')->group(function () {
        Route::get('/dashboard', [LibraryDashboardController::class, 'index'])->name('dashboard');
        Route::post('/books/import', [BookController::class, 'import'])->name('books.import');
        Route::post('/books/categories/store-ajax', [BookController::class, 'storeCategoryAjax'])->name('books.categories.ajax');
        
        // Check Siswa (tanpa prefix library ganda)
        Route::post('/dashboard/check-student', [LibraryDashboardController::class, 'checkStudent'])
                ->name('dashboard.checkStudent');
        Route::resource('books', BookController::class); 
        Route::get('/circulation', [LibraryCirculationController::class, 'index'])->name('circulation.index');
        Route::post('/circulation/search-student', [LibraryCirculationController::class, 'searchStudent'])->name('circulation.searchStudent');
        Route::post('/circulation/search-book', [LibraryCirculationController::class, 'searchBook'])->name('circulation.searchBook');
        Route::post('/circulation/borrow', [LibraryCirculationController::class, 'store'])->name('circulation.store');
        Route::post('/circulation/return', [LibraryCirculationController::class, 'returnBook'])->name('circulation.return');
     
          // ROUTE PEMINJAMAN MASSAL BUKU PAKET
        Route::get('/circulation/bulk', [LibraryCirculationController::class, 'bulkBorrow'])->name('circulation.bulk_borrow');
        Route::post('/circulation/bulk', [LibraryCirculationController::class, 'storeBulk'])->name('circulation.storeBulk');
     
         // ROUTE PEMINJAMAN PAKET INDIVIDU (1 Siswa -> Banyak Buku)
        Route::get('/circulation/student-bulk', [LibraryCirculationController::class, 'studentBorrow'])->name('circulation.student_borrow');
        Route::post('/circulation/student-bulk', [LibraryCirculationController::class, 'storeStudentBulk'])->name('circulation.storeStudentBulk');

        // ALAT BANTU & CETAK
        Route::controller(LibraryToolsController::class)->prefix('tools')->name('tools.')->group(function () {
            Route::get('/', 'index')->name('index');             
            Route::get('/print-card', 'printCard')->name('print-card');            
            Route::get('/print-label', 'printBookLabel')->name('print-book-label');            
            Route::get('/report', 'generateReport')->name('report');

          // ROUTE BEBAS PUSTAKA & API SISWA
            Route::get('/bebas-pustaka', 'bebasPustaka')->name('bebas_pustaka');
            Route::get('/api/check-clearance', 'checkClearanceApi')->name('checkClearanceApi');
            Route::get('/print-clearance/{id}', 'printClearance')->name('printClearance');
            Route::get('/api/students-by-class/{class_id}', 'getStudentsByClass')->name('getStudentsByClass');
        });   
        
        // API TAMBAHAN UNTUK SCANNER KERANJANG PADA PEMINJAMAN INDIVIDU
        Route::get('/tools/api/book-by-code', [LibraryCirculationController::class, 'getBookByCode'])->name('tools.bookByCode');
    });
        
        // ROUTE ADMIN LITERASI (MONITORING)      
         Route::prefix('admin/literacy')->name('admin.literacy.')->group(function() {
            Route::get('/', [App\Http\Controllers\AdminLiteracyController::class, 'index'])->name('index');
            Route::post('/{id}/verify', [App\Http\Controllers\AdminLiteracyController::class, 'verify'])->name('verify');
            Route::post('/{id}/reject', [App\Http\Controllers\AdminLiteracyController::class, 'reject'])->name('reject');
            Route::delete('/{id}', [App\Http\Controllers\AdminLiteracyController::class, 'destroy'])->name('destroy');
    });

   // Manajemen Kelulusan
    Route::prefix('admin/graduation')->name('admin.graduation.')->group(function() {
        Route::get('/', [GraduationController::class, 'adminIndex'])->name('index');
        Route::post('/store', [GraduationController::class, 'store'])->name('store'); 
        Route::post('/bulk-update', [GraduationController::class, 'bulkUpdate'])->name('bulk_update');
        Route::post('/set-date', [GraduationController::class, 'setGlobalDate'])->name('set_date');
        Route::post('/import', [GraduationController::class, 'import'])->name('import');
        Route::post('/auto-generate', [GraduationController::class, 'autoGenerate'])->name('auto_generate');
        Route::get('/template', [GraduationController::class, 'downloadTemplate'])->name('template');
        Route::post('/process-alumni', [GraduationController::class, 'processAlumni'])->name('process_alumni');
        Route::post('/settings', [GraduationController::class, 'saveSettings'])->name('save_settings');
        Route::post('/bulk-skl', [\App\Http\Controllers\GraduationController::class, 'bulkSetSklNumber'])->name('bulk_skl');
    });    
    
    // Route Admin Alumni
    Route::prefix('admin/alumni')->name('admin.alumni.')->group(function() {
        Route::get('/testimonials', [AdminAlumniController::class, 'testimonials'])->name('testimonials');
        Route::get('/export/pdf', [AdminAlumniController::class, 'exportPdf'])->name('export_pdf');
        Route::get('/import', [AdminAlumniController::class, 'import'])->name('import');
        Route::post('/import', [AdminAlumniController::class, 'processImport'])->name('import.process');
        Route::get('/template', [AdminAlumniController::class, 'downloadTemplate'])->name('template');
        Route::get('/', [AdminAlumniController::class, 'index'])->name('index'); 
        Route::get('/{id}/edit', [AdminAlumniController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminAlumniController::class, 'update'])->name('update');
        Route::get('/{id}', [AdminAlumniController::class, 'show'])->name('show');
    });

    // Ekstrakurikuler
    Route::prefix('extracurriculars')->name('extracurriculars.')->group(function () {
        Route::get('/', [ExtracurricularController::class, 'index'])->name('index');
        Route::post('/', [ExtracurricularController::class, 'store'])->name('store');
        Route::put('/{id}', [ExtracurricularController::class, 'update'])->name('update');
        Route::delete('/{id}', [ExtracurricularController::class, 'destroy'])->name('destroy');
        Route::get('/members', [ExtracurricularController::class, 'members'])->name('members');
        Route::post('/members', [ExtracurricularController::class, 'storeMember'])->name('members.store');
        Route::delete('/members/{id}', [ExtracurricularController::class, 'destroyMember'])->name('members.destroy');
        Route::get('/reports', [ExtracurricularController::class, 'reports'])->name('reports');
        Route::get('/reports/export', [ExtracurricularController::class, 'exportReports'])->name('reports.export');
    });

    // Pengumuman & Pengaturan (TERMASUK KALENDER PENDIDIKAN DI SINI)
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::post('/announcements/send', [AnnouncementController::class, 'sendNotification'])->name('announcements.send');
    Route::get('/agendas', [AnnouncementController::class, 'agendas'])->name('agendas.index'); 
    Route::post('/agendas', [AnnouncementController::class, 'storeAgenda'])->name('agendas.store');
    Route::delete('/agendas/{id}', [AnnouncementController::class, 'destroyAgenda'])->name('agendas.destroy');
    
    // --- ROUTE KALENDER PENDIDIKAN ---
    Route::prefix('admin/academic-calendar')->name('admin.academic-calendar.')->group(function() {
        Route::get('/', [AcademicCalendarController::class, 'index'])->name('index');
        Route::post('/', [AcademicCalendarController::class, 'store'])->name('store');
        Route::put('/{id}', [AcademicCalendarController::class, 'update'])->name('update');
        Route::delete('/{id}', [AcademicCalendarController::class, 'destroy'])->name('destroy');
    });

    Route::get('/settings/academic', [AcademicYearController::class, 'index'])->name('settings.academic.index');
    Route::post('/settings/academic', [AcademicYearController::class, 'store'])->name('settings.academic.store');
    Route::patch('/settings/academic/{id}/activate', [AcademicYearController::class, 'activate'])->name('settings.academic.activate');
    Route::delete('/settings/academic/{id}', [AcademicYearController::class, 'destroy'])->name('settings.academic.destroy');

    // Laporan
    Route::get('/reports/teaching-journal', [ReportController::class, 'teachingJournal'])->name('reports.teaching_journal');
    Route::get('/reports/daily/print', [ReportController::class, 'printDaily'])->name('reports.printDaily');
    Route::get('/reports/daily', [ReportController::class, 'dailyReport'])->name('reports.daily');
    Route::post('/reports/manual-entry', [ReportController::class, 'storeManualEntry'])->name('reports.storeManual');
    Route::post('/reports/process-alpha', [ReportController::class, 'processAlpha'])->name('reports.processAlpha');
    Route::delete('/reports/daily', [ReportController::class, 'destroyDaily'])->name('reports.destroyDaily');
    
    Route::get('/reports/export-daily', [ReportController::class, 'exportDaily'])->name('reports.exportDaily');
    Route::get('/reports/attendance/{attendance}/edit', [ReportController::class, 'editAttendance'])->name('reports.edit');
    Route::put('/reports/attendance/{attendance}', [ReportController::class, 'updateAttendance'])->name('reports.update');
    Route::delete('/reports/attendance/{attendance}', [ReportController::class, 'deleteAttendance'])->name('reports.delete');
    Route::get('/reports/religious/print', [ReportController::class, 'printReligious'])->name('reports.printReligious');
    Route::get('/reports/religious', [ReportController::class, 'religiousReport'])->name('reports.religious');
    Route::delete('/reports/religious', [ReportController::class, 'destroyReligious'])->name('reports.destroyReligious');
    Route::get('/reports/export-religious', [ReportController::class, 'exportReligious'])->name('reports.exportReligious');
    Route::post('/reports/bulk-alpha', [ReportController::class, 'bulkAlpha'])->name('reports.bulkAlpha');
    
    // API Helper untuk Checklist Per Kelas
    Route::get('/reports/api/students-by-class', [ReportController::class, 'getStudentsByClass'])->name('reports.getStudentsByClass');
    // Action Simpan Checklist
    Route::post('/reports/store-class', [ReportController::class, 'storeClassAttendance'])->name('reports.storeClass');

   // Route Admin PPDB
    Route::prefix('admin/ppdb')->name('admin.ppdb.')->group(function () {
        Route::get('/', [AdminPpdbController::class, 'index'])->name('index');
        Route::post('/set-schedule', [AdminPpdbController::class, 'setSchedule'])->name('set_schedule');
        Route::post('/bulk-promote', [AdminPpdbController::class, 'bulkPromote'])->name('bulk_promote');
        Route::post('/{id}/promote', [AdminPpdbController::class, 'promoteToStudent'])->name('promote');     
        Route::get('/{id}/show', [AdminPpdbController::class, 'show'])->name('show');
        Route::patch('/{id}/status', [AdminPpdbController::class, 'updateStatus'])->name('update_status');
        Route::delete('/{id}', [AdminPpdbController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/print', [AdminPpdbController::class, 'print'])->name('print');
        Route::get('/selection', [AdminPpdbController::class, 'index'])->name('selection'); 
        Route::get('/reports', [AdminPpdbController::class, 'reports'])->name('reports');
        Route::get('/reports/export-excel', [AdminPpdbController::class, 'exportExcel'])->name('export.excel');
        Route::get('/reports/print-recap', [AdminPpdbController::class, 'printRecap'])->name('print.recap');
        Route::get('/reports/print-mass-letters', [AdminPpdbController::class, 'printMassLetters'])->name('print.mass_letters');
    });

    // Persuratan
     Route::prefix('letters')->name('letters.')->group(function () {
        Route::resource('incoming', LetterIncomingController::class);
        Route::resource('outgoing', LetterOutgoingController::class); // Tambahkan baris ini
        Route::get('spt/{id}/print', [SptController::class, 'print'])->name('spt.print');
        Route::resource('spt', SptController::class);
    });
    Route::get('sppd/{id}/print', [SppdController::class, 'print'])->name('sppd.print');
    Route::resource('sppd', SppdController::class);

    // =========================================================================
    //  MANAJEMEN BUKU PENGHUBUNG & PENGADUAN (GURU/ADMIN)
    // =========================================================================
    Route::prefix('communication')->group(function() {
        
        // 1. Buku Penghubung
        Route::resource('liaison', LiaisonBookController::class);
        
        // API untuk mengambil siswa per kelas (Dipakai di Form Create Liaison)
        Route::get('/api/students-by-class/{classId}', [LiaisonBookController::class, 'getStudentsByClass'])
            ->name('liaison.get_students');
            
        // 2. Pengaduan Siswa
        Route::resource('complaints', ComplaintController::class);
        Route::post('/complaints/{id}/resolve', [ComplaintController::class, 'markAsResolved'])
            ->name('complaints.resolve');

        // API CHAT
        Route::get('/api/chat/contacts', [LiaisonBookController::class, 'getChatContacts'])->name('liaison.chat.contacts');
        Route::get('/api/chat/messages/{studentId}', [LiaisonBookController::class, 'getChatMessages'])->name('liaison.chat.messages');
        Route::post('/api/chat/send', [LiaisonBookController::class, 'sendChatMessage'])->name('liaison.chat.send');
    });

    // =========================================================================
    //  MODUL BIMBINGAN KONSELING (GURU BK)
    // =========================================================================
    Route::prefix('admin/bk')->name('admin.bk.')->group(function () {  
        Route::get('/', [BkTeacherController::class, 'index'])->name('index');

        // ---> bulk <---
        Route::post('/bulk-action', [BkTeacherController::class, 'bulkAction'])->name('bulk_action');
        
        // Detail & Approval
        Route::get('/{id}', [BkTeacherController::class, 'show'])->name('show');
        Route::put('/{id}/status', [BkTeacherController::class, 'updateStatus'])->name('update_status');
        
        // Input Hasil Konseling (Jurnal Rahasia)
        Route::post('/{id}/record', [BkTeacherController::class, 'storeRecord'])->name('store_record');

         // --- ROUTE CHAT BK (SISI GURU) ---
        Route::get('/chat/{id}', [BkTeacherController::class, 'getMessages'])->name('chat.get');
        Route::post('/chat/{id}', [BkTeacherController::class, 'sendMessage'])->name('chat.send');
    });

     // MONITORING 7 KEBIASAAN (GURU)
    Route::get('/teacher/habits', [TeacherHabitController::class, 'index'])->name('teacher.habits.index');
    Route::get('/teacher/habits/leaderboard', [TeacherHabitController::class, 'leaderboard'])->name('teacher.habits.leaderboard');
    Route::get('/teacher/habits/print', [TeacherHabitController::class, 'print'])->name('teacher.habits.print'); 
    Route::get('/teacher/habits/detail/{id}', [TeacherHabitController::class, 'show'])->name('teacher.habits.show');
    Route::post('/teacher/habits/feedback/{id}', [TeacherHabitController::class, 'feedback'])->name('teacher.habits.feedback');

  // RAMADHAN ADMIN (Rekap Guru)
   Route::prefix('admin/ramadan')->name('admin.ramadan.')->group(function() {
        Route::get('/reports', [RamadanLogController::class, 'adminReport'])->name('reports');
        Route::get('/leaderboard', [RamadanLogController::class, 'leaderboard'])->name('leaderboard'); 
        Route::post('/verify/{id}', [RamadanLogController::class, 'verifyFriday'])->name('verify');       
        Route::get('/export-pdf', [RamadanReportController::class, 'exportPdf'])->name('exportPdf');
        Route::get('/export-excel', [\App\Http\Controllers\RamadanReportController::class, 'exportExcel'])->name('exportExcel'); 
    });

      // CRUD Portofolio Guru (Hanya untuk guru yang login mengelola miliknya sendiri)
    Route::prefix('my-portfolio')->name('portfolio.')->group(function () {
        Route::get('/', [\App\Http\Controllers\TeacherPortfolioController::class, 'index'])->name('index');
        
        Route::post('/experience', [\App\Http\Controllers\TeacherPortfolioController::class, 'storeExperience'])->name('exp.store');
        Route::put('/experience/{id}', [\App\Http\Controllers\TeacherPortfolioController::class, 'updateExperience'])->name('exp.update');
        Route::delete('/experience/{id}', [\App\Http\Controllers\TeacherPortfolioController::class, 'destroyExperience'])->name('exp.destroy');
        
        Route::post('/material', [\App\Http\Controllers\TeacherPortfolioController::class, 'storeMaterial'])->name('mat.store');
        Route::put('/material/{id}', [\App\Http\Controllers\TeacherPortfolioController::class, 'updateMaterial'])->name('mat.update');
        Route::delete('/material/{id}', [\App\Http\Controllers\TeacherPortfolioController::class, 'destroyMaterial'])->name('mat.destroy');
        
        Route::post('/portfolio', [\App\Http\Controllers\TeacherPortfolioController::class, 'storePortfolio'])->name('port.store');
        Route::put('/portfolio/{id}', [\App\Http\Controllers\TeacherPortfolioController::class, 'updatePortfolio'])->name('port.update');
        Route::delete('/portfolio/{id}', [\App\Http\Controllers\TeacherPortfolioController::class, 'destroyPortfolio'])->name('port.destroy');
        
        Route::post('/article', [\App\Http\Controllers\TeacherPortfolioController::class, 'storeArticle'])->name('art.store');
        Route::put('/article/{id}', [\App\Http\Controllers\TeacherPortfolioController::class, 'updateArticle'])->name('art.update');
        Route::delete('/article/{id}', [\App\Http\Controllers\TeacherPortfolioController::class, 'destroyArticle'])->name('art.destroy');

        Route::post('/education', [\App\Http\Controllers\TeacherPortfolioController::class, 'storeEducation'])->name('edu.store');
        Route::put('/education/{id}', [\App\Http\Controllers\TeacherPortfolioController::class, 'updateEducation'])->name('edu.update');
        Route::delete('/education/{id}', [\App\Http\Controllers\TeacherPortfolioController::class, 'destroyEducation'])->name('edu.destroy');
    });    

});

require __DIR__.'/auth.php';