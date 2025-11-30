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
        // 1. TENTUKAN PERIODE BERDASARKAN INPUT FILTER
        $period = $request->query('period', 'today'); // Default 'today'
        $dateParam = $request->query('date', Carbon::today()->toDateString());
        
        $startDate = Carbon::parse($dateParam)->startOfDay();
        $endDate = Carbon::parse($dateParam)->endOfDay();

        if ($period === 'week') {
            // Jika filter mingguan, ambil awal & akhir minggu dari tanggal yang dipilih
            // Catatan: Input type="week" mengembalikan format "2024-W10", perlu parsing khusus jika pakai input week
            // Untuk simplifikasi, kita anggap user memilih tanggal dalam minggu tersebut
            $startDate = Carbon::parse($dateParam)->startOfWeek();
            $endDate = Carbon::parse($dateParam)->endOfWeek();
        } elseif ($period === 'month') {
            $startDate = Carbon::parse($dateParam)->startOfMonth();
            $endDate = Carbon::parse($dateParam)->endOfMonth();
        }

        // 2. QUERY DATA BERDASARKAN RANGE TANGGAL
        $totalStudents = Student::count();
        
        $attendances = AttendanceSiswa::whereBetween('attendance_date', [$startDate, $endDate])->get();
        
        // Hitung Statistik Dasar dari data yang didapat
        $presentCount = $attendances->whereIn('status', ['Hadir', 'Terlambat'])->unique('student_id')->count();
        $lateCount = $attendances->where('status', 'Terlambat')->count();
        
        // Logika sederhana untuk absensi (perlu disesuaikan jika range > 1 hari)
        // Jika periode > 1 hari, konsep "Belum Hadir" jadi beda (rata-rata atau total insiden)
        // Di sini kita pakai logika sederhana: Total Siswa - Unik Hadir (Valid utk Harian)
        $absentCount = ($period === 'today') ? max(0, $totalStudents - $presentCount) : 0; 

        $sickCount = $attendances->where('status', 'Sakit')->count();
        $permitCount = $attendances->where('status', 'Izin')->count();
        $alphaCount = $attendances->where('status', 'Alpa')->count();
        
        // Cek notes untuk pulang awal (case sensitive search via PHP collection)
        $earlyLeaveCount = $attendances->filter(function ($att) {
            return str_contains(strtolower($att->notes ?? ''), 'pulang awal');
        })->count();

        // 3. SIAPKAN DATA GRAFIK (MINGGUAN/HARIAN)
        // Kita gunakan range start-end date yang sudah diset di atas
        $weeklyPresentData = [];
        $weeklyLateData = [];
        $weeklyAbsentData = [];
        
        // Loop 6-7 hari atau sesuai range
        // (Logika loop grafik Anda sebelumnya bisa dipindah ke sini dengan menggunakan $startDate sebagai patokan)

        return view('dashboard', [
            'totalStudents' => $totalStudents,
            'presentCount' => $presentCount,
            'lateCount' => $lateCount,
            'absentCount' => $absentCount,
            'earlyLeaveCount' => $earlyLeaveCount,
            'sickCount' => $sickCount,
            'permitCount' => $permitCount,
            'alphaCount' => $alphaCount,
            
            // Variabel grafik (pastikan array ini terisi sesuai periode)
            'weeklyPresentData' => $weeklyPresentData, // Isi array dummy atau hasil query
            'weeklyLateData' => $weeklyLateData,
            'weeklyAbsentData' => $weeklyAbsentData,
            
            // Kirim parameter agar filter di UI tetap aktif
            'presentOnTimeCount' => max(0, $presentCount - $lateCount),
        ]);
    }
}