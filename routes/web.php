<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceSiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\LandingPageController; 
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DisciplineTypeController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GuestBookController; 
use App\Http\Controllers\ExtracurricularController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\StudentExamController;
use App\Http\Controllers\SebController; 
// [PENTING] Import CbtController agar bisa dipanggil di route download
use App\Http\Controllers\CbtController; 
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- UTAMA: RUTE LANDING PAGE ---
Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::get('/pengajar', [LandingPageController::class, 'teachers'])->name('teachers.index');
Route::post('/guestbook', [GuestBookController::class, 'store'])->name('guestbook.store');
Route::get('/kiosk', [KioskController::class, 'showKiosk'])->name('kiosk.show');
Route::post('/kiosk/process', [KioskController::class, 'processKioskScan'])->name('kiosk.process');
Route::get('/portal', [StudentPortalController::class, 'index'])->name('portal.index');
Route::post('/portal/search', [StudentPortalController::class, 'search'])->name('portal.search');
Route::get('/portal/{student_id}', [StudentPortalController::class, 'show'])->name('portal.show');
Route::get('/library/kiosk', [\App\Http\Controllers\LibraryKioskController::class, 'index'])->name('library.kiosk.index');
Route::post('/library/kiosk/process', [\App\Http\Controllers\LibraryKioskController::class, 'process'])->name('library.kiosk.process');


// =========================================================================
//  SISTEM LOGIN & UJIAN KHUSUS SISWA
// =========================================================================

