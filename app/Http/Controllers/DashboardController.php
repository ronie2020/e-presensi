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
        // 1. TENTUKAN PERIODE
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

        // 2. QUERY DATA UTAMA
        $totalStudents = Student::count();
        
        // Ambil semua data dalam range sekaligus (Eager Loading) agar hemat query
        $attendances = AttendanceSiswa::whereBetween('attendance_date', [$startDate, $endDate])->get();
        
        // Hitung Statistik Global
        $presentCount = $attendances->whereIn('status', ['Hadir', 'Terlambat', 'Tepat Waktu'])->unique('student_id')->count();
        $lateCount = $attendances->whereIn('status', ['Terlambat'])->unique('student_id')->count();
        
        // Hitung Absen (Sakit/Izin/Alfa)
        $sickCount = $attendances->whereIn('status', ['Sakit'])->count();
        $permitCount = $attendances->whereIn('status', ['Izin'])->count();
        $alphaCount = $attendances->whereIn('status', ['Alpa', 'Alpha'])->count();
        
        // Hitung yang belum absen (Hanya relevan jika periode = hari ini)
        $absentCount = 0;
        if ($period === 'today') {
             // Total Siswa - (Hadir + Terlambat + Sakit + Izin + Alfa)
             $recorded = $presentCount + $sickCount + $permitCount + $alphaCount; 
             $absentCount = max(0, $totalStudents - $recorded);
        }

        $earlyLeaveCount = $attendances->filter(function ($att) {
            return str_contains(strtolower($att->notes ?? ''), 'pulang awal');
        })->count();

        // 3. LOGIKA GRAFIK (CHART) - PERBAIKAN DI SINI
        $weeklyPresentData = [];
        $weeklyLateData = [];
        $weeklyAbsentData = [];
        $chartLabels = [];

        // Jika filternya 'today', kita buat grafik jam (opsional) atau tetap harian 7 hari terakhir untuk tren
        // Agar grafik selalu menarik, kita defaultkan menampilkan tren 7 hari terakhir jika filter bukan month/week
        $graphStart = ($period === 'today') ? Carbon::today()->subDays(6) : $startDate;
        $graphEnd   = ($period === 'today') ? Carbon::today() : $endDate;

        $periodLoop = $graphStart->copy();
        while($periodLoop <= $graphEnd) {
            $dateStr = $periodLoop->toDateString();
            $chartLabels[] = $periodLoop->format('d M'); // Label X-Axis (Tgl)

            // Query per hari dari range
            // Note: Jika range sangat besar (bulan), query dalam loop bisa berat. 
            // Untuk optimasi produksi, gunakan groupBy di SQL. Tapi untuk skala sekolah, ini cukup.
            $dailyAtt = AttendanceSiswa::whereDate('attendance_date', $dateStr)->get();

            $weeklyPresentData[] = $dailyAtt->whereIn('status', ['Hadir', 'Tepat Waktu'])->unique('student_id')->count();
            $weeklyLateData[] = $dailyAtt->whereIn('status', ['Terlambat'])->unique('student_id')->count();
            // Sakit/Izin/Alfa digabung jadi "Tidak Hadir" di grafik
            $weeklyAbsentData[] = $dailyAtt->whereIn('status', ['Sakit', 'Izin', 'Alpa', 'Alpha'])->unique('student_id')->count();

            $periodLoop->addDay();
        }

        return view('dashboard', [
            'totalStudents' => $totalStudents,
            'presentCount' => $presentCount,
            'lateCount' => $lateCount,
            'absentCount' => $absentCount, // Belum Hadir
            'earlyLeaveCount' => $earlyLeaveCount,
            'sickCount' => $sickCount,
            'permitCount' => $permitCount,
            'alphaCount' => $alphaCount,
            
            // Data Grafik yang sudah diisi
            'chartLabels' => $chartLabels, // Kirim label tanggal
            'weeklyPresentData' => $weeklyPresentData,
            'weeklyLateData' => $weeklyLateData,
            'weeklyAbsentData' => $weeklyAbsentData,
            
            'presentOnTimeCount' => max(0, $presentCount - $lateCount),
        ]);
    }
}