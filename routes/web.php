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
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
   // return view('welcome');
    return redirect()->route('login');
});

// RUTE DASHBOARD 
Route::get('/dashboard', [DashboardController::class, 'index']) 
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    // RUTE KIOSK (PUBLIK, TANPA AUTH)
Route::get('/kiosk', [KioskController::class, 'showKiosk'])->name('kiosk.show');
Route::post('/kiosk/process', [KioskController::class, 'processKioskScan'])->name('kiosk.process');

// 2. TAMBAHKAN RUTE PORTAL SISWA DI SINI
Route::get('/portal', [StudentPortalController::class, 'index'])->name('portal.index');
Route::post('/portal/search', [StudentPortalController::class, 'search'])->name('portal.search');
Route::get('/portal/{student_id}', [StudentPortalController::class, 'show'])->name('portal.show');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Manajemen Siswa
    Route::resource('students', StudentController::class);
    // 1. TAMBAHKAN RUTE Export dan IMPORT DI SINI
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::post('/students/export',[StudentController::class,'export'])->name('students.export');

    // Rute Manajemen Kelas
    Route::resource('classes', SchoolClassController::class);

    // Rute Manajemen Jadwal
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('/schedules/regular', [ScheduleController::class, 'storeRegular'])->name('schedules.regular.store');
    Route::post('/schedules/special', [ScheduleController::class, 'storeSpecial'])->name('schedules.special.store');
    Route::delete('/schedules/special/{schedule}', [ScheduleController::class, 'destroySpecial'])->name('schedules.special.destroy');

    // RUTE INI UNTUK SCAN ABSENSI
    Route::get('/scan', [AttendanceSiswaController::class, 'showScanner'])->name('scan.show');
    Route::post('/scan', [AttendanceSiswaController::class, 'processScan'])->name('scan.process');

     // RUTE INI UNTUK CATATAN DISIPLIN
    Route::resource('discipline', DisciplineController::class)->only([
        'index', 'store', 'destroy'
    ]);

     // RUTE INI UNTUK MANAJEMEN PENGGUNA
    Route::resource('users', UserController::class);
   
    //  DUA RUTE INI UNTUK LAPORAN
    // Rute untuk menampilkan halaman
    Route::get('/reports/daily', [ReportController::class, 'dailyReport'])->name('reports.daily');
    // Rute untuk memproses form input manual
    Route::post('/reports/manual-entry', [ReportController::class, 'storeManualEntry'])->name('reports.storeManual');
});

require __DIR__.'/auth.php';
