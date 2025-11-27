<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceSiswa;
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
        
        // 1. STATISTIK HARIAN (Tetap diambil untuk kotak info)
        $hadir = AttendanceSiswa::whereDate('attendance_date', $today)->where('type', 'Masuk')->where('status', 'Hadir')->count();
        $terlambat = AttendanceSiswa::whereDate('attendance_date', $today)->where('type', 'Masuk')->where('status', 'Terlambat')->count();
        $sakit = AttendanceSiswa::whereDate('attendance_date', $today)->where('status', 'Sakit')->count();
        $izin = AttendanceSiswa::whereDate('attendance_date', $today)->where('status', 'Izin')->count();
        $alpa = AttendanceSiswa::whereDate('attendance_date', $today)->where('status', 'Alpa')->count();

        $stats = [
            'hadir' => $hadir + $terlambat, // Total kehadiran fisik
            'tepat_waktu' => $hadir,
            'terlambat' => $terlambat,
            'tidak_hadir' => $sakit + $izin + $alpa
        ];

        // 2. CHART MINGGUAN (STACKED)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Helper Query untuk grouping tanggal
        $getQuery = function($status, $type = null) use ($startOfWeek, $endOfWeek) {
            $q = AttendanceSiswa::select(DB::raw('DATE(attendance_date) as date'), DB::raw('count(*) as total'))
                ->whereBetween('attendance_date', [$startOfWeek, $endOfWeek]);
            
            if ($type) $q->where('type', $type);
            
            if (is_array($status)) $q->whereIn('status', $status);
            else $q->where('status', $status);

            return $q->groupBy('date')->pluck('total', 'date');
        };

        // Ambil Data 3 Kategori
        $dataTepatWaktu = $getQuery('Hadir', 'Masuk');
        $dataTerlambat = $getQuery('Terlambat', 'Masuk');
        $dataTidakHadir = $getQuery(['Sakit', 'Izin', 'Alpa']); // Gabungan ketidakhadiran

        // Format Data untuk ChartJS
        $labels = [];
        $datasetHadir = [];
        $datasetTelat = [];
        $datasetAbsen = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
            $labels[] = $startOfWeek->copy()->addDays($i)->isoFormat('dddd'); // Nama Hari
            
            $datasetHadir[] = $dataTepatWaktu[$date] ?? 0;
            $datasetTelat[] = $dataTerlambat[$date] ?? 0;
            $datasetAbsen[] = $dataTidakHadir[$date] ?? 0;
        }

        $barChartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Tepat Waktu',
                    'data' => $datasetHadir,
                    'backgroundColor' => '#10b981', // Emerald 500 (Hijau)
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Terlambat',
                    'data' => $datasetTelat,
                    'backgroundColor' => '#f59e0b', // Amber 500 (Kuning/Oranye)
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Tidak Hadir', // Sakit + Izin + Alpa
                    'data' => $datasetAbsen,
                    'backgroundColor' => '#ef4444', // Red 500 (Merah)
                    'borderRadius' => 4,
                ]
            ]
        ];

        // ... (SISA KODE SAMA: Library, Pengumuman, Prestasi) ...
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

        $announcements = Announcement::orderBy('created_at', 'desc')->limit(3)->get();
        $achievements = Achievement::with('student')->orderBy('date', 'desc')->limit(6)->get();

        return view('welcome', compact(
            'stats', 'barChartData', 
            'libraryStats', 'libraryChartData', 
            'announcements', 'achievements'
        ));
    }
}