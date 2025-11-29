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
use App\Http\Controllers\GuestBookController; // [BARU] Import Controller Buku Tamu
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- UTAMA: RUTE LANDING PAGE ---
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// RUTE DIREKTORI GURU
Route::get('/pengajar', [LandingPageController::class, 'teachers'])->name('teachers.index');

// RUTE BUKU TAMU (Publik) - Ini perbaikan untuk error Anda
Route::post('/guestbook', [GuestBookController::class, 'store'])->name('guestbook.store');

// RUTE DASHBOARD (Hanya bisa diakses setelah Login)
Route::get('/dashboard', [DashboardController::class, 'index']) 
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// RUTE KIOSK (Publik - Mesin Absensi)
Route::get('/kiosk', [KioskController::class, 'showKiosk'])->name('kiosk.show');
Route::post('/kiosk/process', [KioskController::class, 'processKioskScan'])->name('kiosk.process');

// RUTE PORTAL SISWA (Publik - Cek Poin/Absensi)
Route::get('/portal', [StudentPortalController::class, 'index'])->name('portal.index');
Route::post('/portal/search', [StudentPortalController::class, 'search'])->name('portal.search');
Route::get('/portal/{student_id}', [StudentPortalController::class, 'show'])->name('portal.show');

// RUTE KIOSK PERPUSTAKAAN (BUKU TAMU)
Route::get('/library/kiosk', [\App\Http\Controllers\LibraryKioskController::class, 'index'])->name('library.kiosk.index');
Route::post('/library/kiosk/process', [\App\Http\Controllers\LibraryKioskController::class, 'process'])->name('library.kiosk.process');

// --- GRUP RUTE YANG BUTUH LOGIN ---
Route::middleware('auth')->group(function () {
    
    // Profil User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Manajemen Siswa    
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/students/export', [StudentController::class, 'export'])->name('students.export'); 
    
    Route::resource('students', StudentController::class); 

    // Manajemen Kelas
    Route::resource('classes', SchoolClassController::class);

    // Manajemen Jadwal
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('/schedules/regular', [ScheduleController::class, 'storeRegular'])->name('schedules.regular.store');
    Route::post('/schedules/special', [ScheduleController::class, 'storeSpecial'])->name('schedules.special.store');
    Route::delete('/schedules/special/{schedule}', [ScheduleController::class, 'destroySpecial'])->name('schedules.special.destroy');

    // Scan Absensi (Fitur Guru Piket)
    Route::get('/scan', [AttendanceSiswaController::class, 'showScanner'])->name('scan.show');
    Route::post('/scan', [AttendanceSiswaController::class, 'processScan'])->name('scan.process');

    // Catatan Disiplin (Input Pelanggaran/Kebaikan)
    Route::resource('discipline', DisciplineController::class)->only([
        'index', 'store', 'destroy'
    ]);
    
    // Manajemen Tipe Disiplin (Master Data Pelanggaran/Kebaikan)
    Route::resource('discipline-types', DisciplineTypeController::class);

    // --- MODUL: MANAJEMEN NILAI & E-RAPOR ---
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/input', [GradeController::class, 'create'])->name('grades.create');
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
    Route::get('/report-card/{student_id}', [GradeController::class, 'reportCard'])->name('grades.report');
 
    // PENGATURAN AKADEMIK (BARU)
    Route::get('/settings/academic', [\App\Http\Controllers\AcademicYearController::class, 'index'])->name('settings.academic.index');
    Route::post('/settings/academic', [\App\Http\Controllers\AcademicYearController::class, 'store'])->name('settings.academic.store');
    Route::patch('/settings/academic/{id}/activate', [\App\Http\Controllers\AcademicYearController::class, 'activate'])->name('settings.academic.activate');
    Route::delete('/settings/academic/{id}', [\App\Http\Controllers\AcademicYearController::class, 'destroy'])->name('settings.academic.destroy');

    // === MODUL PERPUSTAKAAN ===
    Route::prefix('library')->name('library.')->group(function () {
        // 1. Dashboard Perpus
        Route::get('/dashboard', [\App\Http\Controllers\LibraryDashboardController::class, 'index'])->name('dashboard');
        
        // 2. Manajemen Buku
        Route::post('/books/import', [\App\Http\Controllers\BookController::class, 'import'])->name('books.import');
        Route::resource('books', \App\Http\Controllers\BookController::class);

        // 3. Sirkulasi (Peminjaman & Pengembalian)
        Route::get('/circulation', [\App\Http\Controllers\LibraryCirculationController::class, 'index'])->name('circulation.index');
        Route::post('/circulation/search-student', [\App\Http\Controllers\LibraryCirculationController::class, 'searchStudent'])->name('circulation.searchStudent');
        Route::post('/circulation/search-book', [\App\Http\Controllers\LibraryCirculationController::class, 'searchBook'])->name('circulation.searchBook');
        Route::post('/circulation/borrow', [\App\Http\Controllers\LibraryCirculationController::class, 'store'])->name('circulation.store');
        Route::post('/circulation/return', [\App\Http\Controllers\LibraryCirculationController::class, 'returnBook'])->name('circulation.return');
    });

    // Pengumuman & Notifikasi WA Broadcast
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::post('/announcements/send', [AnnouncementController::class, 'sendNotification'])->name('announcements.send');

    // Manajemen Pengguna (Admin)
    Route::resource('users', UserController::class);
   
    // Laporan & Rekap
    Route::get('/reports/daily', [ReportController::class, 'dailyReport'])->name('reports.daily');
    Route::post('/reports/manual-entry', [ReportController::class, 'storeManualEntry'])->name('reports.storeManual');
    Route::post('/reports/process-alpha', [ReportController::class, 'processAlpha'])->name('reports.processAlpha');
    Route::delete('/reports/daily', [ReportController::class, 'destroyDaily'])->name('reports.destroyDaily');
    Route::get('/reports/export-daily', [ReportController::class, 'exportDaily'])->name('reports.exportDaily');
    Route::get('/reports/attendance/{attendance}/edit', [ReportController::class, 'editAttendance'])->name('reports.edit');
    Route::put('/reports/attendance/{attendance}', [ReportController::class, 'updateAttendance'])->name('reports.update');
    Route::delete('/reports/attendance/{attendance}', [ReportController::class, 'deleteAttendance'])->name('reports.delete');

    // Route Khusus untuk Absensi Keagamaan
    Route::get('/reports/religious', [ReportController::class, 'religiousReport'])->name('reports.religious');
    Route::delete('/reports/religious', [ReportController::class, 'destroyReligious'])->name('reports.destroyReligious');
    Route::get('/reports/export-religious', [ReportController::class, 'exportReligious'])->name('reports.exportReligious');

     // PENGATURAN MATA PELAJARAN
    Route::resource('subjects', \App\Http\Controllers\SubjectController::class)->only(['index', 'store', 'update', 'destroy']);
    
    // Route Achievement
    Route::resource('achievements', \App\Http\Controllers\AchievementController::class);

    // Route Kegiatan Sekolah (Admin CRUD)
    Route::resource('school-activities', \App\Http\Controllers\SchoolActivityController::class);

});

require __DIR__.'/auth.php';