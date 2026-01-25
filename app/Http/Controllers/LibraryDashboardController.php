<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\LibraryVisit; 
use App\Models\EbookRead; 
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

        // 2. STATISTIK KUNJUNGAN FISIK (KIOSK)
        $todayVisits = 0;
        $visitChartLabels = [];
        $visitChartData = [];

        try {
            if (class_exists(LibraryVisit::class)) {
                // A. Pengunjung Hari Ini
                $todayVisits = LibraryVisit::whereDate('date', Carbon::today())->count();

                // B. Grafik Kunjungan (7 Hari Terakhir)
                $visitStats = LibraryVisit::select(DB::raw('DATE(date) as visit_date'), DB::raw('count(*) as total'))
                                ->where('date', '>=', Carbon::now()->subDays(6))
                                ->groupBy('visit_date')
                                ->orderBy('visit_date', 'asc')
                                ->get();
                
                $visitChartLabels = $visitStats->map(fn($item) => Carbon::parse($item->visit_date)->format('d M'));
                $visitChartData = $visitStats->pluck('total');
            }
        } catch (\Exception $e) {}

        // 3. STATISTIK SIRKULASI (PEMINJAMAN FISIK)
        $borrowedBooks = Borrowing::where('status', 'borrowed')->count();
        $overdueBooks  = Borrowing::where('status', 'borrowed')
                            ->where('due_date', '<', Carbon::now())
                            ->count();

        $recentActivities = Borrowing::with(['student', 'book'])
                            ->latest('updated_at') // Bisa created_at atau updated_at
                            ->take(7)
                            ->get();

        $overdueList = Borrowing::with(['student', 'book'])
                            ->where('status', 'borrowed')
                            ->where('due_date', '<', Carbon::now())
                            ->orderBy('due_date', 'asc')
                            ->limit(5)
                            ->get();

        // Buku Populer (FISIK)
        $popularBooks = Book::withCount('borrowings')
                            ->orderBy('borrowings_count', 'desc')
                            ->limit(5)
                            ->get();

        // Grafik Peminjaman per Kelas
        $chartQuery = DB::table('borrowings')
            ->join('students', 'borrowings.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->select('classes.name', DB::raw('count(*) as total'))
            ->groupBy('classes.name')
            ->orderBy('classes.name', 'asc')
            ->get();

        $chartLabels = $chartQuery->pluck('name');
        $chartData = $chartQuery->pluck('total');

        // ---------------------------------------------------------
        // 4. [BARU] STATISTIK LITERASI DIGITAL (E-BOOK)
        // ---------------------------------------------------------
        $ebookReadsThisMonth = 0;
        $popularEbooks = collect([]);

        try {
            if (class_exists(EbookRead::class)) {
                // A. Total Bacaan E-Book Bulan Ini
                $ebookReadsThisMonth = EbookRead::whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->count();
                
                // B. 5 E-Book Terpopuler Bulan Ini
                $popularEbooks = Book::withCount(['ebookReads' => function($query) {
                                        $query->whereMonth('created_at', Carbon::now()->month)
                                              ->whereYear('created_at', Carbon::now()->year);
                                    }])
                                    ->having('ebook_reads_count', '>', 0)
                                    ->orderByDesc('ebook_reads_count')
                                    ->take(5)
                                    ->get();
            }
        } catch (\Exception $e) {}

        return view('library.dashboard', compact(
            'totalBooks', 'activeMembers', 'membersBorrowingCount',
            'todayVisits', 'visitChartLabels', 'visitChartData',
            'borrowedBooks', 'overdueBooks', 
            'recentActivities', 'overdueList', 
            'popularBooks', 'chartLabels', 'chartData',
            
            // Variabel Baru E-Book
            'ebookReadsThisMonth', 'popularEbooks'
        ));
    }
}