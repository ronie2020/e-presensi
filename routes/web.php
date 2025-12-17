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

// LMS (Learning Management System)
use App\Http\Controllers\LmsMaterialController;
use App\Http\Controllers\LmsAssignmentController;
use App\Http\Controllers\LmsGradeController; 
use App\Http\Controllers\StudentLmsController;

// CBT & Ujian
use App\Http\Controllers\CbtController;
use App\Http\Controllers\SebController;

// Portal Siswa
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\StudentExamController;

// Perpustakaan
use App\Http\Controllers\BookController;
use App\Http\Controllers\LibraryDashboardController;
use App\Http\Controllers\LibraryCirculationController;
use App\Http\Controllers\LibraryKioskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================================================================
//  1. HALAMAN PUBLIK & KIOSK (Tanpa Login)
// =========================================================================

Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::get('/guru', [LandingPageController::class, 'teachers'])->name('teachers.index');
Route::post('/guestbook', [GuestBookController::class, 'store'])->name('guestbook.store');

// Kiosk
Route::get('/kiosk', [KioskController::class, 'showKiosk'])->name('kiosk.show');
Route::post('/kiosk/process', [KioskController::class, 'processKioskScan'])->name('kiosk.process');

// Portal Informasi Siswa
Route::get('/portal', [StudentPortalController::class, 'index'])->name('portal.index');
Route::post('/portal/search', [StudentPortalController::class, 'search'])->name('portal.search');
Route::get('/portal/{student_id}', [StudentPortalController::class, 'show'])->name('portal.show');
Route::get('/portal/student/{id}/card', [StudentPortalController::class, 'printCard'])->name('portal.card');

// Pengumuman Kelulusan
Route::get('/kelulusan', [GraduationController::class, 'index'])->name('graduation.index');
Route::post('/kelulusan/cek', [GraduationController::class, 'check'])->name('graduation.check');
Route::get('/kelulusan/cetak/{id}', [GraduationController::class, 'printSkl'])->name('graduation.print');

// Library Kiosk
Route::get('/library/kiosk', [LibraryKioskController::class, 'index'])->name('library.kiosk.index');
Route::post('/library/kiosk/process', [LibraryKioskController::class, 'process'])->name('library.kiosk.process');


// =========================================================================
//  2. SISTEM SISWA (LOGIN & AREA SISWA)
// =========================================================================

