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

// LMS (Learning Management System)
use App\Http\Controllers\LmsMaterialController;
use App\Http\Controllers\LmsAssignmentController;
use App\Http\Controllers\LmsGradeController; 
use App\Http\Controllers\StudentLmsController;
use App\Http\Controllers\StudentQuizController; 

// CBT & Ujian
use App\Http\Controllers\CbtController;
use App\Http\Controllers\SebController;

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
use App\Http\Controllers\SptController;
use App\Http\Controllers\SppdController;

// CheckSebMode
use App\Http\Middleware\CheckSebMode;

use App\Http\Controllers\AdminAlumniController;

// Buku Penghubung, Pengaduan & Kebiasaan Guru
use App\Http\Controllers\LiaisonBookController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\TeacherHabitController; 

// CONTROLLER BIMBINGAN KONSELING (BK)
use App\Http\Controllers\BkStudentController; 
use App\Http\Controllers\BkTeacherController; 

// CONTROLLER RAMADAN LOG
use App\Http\Controllers\RamadanLogController;

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
Route::get('/guru', [LandingPageController::class, 'teachers'])->name('teachers.index');
Route::post('/guestbook', [GuestBookController::class, 'store'])->name('guestbook.store');

// --- ROUTE PPDB PUBLIK ---
Route::prefix('ppdb')->name('ppdb.')->group(function () {
    // Ubah 'register' jadi 'create' agar konsisten dengan view collective
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

     //---- 5. SISTEM RAMADHAN SISWA ---
    Route::prefix('student/ramadan')->name('student.ramadan.')->group(function() {
        Route::get('/tracker', [RamadanLogController::class, 'studentIndex'])->name('index');
        Route::post('/save', [RamadanLogController::class, 'store'])->name('save');
    });
    Route::get('/ramadan/leaderboard', [RamadanLogController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/ramadan/leaderboard-alias', [RamadanLogController::class, 'leaderboard'])->name('ramadan.leaderboard');
    });

    // --- 6. LAYANAN E-COUNSELING (BK) UNTUK SISWA ---
    Route::prefix('student/bk')->name('student.bk.')->group(function() {
        Route::get('/', [BkStudentController::class, 'index'])->name('index'); 
        Route::get('/konsultasi', [BkStudentController::class, 'create'])->name('create'); 
        Route::post('/', [BkStudentController::class, 'store'])->name('store'); 
        Route::get('/{id}', [BkStudentController::class, 'show'])->name('show'); 
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
    });

    // B. UJIAN SISWA (CBT)    
    Route::prefix('student/exam')->name('student.exam.')->group(function () {
        Route::get('/', [StudentExamController::class, 'index'])->name('index');   
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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/reports/classes', [ClassReportController::class, 'index'])->name('reports.class');
    Route::get('/reports/classes/print', [ClassReportController::class, 'print'])->name('reports.class.print');
    Route::get('/reports/classes/excel', [ClassReportController::class, 'exportExcel'])->name('reports.class.excel');

    // ===> LMS GURU (Materi, Tugas, & Nilai) <===
    Route::prefix('lms')->name('lms.')->group(function () {
        Route::resource('materials', LmsMaterialController::class);
        Route::resource('assignments', LmsAssignmentController::class);
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
        Route::put('/session/{id}', [TeachingController::class, 'update'])->name('update');
        Route::post('/scan', [TeachingController::class, 'scan'])->name('scan');
        Route::post('/close/{id}', [TeachingController::class, 'close'])->name('close');
    });

    // Manajemen Master Data
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/students/export', [StudentController::class, 'export'])->name('students.export'); 
    Route::get('/students/{student}/card', [StudentController::class, 'card'])->name('students.card');
    Route::resource('students', StudentController::class); 
    Route::resource('classes', SchoolClassController::class);
    
    // --- MANAJEMEN USER (GURU & STAFF) ---
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');
    Route::post('users/import', [UserController::class, 'import'])->name('users.import');
    Route::resource('users', UserController::class);
    Route::resource('subjects', SubjectController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/achievements/export', [AchievementController::class, 'export'])->name('achievements.export');
    
    // Resource Admin 
    Route::resource('achievements', AchievementController::class);
    Route::resource('school-activities', SchoolActivityController::class);

    // Jadwal
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::post('/schedules/regular', [ScheduleController::class, 'storeRegular'])->name('schedules.regular.store');
    Route::post('/schedules/special', [ScheduleController::class, 'storeSpecial'])->name('schedules.special.store');
    Route::delete('/schedules/special/{schedule}', [ScheduleController::class, 'destroySpecial'])->name('schedules.special.destroy');

    // CBT & Ujian
    Route::prefix('cbt')->name('cbt.')->group(function () {
        Route::resource('/', CbtController::class)->parameters(['' => 'exam']);
        
        // REKAP NILAI & EXPORT
        Route::get('/recap/{id}', [CbtController::class, 'recap'])->name('recap');
        Route::get('/export/{id}/{type}', [CbtController::class, 'export'])->name('export');
        Route::get('/exam/{exam}/questions', [CbtController::class, 'manageQuestions'])->name('questions.manage');
        Route::post('/exam/{exam}/questions', [CbtController::class, 'storeQuestion'])->name('questions.store');
        Route::put('/questions/{question}/update', [CbtController::class, 'updateQuestion'])->name('questions.update');
        Route::post('/exam/{exam}/refresh-token', [CbtController::class, 'refreshToken'])->name('refresh_token');
        Route::delete('/questions/{id}', [CbtController::class, 'destroyQuestion'])->name('questions.destroy');
        Route::post('/exam/{exam}/import', [CbtController::class, 'importQuestions'])->name('questions.import');
        Route::get('/questions/template', [CbtController::class, 'downloadTemplate'])->name('questions.template');
        Route::get('/monitoring/{exam_id}', [CbtController::class, 'monitoring'])->name('monitoring');
        Route::post('/reset/{exam}/{student}', [CbtController::class, 'resetExam'])->name('reset');
        Route::get('/results', [CbtController::class, 'results'])->name('results');

        // Route Auto Rotate Token (AJAX)
        Route::post('/exam/{exam}/auto-token', [CbtController::class, 'autoRotateToken'])->name('auto_token');
    });

    // Kedisiplinan & Penilaian
    Route::get('/scan', [AttendanceSiswaController::class, 'showScanner'])->name('scan.show');
    Route::post('/scan', [AttendanceSiswaController::class, 'processScan'])->name('scan.process');
    
    
    // --- SISTEM IZIN KELUAR (GURU PIKET) ---
    Route::prefix('permit')->name('permit.')->group(function() {
        Route::get('/', [StudentPermitController::class, 'index'])->name('index');
        Route::get('/history', [StudentPermitController::class, 'history'])->name('history');
        
        //--- Cetak & Export
        Route::get('/print', [StudentPermitController::class, 'print'])->name('print');
        Route::get('/export', [StudentPermitController::class, 'export'])->name('export');
        
        Route::post('/scan', [StudentPermitController::class, 'scan'])->name('scan');
        Route::post('/store', [StudentPermitController::class, 'store'])->name('store');    
    });

    Route::resource('discipline', DisciplineController::class)->only(['index', 'store', 'destroy']);
    Route::resource('discipline-types', DisciplineTypeController::class);
    
    // E-RAPOR & PENILAIAN
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/input', [GradeController::class, 'create'])->name('grades.create');
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/template', [GradeController::class, 'downloadTemplate'])->name('grades.template');
    Route::get('/grades/template-student', [GradeController::class, 'downloadStudentTemplate'])->name('grades.template_student');
    Route::post('/grades/import', [GradeController::class, 'importGrades'])->name('grades.import');
    Route::post('/grades/import-student', [GradeController::class, 'importStudentGrades'])->name('grades.import_student');
    Route::get('/grades/students/{class_id}', [GradeController::class, 'getStudentsByClass'])->name('grades.get_students');
    Route::get('/grades/input-student', [GradeController::class, 'createByStudent'])->name('grades.create_by_student');
    Route::post('/grades/store-student', [GradeController::class, 'storeByStudent'])->name('grades.store_by_student');
    Route::get('/grades/list', [GradeController::class, 'listStudents'])->name('grades.list');
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
     
        // ALAT BANTU & CETAK
        Route::controller(LibraryToolsController::class)->prefix('tools')->name('tools.')->group(function () {
            Route::get('/', 'index')->name('index');             
            Route::get('/print-card', 'printCard')->name('print-card');            
            Route::get('/print-label', 'printBookLabel')->name('print-book-label');            
            Route::get('/report', 'generateReport')->name('report');
        });    
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

    // Pengumuman & Pengaturan
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::post('/announcements/send', [AnnouncementController::class, 'sendNotification'])->name('announcements.send');
    Route::get('/agendas', [AnnouncementController::class, 'agendas'])->name('agendas.index'); 
    Route::post('/agendas', [AnnouncementController::class, 'storeAgenda'])->name('agendas.store');
    Route::delete('/agendas/{id}', [AnnouncementController::class, 'destroyAgenda'])->name('agendas.destroy');
    Route::resource('activities', SchoolActivityController::class)->except(['show']);
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
    Route::get('/reports/class-recap', [ReportController::class, 'classReport'])->name('reports.classReport');
    Route::get('/reports/class-recap/print', [ReportController::class, 'printClassReport'])->name('reports.printClassReport');
    Route::get('/reports/export-daily', [ReportController::class, 'exportDaily'])->name('reports.exportDaily');
    Route::get('/reports/attendance/{attendance}/edit', [ReportController::class, 'editAttendance'])->name('reports.edit');
    Route::put('/reports/attendance/{attendance}', [ReportController::class, 'updateAttendance'])->name('reports.update');
    Route::delete('/reports/attendance/{attendance}', [ReportController::class, 'deleteAttendance'])->name('reports.delete');
    Route::get('/reports/religious/print', [ReportController::class, 'printReligious'])->name('reports.printReligious');
    Route::get('/reports/religious', [ReportController::class, 'religiousReport'])->name('reports.religious');
    Route::delete('/reports/religious', [ReportController::class, 'destroyReligious'])->name('reports.destroyReligious');
    Route::get('/reports/export-religious', [ReportController::class, 'exportReligious'])->name('reports.exportReligious');
    Route::post('/reports/bulk-alpha', [ReportController::class, 'bulkAlpha'])->name('reports.bulkAlpha');

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
        
        // Detail & Approval
        Route::get('/{id}', [BkTeacherController::class, 'show'])->name('show');
        Route::put('/{id}/status', [BkTeacherController::class, 'updateStatus'])->name('update_status');
        
        // Input Hasil Konseling (Jurnal Rahasia)
        Route::post('/{id}/record', [BkTeacherController::class, 'storeRecord'])->name('store_record');
    });

    // MONITORING 7 KEBIASAAN (GURU)
    Route::get('/teacher/habits', [TeacherHabitController::class, 'index'])->name('teacher.habits.index');
    Route::get('/teacher/habits/print', [TeacherHabitController::class, 'print'])->name('teacher.habits.print'); 
    Route::get('/teacher/habits/detail/{id}', [TeacherHabitController::class, 'show'])->name('teacher.habits.show');
    Route::post('/teacher/habits/feedback/{id}', [TeacherHabitController::class, 'feedback'])->name('teacher.habits.feedback');

  // RAMADHAN ADMIN (Rekap Guru)
    Route::prefix('admin/ramadan')->name('admin.ramadan.')->group(function() {
        Route::get('/reports', [RamadanLogController::class, 'adminReport'])->name('reports');
        Route::post('/verify/{id}', [RamadanLogController::class, 'verifyFriday'])->name('verify');
    });
});

require __DIR__.'/auth.php';