<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceSiswa; // Pastikan Model ini benar
use App\Models\LibraryVisit;
use App\Models\Borrowing;
use App\Models\Announcement;
use App\Models\Achievement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // --- 1. STATISTIK HARIAN (KOTAK INFO) ---
        // PERBAIKAN: Menghapus ->where('type', 'Masuk') agar query lebih fleksibel
        // PERBAIKAN: Menggunakan whereIn untuk toleransi penulisan huruf besar/kecil
        
        $hadir = AttendanceSiswa::whereDate('attendance_date', $today)
                    ->whereIn('status', ['Hadir', 'hadir', 'Present']) // Cek berbagai kemungkinan penulisan
                    ->count();
                    
        $terlambat = AttendanceSiswa::whereDate('attendance_date', $today)
                    ->whereIn('status', ['Terlambat', 'terlambat', 'Late'])
                    ->count();
                    
        $sakit = AttendanceSiswa::whereDate('attendance_date', $today)
                    ->whereIn('status', ['Sakit', 'sakit'])
                    ->count();
                    
        $izin = AttendanceSiswa::whereDate('attendance_date', $today)
                    ->whereIn('status', ['Izin', 'izin'])
                    ->count();
                    
        $alpa = AttendanceSiswa::whereDate('attendance_date', $today)
                    ->whereIn('status', ['Alpa', 'alpa', 'Alpha', 'alpha'])
                    ->count();

        // Debugging (Opsional): Jika masih 0, uncomment baris bawah ini untuk cek data mentah
        // dd(AttendanceSiswa::whereDate('attendance_date', $today)->get());

        $stats = [
            'hadir'       => $hadir + $terlambat, // Total yang fisik ada di sekolah
            'tepat_waktu' => $hadir,
            'terlambat'   => $terlambat,
            'tidak_hadir' => $sakit + $izin + $alpa
        ];

        // --- 2. CHART MINGGUAN (STACKED) ---
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Helper Query (Diperbaiki: Hapus filter Type)
        $getQuery = function($statusList) use ($startOfWeek, $endOfWeek) {
            // Pastikan status berupa array
            if (!is_array($statusList)) {
                $statusList = [$statusList];
            }

            return AttendanceSiswa::select(DB::raw('DATE(attendance_date) as date'), DB::raw('count(*) as total'))
                ->whereBetween('attendance_date', [$startOfWeek, $endOfWeek])
                ->whereIn('status', $statusList) // Pakai whereIn agar lebih aman
                ->groupBy('date')
                ->pluck('total', 'date');
        };

        // Ambil Data Chart
        $dataTepatWaktu = $getQuery(['Hadir', 'hadir', 'Present']);
        $dataTerlambat  = $getQuery(['Terlambat', 'terlambat']);
        $dataTidakHadir = $getQuery(['Sakit', 'sakit', 'Izin', 'izin', 'Alpa', 'alpa', 'Alpha']);

        // Format Data untuk ChartJS
        $labels = [];
        $datasetHadir = [];
        $datasetTelat = [];
        $datasetAbsen = [];

        // Loop 7 Hari (Senin - Minggu)
        for ($i = 0; $i < 7; $i++) {
            $currentDay = $startOfWeek->copy()->addDays($i);
            $dateKey = $currentDay->format('Y-m-d');
            
            // Label Hari Indonesia
            $labels[] = $currentDay->locale('id')->isoFormat('dddd'); 
            
            $datasetHadir[] = $dataTepatWaktu[$dateKey] ?? 0;
            $datasetTelat[] = $dataTerlambat[$dateKey] ?? 0;
            $datasetAbsen[] = $dataTidakHadir[$dateKey] ?? 0;
        }

        $barChartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Hadir Tepat Waktu',
                    'data' => $datasetHadir,
                    'backgroundColor' => '#10b981', // Emerald
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Terlambat',
                    'data' => $datasetTelat,
                    'backgroundColor' => '#f59e0b', // Amber
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Tidak Hadir',
                    'data' => $datasetAbsen,
                    'backgroundColor' => '#ef4444', // Red
                    'borderRadius' => 4,
                ]
            ]
        ];

        // --- 3. DATA LAINNYA (Perpus, Pengumuman, dll) ---
        // Menggunakan try-catch agar jika tabel belum ada tidak error 500
        try {
            $libraryStats = [
                'visitors_today' => LibraryVisit::whereDate('date', $today)->count(),
                'books_borrowed' => Borrowing::where('status', 'borrowed')->count(),
            ];
            
            $libWeeklyData = LibraryVisit::select(DB::raw('DATE(date) as visit_date'), DB::raw('count(*) as total'))
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->groupBy('visit_date')->pluck('total', 'visit_date');

            $libData = [];
            foreach ($labels as $index => $dayName) {
                $date = $startOfWeek->copy()->addDays($index)->format('Y-m-d');
                $libData[] = $libWeeklyData[$date] ?? 0;
            }
            $libraryChartData = ['labels' => $labels, 'data' => $libData];
        } catch (\Exception $e) {
            // Fallback jika tabel library belum ada
            $libraryStats = ['visitors_today' => 0, 'books_borrowed' => 0];
            $libraryChartData = ['labels' => $labels, 'data' => array_fill(0, 7, 0)];
        }

        $announcements = Announcement::orderBy('created_at', 'desc')->limit(3)->get();
        $achievements = Achievement::with('student')->orderBy('date', 'desc')->limit(6)->get();

        return view('welcome', compact(
            'stats', 'barChartData', 
            'libraryStats', 'libraryChartData', 
            'announcements', 'achievements'
        ));
    }
}