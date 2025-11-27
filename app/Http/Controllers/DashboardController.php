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
        
        $attendancesToday = AttendanceSiswa::whereDate('attendance_date', $today)->get();
        $groupedAttendances = $attendancesToday->groupBy('student_id');

        $presentCount = 0;
        $lateCount = 0;
        $earlyLeaveCount = 0;
        $sickCount = 0;
        $permitCount = 0;
        $alphaCount = 0;

        foreach ($groupedAttendances as $studentId => $records) {
            $statuses = $records->pluck('status')->toArray();
            
            // Gabungkan semua notes untuk pengecekan manual (Pulang Awal)
            $allNotes = $records->pluck('notes')->implode(' ');

            // --- PERBAIKAN LOGIKA STATUS ---
            // Cek apakah ada status 'Hadir' ATAU 'Terlambat' (karena Terlambat = Hadir secara fisik)
            $isHadir = in_array('Hadir', $statuses);
            $isTerlambat = in_array('Terlambat', $statuses); // <-- Cek status Terlambat dari DB

            if ($isHadir || $isTerlambat) {
                $presentCount++; // Hitung sebagai hadir fisik

                // Cek Terlambat:
                // 1. Jika status di DB 'Terlambat'
                // 2. ATAU jika di notes ada kata 'Terlambat' (untuk data lama/kompatibilitas)
                if ($isTerlambat || stripos($allNotes, 'Terlambat') !== false) {
                    $lateCount++;
                }

                // Cek Pulang Awal
                if (stripos($allNotes, 'Pulang Awal') !== false) {
                    $earlyLeaveCount++;
                }
            } 
            elseif (in_array('Sakit', $statuses)) {
                $sickCount++;
            } elseif (in_array('Izin', $statuses)) {
                $permitCount++;
            } elseif (in_array('Alfa', $statuses)) {
                $alphaCount++;
            }
        }

        // Hitung Belum Hadir 
        $totalRecorded = $groupedAttendances->count();
        $absentCount = max(0, $totalStudents - $totalRecorded);

        // Hitung Presentase Kehadiran
        $presentPercentage = $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100, 1) : 0;
        
        // Hadir Tepat Waktu (Hadir Total - Terlambat)
        $presentOnTimeCount = max(0, $presentCount - $lateCount);


        // === 3. DATA MINGGUAN (Untuk Grafik Batang) ===
        $weeklyPresentData = [];
        $weeklyLateData = [];
        $weeklyAbsentData = [];

        $weeklyAttendances = AttendanceSiswa::whereBetween('attendance_date', [$startOfWeek, $endOfWeek])->get();

        for ($i = 0; $i < 6; $i++) { // Senin - Sabtu
            $dateCheck = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
            
            $dailyRecords = $weeklyAttendances->filter(function ($item) use ($dateCheck) {
                return Carbon::parse($item->attendance_date)->format('Y-m-d') == $dateCheck;
            });

            $dailyGrouped = $dailyRecords->groupBy('student_id');

            $dailyPresent = 0;
            $dailyLate = 0;
            $dailyAbsent = 0;

            foreach ($dailyGrouped as $studentId => $records) {
                $statuses = $records->pluck('status')->toArray();
                $allNotes = $records->pluck('notes')->implode(' ');

                $isHadir = in_array('Hadir', $statuses);
                $isTerlambat = in_array('Terlambat', $statuses);

                // PERBAIKAN LOGIKA MINGGUAN JUGA
                if ($isHadir || $isTerlambat) {
                    $dailyPresent++;
                    
                    if ($isTerlambat || stripos($allNotes, 'Terlambat') !== false) {
                        $dailyLate++;
                    }
                } elseif (in_array('Sakit', $statuses) || in_array('Izin', $statuses) || in_array('Alfa', $statuses)) {
                    $dailyAbsent++;
                }
            }

            $weeklyPresentData[] = $dailyPresent;
            $weeklyLateData[] = $dailyLate;
            $weeklyAbsentData[] = $dailyAbsent;
        }

        // === 4. KIRIM KE VIEW ===
        return view('dashboard', [
            'totalStudents' => $totalStudents,
            'presentCount' => $presentCount,     
            'lateCount' => $lateCount,
            'absentCount' => $absentCount,      
            'sickCount' => $sickCount,
            'permitCount' => $permitCount,
            'alphaCount' => $alphaCount,
            'earlyLeaveCount' => $earlyLeaveCount,
            'presentPercentage' => $presentPercentage,
            'presentOnTimeCount' => $presentOnTimeCount,
            'weeklyPresentData' => $weeklyPresentData,
            'weeklyLateData' => $weeklyLateData,
            'weeklyAbsentData' => $weeklyAbsentData,
        ]);
    }
}