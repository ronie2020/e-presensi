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
        
        // --- 1. STATISTIK HARIAN (KOTAK INFO) ---
        // PERBAIKAN UTAMA: Tambahkan ->distinct('student_id') agar siswa yang scan 2x tidak dihitung ganda
        
        $hadir = AttendanceSiswa::whereDate('attendance_date', $today)
                    ->whereIn('status', ['Hadir', 'hadir', 'Present'])
                    ->distinct('student_id') // <--- PENTING: Hitung siswa unik saja
                    ->count('student_id');
                    
        $terlambat = AttendanceSiswa::whereDate('attendance_date', $today)
                    ->whereIn('status', ['Terlambat', 'terlambat', 'Late'])
                    ->distinct('student_id') // <--- PENTING
                    ->count('student_id');
                    
        $sakit = AttendanceSiswa::whereDate('attendance_date', $today)
                    ->whereIn('status', ['Sakit', 'sakit'])
                    ->distinct('student_id')
                    ->count('student_id');
                    
        $izin = AttendanceSiswa::whereDate('attendance_date', $today)
                    ->whereIn('status', ['Izin', 'izin'])
                    ->distinct('student_id')
                    ->count('student_id');
                    
        $alpa = AttendanceSiswa::whereDate('attendance_date', $today)
                    ->whereIn('status', ['Alpa', 'alpa', 'Alpha', 'alpha'])
                    ->distinct('student_id')
                    ->count('student_id');

        $stats = [
            'hadir'       => $hadir + $terlambat, // Total Siswa di Sekolah (Tepat Waktu + Terlambat)
            'tepat_waktu' => $hadir,
            'terlambat'   => $terlambat,
            'tidak_hadir' => $sakit + $izin + $alpa
        ];

        // --- 2. CHART MINGGUAN (STACKED) ---
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Helper Query (Juga diperbaiki dengan distinct agar grafik akurat)
        $getQuery = function($statusList) use ($startOfWeek, $endOfWeek) {
            if (!is_array($statusList)) {
                $statusList = [$statusList];
            }

            return AttendanceSiswa::select(DB::raw('DATE(attendance_date) as date'), DB::raw('count(distinct student_id) as total')) // <--- Pakai count(distinct student_id)
                ->whereBetween('attendance_date', [$startOfWeek, $endOfWeek])
                ->whereIn('status', $statusList)
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

        for ($i = 0; $i < 7; $i++) {
            $currentDay = $startOfWeek->copy()->addDays($i);
            $dateKey = $currentDay->format('Y-m-d');
            
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
                    'backgroundColor' => '#10b981', 
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Terlambat',
                    'data' => $datasetTelat,
                    'backgroundColor' => '#f59e0b',
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Tidak Hadir',
                    'data' => $datasetAbsen,
                    'backgroundColor' => '#ef4444',
                    'borderRadius' => 4,
                ]
            ]
        ];

        // --- 3. DATA LAINNYA ---
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