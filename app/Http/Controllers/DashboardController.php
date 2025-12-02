<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AttendanceSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. TENTUKAN PERIODE & TANGGAL
        $period = $request->query('period', 'today'); 
        $dateParam = $request->query('date', Carbon::today()->toDateString());
        
        $startDate = Carbon::parse($dateParam)->startOfDay();
        $endDate = Carbon::parse($dateParam)->endOfDay();

        if ($period === 'week') {
            $startDate = Carbon::parse($dateParam)->startOfWeek();
            $endDate = Carbon::parse($dateParam)->endOfWeek();
        } elseif ($period === 'month') {
            $startDate = Carbon::parse($dateParam)->startOfMonth();
            $endDate = Carbon::parse($dateParam)->endOfMonth();
        }

        // 2. DATA STATISTIK UTAMA (KPIS)
        $totalStudents = Student::count();
        
        // Ambil data dalam range
        $attendances = AttendanceSiswa::whereBetween('attendance_date', [$startDate, $endDate])->get();
        
        // Hitung Hadir Total (Fisik di sekolah)
        $presentCount = $attendances->whereIn('status', ['Hadir', 'Terlambat', 'Tepat Waktu'])->unique('student_id')->count();
        
        // Hitung Terlambat
        $lateCount = $attendances->whereIn('status', ['Terlambat'])->unique('student_id')->count();
        
        // Hitung Hadir Tepat Waktu (Hadir Total - Terlambat)
        $presentOnTimeCount = max(0, $presentCount - $lateCount);

        // Hitung Kategori Izin/Sakit/Alpa
        $sickCount = $attendances->whereIn('status', ['Sakit'])->unique('student_id')->count();
        $permitCount = $attendances->whereIn('status', ['Izin'])->unique('student_id')->count();
        $alphaCount = $attendances->whereIn('status', ['Alpa', 'Alpha'])->unique('student_id')->count();
        
        // Hitung Pulang Awal
        $earlyLeaveCount = $attendances->filter(function ($att) {
            return str_contains(strtolower($att->notes ?? ''), 'pulang awal');
        })->count();

        // [PERBAIKAN LOGIKA]: Hitung "Belum Hadir"
        // Hanya relevan jika filter adalah 'today'. Jika week/month, "Belum Hadir" tidak relevan (selalu 0 atau rata-rata)
        $absentCount = 0;
        if ($period === 'today') {
             // Total Siswa - (Yang sudah absen Harian/Masuk/Pulang/Sakit/Izin/Alpa)
             $alreadyRecorded = $attendances->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
                                          ->unique('student_id')
                                          ->count();
             
             // Pastikan tidak negatif
             $absentCount = max(0, $totalStudents - $alreadyRecorded);
        }

        // 3. LOGIKA GRAFIK (CHART)
        $weeklyPresentData = [];
        $weeklyLateData = [];
        $weeklyAbsentData = [];
        $chartLabels = [];

        // Loop untuk grafik: Default 7 hari terakhir jika 'today', atau sesuai range jika week/month
        $graphStart = ($period === 'today') ? Carbon::today()->subDays(6) : $startDate;
        $graphEnd   = ($period === 'today') ? Carbon::today() : $endDate;

        $periodLoop = $graphStart->copy();
        while($periodLoop <= $graphEnd) {
            $dateStr = $periodLoop->toDateString();
            
            // Format Label Tanggal
            $chartLabels[] = $periodLoop->format('d M'); 

            // Query Harian
            $dailyAtt = AttendanceSiswa::whereDate('attendance_date', $dateStr)->get();

            // [PENTING] Data Grafik harus konsisten
            // PresentData = MURNI Tepat Waktu (Hadir/Tepat Waktu)
            $weeklyPresentData[] = $dailyAtt->whereIn('status', ['Hadir', 'Tepat Waktu'])->unique('student_id')->count();
            
            // LateData = MURNI Terlambat
            $weeklyLateData[] = $dailyAtt->whereIn('status', ['Terlambat'])->unique('student_id')->count();
            
            // AbsentData = Sakit + Izin + Alpa
            $weeklyAbsentData[] = $dailyAtt->whereIn('status', ['Sakit', 'Izin', 'Alpa', 'Alpha'])->unique('student_id')->count();

            $periodLoop->addDay();
        }

        return view('dashboard', [
            'totalStudents' => $totalStudents,
            'presentCount' => $presentCount, // Total Hadir (termasuk telat)
            'presentOnTimeCount' => $presentOnTimeCount, // Hadir Tepat Waktu
            'lateCount' => $lateCount,
            'absentCount' => $absentCount, // Belum Hadir (Realtime)
            'earlyLeaveCount' => $earlyLeaveCount,
            'sickCount' => $sickCount,
            'permitCount' => $permitCount,
            'alphaCount' => $alphaCount,
            
            // Data Grafik
            'chartLabels' => $chartLabels,
            'weeklyPresentData' => $weeklyPresentData, // Array Tepat Waktu
            'weeklyLateData' => $weeklyLateData,       // Array Terlambat
            'weeklyAbsentData' => $weeklyAbsentData,   // Array Tidak Hadir
        ]);
    }
}