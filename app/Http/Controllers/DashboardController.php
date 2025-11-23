<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AttendanceSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan data yang sesuai untuk Tampilan Modern.
     */
    public function index(Request $request)
    {
        // === 1. KONFIGURASI DASAR ===
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek(); 
        $endOfWeek = Carbon::now()->endOfWeek();

        // Ambil total siswa aktif
        $totalStudents = Student::count();

        // === 2. DATA HARI INI (Untuk Kartu Statistik Atas & Donut Chart) ===
        
        // Ambil semua absensi hari ini
        $attendancesToday = AttendanceSiswa::whereDate('attendance_date', $today)->get();

        // --- PERBAIKAN UTAMA: GROUPING BY SISWA ---
        // Kita kelompokkan data berdasarkan student_id agar Masuk & Pulang dihitung sebagai 1 orang.
        $groupedAttendances = $attendancesToday->groupBy('student_id');

        // Inisialisasi Variabel Counter
        $presentCount = 0;
        $lateCount = 0;
        $earlyLeaveCount = 0;
        $sickCount = 0;
        $permitCount = 0;
        $alphaCount = 0;

        // Looping setiap siswa unik untuk menentukan status finalnya hari ini
        foreach ($groupedAttendances as $studentId => $records) {
            // Ambil status unik dari record siswa ini (misal: Hadir, Sakit, Izin)
            $statuses = $records->pluck('status')->toArray();
            
            // Gabungkan semua notes untuk pengecekan Terlambat/Pulang Awal
            $allNotes = $records->pluck('notes')->implode(' ');

            // LOGIKA PRIORITAS STATUS
            if (in_array('Hadir', $statuses)) {
                $presentCount++;

                // Cek Terlambat (Cari kata 'Terlambat' di notes record manapun milik siswa ini)
                if (stripos($allNotes, 'Terlambat') !== false) {
                    $lateCount++;
                }

                // Cek Pulang Awal
                if (stripos($allNotes, 'Pulang Awal') !== false) {
                    $earlyLeaveCount++;
                }
            } elseif (in_array('Sakit', $statuses)) {
                $sickCount++;
            } elseif (in_array('Izin', $statuses)) {
                $permitCount++;
            } elseif (in_array('Alfa', $statuses)) {
                $alphaCount++;
            }
        }

        // Hitung Belum Hadir 
        // Rumus: Total Siswa - (Yang sudah ada statusnya hari ini)
        $totalRecorded = $groupedAttendances->count();
        $absentCount = max(0, $totalStudents - $totalRecorded);

        // Hitung Presentase Kehadiran
        $presentPercentage = $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100, 1) : 0;
        
        // Hadir Tepat Waktu (Hadir Total - Terlambat)
        $presentOnTimeCount = max(0, $presentCount - $lateCount);


        // === 3. DATA MINGGUAN (Untuk Grafik Batang) ===
        // Array kosong untuk 6 hari (Senin-Sabtu)
        $weeklyPresentData = [];
        $weeklyLateData = [];
        $weeklyAbsentData = [];

        // Ambil data seminggu
        $weeklyAttendances = AttendanceSiswa::whereBetween('attendance_date', [$startOfWeek, $endOfWeek])->get();

        for ($i = 0; $i < 6; $i++) {
            $dateCheck = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
            
            // Filter data per hari
            $dailyRecords = $weeklyAttendances->filter(function ($item) use ($dateCheck) {
                return Carbon::parse($item->attendance_date)->format('Y-m-d') == $dateCheck;
            });

            // Grouping per siswa untuk data mingguan juga (PENTING!)
            $dailyGrouped = $dailyRecords->groupBy('student_id');

            $dailyPresent = 0;
            $dailyLate = 0;
            $dailyAbsent = 0; // Sakit/Izin/Alfa

            foreach ($dailyGrouped as $studentId => $records) {
                $statuses = $records->pluck('status')->toArray();
                $allNotes = $records->pluck('notes')->implode(' ');

                if (in_array('Hadir', $statuses)) {
                    $dailyPresent++;
                    if (stripos($allNotes, 'Terlambat') !== false) {
                        $dailyLate++;
                    }
                } elseif (in_array('Sakit', $statuses) || in_array('Izin', $statuses) || in_array('Alfa', $statuses)) {
                    $dailyAbsent++;
                }
            }

            // Masukkan ke array grafik
            $weeklyPresentData[] = $dailyPresent;
            $weeklyLateData[] = $dailyLate;
            $weeklyAbsentData[] = $dailyAbsent;
        }


        // === 4. KIRIM KE VIEW ===
        return view('dashboard', [
            // Kartu Utama
            'totalStudents' => $totalStudents,
            'presentCount' => $presentCount,     // Sekarang menghitung ORANG, bukan baris
            'lateCount' => $lateCount,
            'absentCount' => $absentCount,       // Belum Hadir (Siswa yg belum scan sama sekali)
            'sickCount' => $sickCount,
            'permitCount' => $permitCount,
            'alphaCount' => $alphaCount,
            'earlyLeaveCount' => $earlyLeaveCount,
            'presentPercentage' => $presentPercentage,
            
            // Data Donut Chart
            'presentOnTimeCount' => $presentOnTimeCount,

            // Data Bar Chart (Mingguan)
            'weeklyPresentData' => $weeklyPresentData,
            'weeklyLateData' => $weeklyLateData,
            'weeklyAbsentData' => $weeklyAbsentData,
        ]);
    }
}