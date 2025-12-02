<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\LibraryVisit; // Pastikan Model ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LibraryDashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama (Buku & Anggota)
        $totalBooks = Book::count();
        $activeMembers = Student::count(); 
        
        $membersBorrowingCount = Borrowing::where('status', 'borrowed')
                                    ->distinct('student_id')
                                    ->count('student_id');

        // ---------------------------------------------------------
        // [BARU] STATISTIK KUNJUNGAN (ABSENSI KIOSK)
        // ---------------------------------------------------------
        
        // A. Pengunjung Hari Ini
        $todayVisits = LibraryVisit::whereDate('date', Carbon::today())->count();

        // B. Grafik Kunjungan (7 Hari Terakhir)
        $visitStats = LibraryVisit::select(DB::raw('DATE(date) as visit_date'), DB::raw('count(*) as total'))
                        ->where('date', '>=', Carbon::now()->subDays(6))
                        ->groupBy('visit_date')
                        ->orderBy('visit_date', 'asc')
                        ->get();
        
        $visitChartLabels = $visitStats->map(function($item) {
            return Carbon::parse($item->visit_date)->isoFormat('dddd, D MMM');
        });
        $visitChartData = $visitStats->pluck('total');

        // ---------------------------------------------------------

        // 2. Statistik Sidebar Kanan (Status Sirkulasi)
        $borrowedBooks = Borrowing::where('status', 'borrowed')->count();
        $overdueBooks = Borrowing::where('status', 'borrowed')
                            ->where('due_date', '<', Carbon::now())
                            ->count();

        // 3. Aktivitas Terkini (GABUNGAN Peminjaman + Absensi Kiosk)
        // Mengambil 5 peminjaman terakhir
        $latestBorrowings = Borrowing::with(['student', 'book'])
                            ->orderBy('updated_at', 'desc')
                            ->limit(5)
                            ->get()
                            ->map(function ($item) {
                                $item->type = 'circulation'; // Penanda tipe
                                $item->sort_time = $item->updated_at;
                                return $item;
                            });

        // Mengambil 5 kunjungan terakhir
        $latestVisits = LibraryVisit::with('student')
                            ->orderBy('date', 'desc')
                            ->orderBy('time', 'desc')
                            ->limit(5)
                            ->get()
                            ->map(function ($item) {
                                $item->type = 'visit'; // Penanda tipe
                                // Gabungkan date & time untuk sorting
                                $item->sort_time = Carbon::parse($item->date . ' ' . $item->time);
                                return $item;
                            });

        // Gabung collection, sort ulang berdasarkan waktu, dan ambil 6 teratas
        $recentActivities = $latestBorrowings->merge($latestVisits)
                            ->sortByDesc('sort_time')
                            ->take(7);

        // 4. Daftar Terlambat & Buku Populer (Tetap sama)
        $overdueList = Borrowing::with(['student', 'book'])
                            ->where('status', 'borrowed')
                            ->where('due_date', '<', Carbon::now())
                            ->orderBy('due_date', 'asc')
                            ->limit(5)
                            ->get();

        $popularBooks = Book::withCount('borrowings')
                            ->orderBy('borrowings_count', 'desc')
                            ->limit(5)
                            ->get();

        // 5. Grafik Peminjaman (Tetap sama)
        $chartQuery = DB::table('borrowings')
            ->join('students', 'borrowings.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->select('classes.name', DB::raw('count(*) as total'))
            ->groupBy('classes.name')
            ->orderBy('classes.name', 'asc')
            ->get();

        $chartLabels = $chartQuery->pluck('name');
        $chartData = $chartQuery->pluck('total');

        return view('library.dashboard', compact(
            'totalBooks', 'activeMembers', 'membersBorrowingCount',
            'todayVisits', 'visitChartLabels', 'visitChartData', // Variabel Baru
            'borrowedBooks', 'overdueBooks', 
            'recentActivities', 'overdueList', 
            'popularBooks', 'chartLabels', 'chartData'
        ));
    }
}