// Auth Siswa
Route::middleware('guest:student')->group(function() {
    Route::get('/student/login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
    Route::post('/student/login', [StudentAuthController::class, 'login'])->name('student.login.post');
});
Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');

// Area Privat Siswa (Ujian & Belajar)
Route::middleware(['auth:student'])->group(function () {
    
    // A. LMS SISWA (Belajar & Tugas)
    Route::prefix('students/learning')->name('students.learning.')->group(function () {
        Route::get('/', [StudentLmsController::class, 'index'])->name('index');
        Route::get('/subject/{subject_id}', [StudentLmsController::class, 'showSubject'])->name('subject.show');
        Route::get('/material/{id}/download', [StudentLmsController::class, 'downloadMaterial'])->name('material.download');
        
        // Upload Tugas & Kuis
        Route::post('/assignment/{id}/submit', [StudentLmsController::class, 'submitAssignment'])->name('assignment.submit');
        Route::get('/assignment/{id}/quiz', [StudentLmsController::class, 'startQuiz'])->name('assignment.quiz');
        Route::post('/assignment/{id}/quiz', [StudentLmsController::class, 'submitQuiz'])->name('assignment.quiz.submit');
    });

    // B. UJIAN SISWA (CBT)
    Route::prefix('student/exam')->name('student.exam.')->group(function () {
        Route::get('/', [StudentExamController::class, 'index'])->name('index');
        Route::middleware(['seb'])->group(function () {
            Route::get('/{exam}/start', [StudentExamController::class, 'showStart'])->name('showStart');
            Route::post('/{exam}/start', [StudentExamController::class, 'start'])->name('start');
            Route::get('/{exam}/run', [StudentExamController::class, 'run'])->name('run');
            Route::post('/answer', [StudentExamController::class, 'saveAnswer'])->name('saveAnswer');
            Route::post('/{exam}/finish', [StudentExamController::class, 'finish'])->name('finish');
        });
    });
});

// SEB Utilities
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

    // ===> LMS GURU (Materi, Tugas, & Nilai) <===
    Route::prefix('lms')->name('lms.')->group(function () {
        // Materi
        Route::resource('materials', LmsMaterialController::class);
        
        // Tugas & Penilaian
        Route::resource('assignments', LmsAssignmentController::class);
        Route::get('/assignments/{assignment}/submissions', [LmsAssignmentController::class, 'submissions'])->name('assignments.submissions');
        Route::post('/submissions/{submission}/grade', [LmsAssignmentController::class, 'grade'])->name('submissions.grade');

        // Rekap Nilai (Gradebook)
        Route::get('/grades/recap', [LmsGradeController::class, 'index'])->name('grades.index');
        
        // ===> [PENTING] INI ADALAH ROUTE YANG HILANG <===
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
    Route::resource('users', UserController::class);
    Route::resource('subjects', SubjectController::class)->only(['index', 'store', 'update', 'destroy']);
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
    });

    // Kedisiplinan & Penilaian
    Route::get('/scan', [AttendanceSiswaController::class, 'showScanner'])->name('scan.show');
    Route::post('/scan', [AttendanceSiswaController::class, 'processScan'])->name('scan.process');
    Route::resource('discipline', DisciplineController::class)->only(['index', 'store', 'destroy']);
    Route::resource('discipline-types', DisciplineTypeController::class);
    
    // E-Rapor
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/input', [GradeController::class, 'create'])->name('grades.create');
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/list', [GradeController::class, 'listStudents'])->name('grades.list');
    Route::get('/report-card/{student_id}', [GradeController::class, 'reportCard'])->name('grades.report');

    // Perpustakaan
    Route::prefix('library')->name('library.')->group(function () {
        Route::get('/dashboard', [LibraryDashboardController::class, 'index'])->name('dashboard');
        Route::post('/books/import', [BookController::class, 'import'])->name('books.import');
        Route::post('/books/categories/store-ajax', [BookController::class, 'storeCategoryAjax'])->name('books.categories.ajax');
        Route::resource('books', BookController::class);   
        Route::get('/circulation', [LibraryCirculationController::class, 'index'])->name('circulation.index');
        Route::post('/circulation/search-student', [LibraryCirculationController::class, 'searchStudent'])->name('circulation.searchStudent');
        Route::post('/circulation/search-book', [LibraryCirculationController::class, 'searchBook'])->name('circulation.searchBook');
        Route::post('/circulation/borrow', [LibraryCirculationController::class, 'store'])->name('circulation.store');
        Route::post('/circulation/return', [LibraryCirculationController::class, 'returnBook'])->name('circulation.return');
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
    Route::get('/reports/export-daily', [ReportController::class, 'exportDaily'])->name('reports.exportDaily');
    Route::get('/reports/attendance/{attendance}/edit', [ReportController::class, 'editAttendance'])->name('reports.edit');
    Route::put('/reports/attendance/{attendance}', [ReportController::class, 'updateAttendance'])->name('reports.update');
    Route::delete('/reports/attendance/{attendance}', [ReportController::class, 'deleteAttendance'])->name('reports.delete');
    Route::get('/reports/religious', [ReportController::class, 'religiousReport'])->name('reports.religious');
    Route::delete('/reports/religious', [ReportController::class, 'destroyReligious'])->name('reports.destroyReligious');
    Route::get('/reports/export-religious', [ReportController::class, 'exportReligious'])->name('reports.exportReligious');
    Route::post('/reports/bulk-alpha', [ReportController::class, 'bulkAlpha'])->name('reports.bulkAlpha');

});

require __DIR__.'/auth.php';