// 1. Route Login Siswa
Route::middleware('guest:student')->group(function() {
    Route::get('/student/login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
    Route::post('/student/login', [StudentAuthController::class, 'login'])->name('student.login.post');
});

// 2. Route Logout Siswa
Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');

// 3. Route Publik SEB (Landing Page & Download)
// Route untuk menampilkan halaman landing pilihan device (Laptop/HP)
Route::get('/exam/{exam}/seb-landing', [SebController::class, 'landing'])->name('cbt.seb_landing');

// [PERBAIKAN UTAMA] Route untuk download file config .seb
// Mengarah ke CbtController@download_seb dengan parameter {id}
Route::get('/exam/{id}/download-seb', [CbtController::class, 'download_seb'])->name('cbt.download_seb');


// 4. GROUP ROUTE SISWA
Route::middleware(['auth:student'])->prefix('student/exam')->name('student.exam.')->group(function () {
    
    // A. Halaman Daftar Ujian (BISA DIBUKA DI CHROME BIASA)    
    Route::get('/', [StudentExamController::class, 'index'])->name('index');

    // B. Halaman Pengerjaan Ujian (WAJIB PAKAI SEB)    
    Route::middleware(['seb'])->group(function () {

        // Konfirmasi (Start)
        Route::get('/{exam}/start', [StudentExamController::class, 'showStart'])->name('showStart');
        Route::post('/{exam}/start', [StudentExamController::class, 'start'])->name('start');
        
        // Mengerjakan Soal
        Route::get('/{exam}/run', [StudentExamController::class, 'run'])->name('run');
        Route::post('/answer', [StudentExamController::class, 'saveAnswer'])->name('saveAnswer');
        Route::post('/{exam}/finish', [StudentExamController::class, 'finish'])->name('finish');
    });

});
// =========================================================================


// --- GRUP RUTE GURU/ADMIN ---
Route::middleware('auth')->group(function () {
           
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/students/export', [StudentController::class, 'export'])->name('students.export'); 
    Route::resource('students', StudentController::class); 
    Route::resource('classes', SchoolClassController::class);
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    
     // === MANAJEMEN JADWAL (ADMIN) ===
    Route::get('/schedules', [\App\Http\Controllers\ScheduleController::class, 'index'])->name('schedules.index');
    
    // Simpan Jadwal Pelajaran (Baru)
    Route::post('/schedules', [\App\Http\Controllers\ScheduleController::class, 'store'])->name('schedules.store');
    
    // Hapus Jadwal
    Route::delete('/schedules/{id}', [\App\Http\Controllers\ScheduleController::class, 'destroy'])->name('schedules.destroy');

    Route::post('/schedules/regular', [ScheduleController::class, 'storeRegular'])->name('schedules.regular.store');
    Route::post('/schedules/special', [ScheduleController::class, 'storeSpecial'])->name('schedules.special.store');
    Route::delete('/schedules/special/{schedule}', [ScheduleController::class, 'destroySpecial'])->name('schedules.special.destroy');
    Route::get('/scan', [AttendanceSiswaController::class, 'showScanner'])->name('scan.show');
    Route::post('/scan', [AttendanceSiswaController::class, 'processScan'])->name('scan.process');
    Route::resource('discipline', DisciplineController::class)->only(['index', 'store', 'destroy']);
    Route::resource('discipline-types', DisciplineTypeController::class);
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/input', [GradeController::class, 'create'])->name('grades.create');
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/list', [GradeController::class, 'listStudents'])->name('grades.list');
    Route::get('/report-card/{student_id}', [GradeController::class, 'reportCard'])->name('grades.report');
 
    Route::prefix('cbt')->name('cbt.')->group(function () {
        Route::resource('/', \App\Http\Controllers\CbtController::class)->parameters(['' => 'exam']);
        Route::get('/exam/{exam}/questions', [\App\Http\Controllers\CbtController::class, 'manageQuestions'])->name('questions.manage');
        Route::post('/exam/{exam}/questions', [\App\Http\Controllers\CbtController::class, 'storeQuestion'])->name('questions.store');
        Route::delete('/questions/{id}', [\App\Http\Controllers\CbtController::class, 'destroyQuestion'])->name('questions.destroy');
        Route::post('/exam/{exam}/import', [\App\Http\Controllers\CbtController::class, 'importQuestions'])->name('questions.import');
        Route::get('/questions/template', [\App\Http\Controllers\CbtController::class, 'downloadTemplate'])->name('questions.template');
         // Route Monitoring & Reset
        Route::get('/monitoring/{exam_id}', [\App\Http\Controllers\CbtController::class, 'monitoring'])->name('monitoring');
        Route::post('/reset/{exam}/{student}', [\App\Http\Controllers\CbtController::class, 'resetExam'])->name('reset'); // Route Baru
        Route::get('/results', [\App\Http\Controllers\CbtController::class, 'results'])->name('results');
    });

    Route::get('/settings/academic', [\App\Http\Controllers\AcademicYearController::class, 'index'])->name('settings.academic.index');
    Route::post('/settings/academic', [\App\Http\Controllers\AcademicYearController::class, 'store'])->name('settings.academic.store');
    Route::patch('/settings/academic/{id}/activate', [\App\Http\Controllers\AcademicYearController::class, 'activate'])->name('settings.academic.activate');
    Route::delete('/settings/academic/{id}', [\App\Http\Controllers\AcademicYearController::class, 'destroy'])->name('settings.academic.destroy');

    Route::prefix('library')->name('library.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\LibraryDashboardController::class, 'index'])->name('dashboard');
        Route::post('/books/import', [\App\Http\Controllers\BookController::class, 'import'])->name('books.import');
        Route::post('/books/categories/store-ajax', [\App\Http\Controllers\BookController::class, 'storeCategoryAjax'])->name('books.categories.ajax');
        Route::resource('books', \App\Http\Controllers\BookController::class);   
        Route::get('/circulation', [\App\Http\Controllers\LibraryCirculationController::class, 'index'])->name('circulation.index');
        Route::post('/circulation/search-student', [\App\Http\Controllers\LibraryCirculationController::class, 'searchStudent'])->name('circulation.searchStudent');
        Route::post('/circulation/search-book', [\App\Http\Controllers\LibraryCirculationController::class, 'searchBook'])->name('circulation.searchBook');
        Route::post('/circulation/borrow', [\App\Http\Controllers\LibraryCirculationController::class, 'store'])->name('circulation.store');
        Route::post('/circulation/return', [\App\Http\Controllers\LibraryCirculationController::class, 'returnBook'])->name('circulation.return');
    });

    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::post('/announcements/send', [AnnouncementController::class, 'sendNotification'])->name('announcements.send');
    Route::post('/agendas', [AnnouncementController::class, 'storeAgenda'])->name('agendas.store');
    Route::delete('/agendas/{id}', [AnnouncementController::class, 'destroyAgenda'])->name('agendas.destroy');

    Route::resource('users', UserController::class);
   
    // --- Route Cetak Laporan ---
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

     // === TEACHING AGENDA (KBM) ===
    Route::prefix('teaching')->name('teaching.')->group(function () {
        // Dashboard Jadwal Mengajar Guru
        Route::get('/', [\App\Http\Controllers\TeachingController::class, 'index'])->name('index');
        
        // [BARU] Route Riwayat
        Route::get('/history', [\App\Http\Controllers\TeachingController::class, 'history'])->name('history');

        // Mulai Sesi (Tombol Start)
        Route::post('/start/{schedule_id}', [\App\Http\Controllers\TeachingController::class, 'start'])->name('start');
        
         // Simpan Absen Manual (Sakit/Izin/Alpha/Hadir Manual)
        Route::post('/attendance/manual', [\App\Http\Controllers\TeachingController::class, 'storeManual'])->name('manual');
        
        // Halaman KBM Berlangsung (Isi Jurnal & Live Absen)
        Route::get('/session/{id}', [\App\Http\Controllers\TeachingController::class, 'show'])->name('show');
        
        // Simpan Data Jurnal (Ajax/Form)
        Route::put('/session/{id}', [\App\Http\Controllers\TeachingController::class, 'update'])->name('update');
        
        // Proses Scan Kartu Siswa
        Route::post('/scan', [\App\Http\Controllers\TeachingController::class, 'scan'])->name('scan');
        
        // Tutup Sesi & Generate Alpha (Tombol Finish)
        Route::post('/close/{id}', [\App\Http\Controllers\TeachingController::class, 'close'])->name('close');
    });

     // MONITORING & LAPORAN (ADMIN)
    Route::get('/reports/teaching-journal', [\App\Http\Controllers\ReportController::class, 'teachingJournal'])->name('reports.teaching_journal');


    Route::resource('subjects', \App\Http\Controllers\SubjectController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('achievements', \App\Http\Controllers\AchievementController::class);
    Route::resource('school-activities', \App\Http\Controllers\SchoolActivityController::class);

});

require __DIR__.'/auth.php';