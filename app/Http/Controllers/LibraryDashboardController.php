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

        // ---------------------------------------------------------
        // 2. DATA UNTUK GRAFIK UTAMA (TREN 7 HARI TERAKHIR)
        // ---------------------------------------------------------
        
        // A. Data Kunjungan (Visit Trend)
        $todayVisits = 0;
        $visitChartLabels = [];
        $visitChartData = [];

        try {
            if (class_exists(LibraryVisit::class)) {
                $todayVisits = LibraryVisit::whereDate('date', Carbon::today())->count();

                $visitStats = LibraryVisit::select(DB::raw('DATE(date) as visit_date'), DB::raw('count(*) as total'))
                                ->where('date', '>=', Carbon::now()->subDays(6))
                                ->groupBy('visit_date')
                                ->orderBy('visit_date', 'asc')
                                ->get();
                
                $visitChartLabels = $visitStats->map(fn($item) => Carbon::parse($item->visit_date)->format('d M'));
                $visitChartData = $visitStats->pluck('total');
            }
        } catch (\Exception $e) {}

        // B. Data Peminjaman (Loan Trend)
        $loanStats = Borrowing::select(DB::raw('DATE(created_at) as loan_date'), DB::raw('count(*) as total'))
                        ->where('created_at', '>=', Carbon::now()->subDays(6))
                        ->groupBy('loan_date')
                        ->orderBy('loan_date', 'asc')
                        ->get();

        $loanChartLabels = $loanStats->map(fn($item) => Carbon::parse($item->loan_date)->format('d M'));
        $loanChartData = $loanStats->pluck('total');

        // Fallback label chart
        if(count($visitChartLabels) == 0 && count($loanChartLabels) > 0) {
            $visitChartLabels = $loanChartLabels;
        }

        // ---------------------------------------------------------
        // 3. STATISTIK SIRKULASI & AKTIVITAS
        // ---------------------------------------------------------
        $borrowedBooks = Borrowing::where('status', 'borrowed')->count();
        $overdueBooks  = Borrowing::where('status', 'borrowed')
                            ->where('due_date', '<', Carbon::now())
                            ->count();

        $recentActivities = Borrowing::with(['student', 'book'])
                            ->latest('updated_at')
                            ->take(7)
                            ->get();

        // Buku Fisik Populer
        $popularBooks = Book::withCount('borrowings')
                            ->orderBy('borrowings_count', 'desc')
                            ->limit(5)
                            ->get();

        // ---------------------------------------------------------
        // 4. GRAFIK PER KELAS
        // ---------------------------------------------------------
        $classChartQuery = DB::table('borrowings')
            ->join('students', 'borrowings.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->select('classes.name', DB::raw('count(*) as total'))
            ->groupBy('classes.name')
            ->orderBy('classes.name', 'asc')
            ->limit(10)
            ->get();

        $classChartLabels = $classChartQuery->pluck('name');
        $classChartData = $classChartQuery->pluck('total');

        // ---------------------------------------------------------
        // 5. STATISTIK LITERASI DIGITAL (E-BOOK)
        // ---------------------------------------------------------
        $ebookReadsThisMonth = 0;
        $popularEbooks = collect([]);

        try {
            if (class_exists(EbookRead::class)) {
                $ebookReadsThisMonth = EbookRead::whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->count();
                
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
            'recentActivities', 
            'popularBooks', 
            'loanChartLabels', 'loanChartData', 
            'classChartLabels', 'classChartData', 
            'ebookReadsThisMonth', 'popularEbooks'
        ));
    }

    /**
     * Method Baru: Handle AJAX Request untuk Cek Status Siswa
     * Route: POST /library/check-student (Sesuaikan route Anda ke method ini)
     */
    public function checkStudent(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json(['success' => false, 'message' => 'Input kosong']);
        }

        // Cari siswa berdasarkan Nama, NISN, atau ID (Scan Barcode biasanya mengirim NISN/ID)
        // Pastikan relasi 'schoolClass' (atau 'class') diload
        $student = Student::with('schoolClass') 
                    ->where('name', 'like', "%{$query}%")
                    ->orWhere('nisn', 'like', "%{$query}%") // Asumsi kolom NISN
                    ->first();

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan']);
        }

        // Hitung peminjaman aktif
        $activeLoans = Borrowing::where('student_id', $student->id)
                        ->where('status', 'borrowed')
                        ->count();

        // Cek apakah ada yang terlambat (Overdue)
        $hasOverdue = Borrowing::where('student_id', $student->id)
                        ->where('status', 'borrowed')
                        ->where('due_date', '<', Carbon::now())
                        ->exists();

        return response()->json([
            'success' => true,
            'student' => $student,
            'active_loans' => $activeLoans,
            'has_overdue' => $hasOverdue
        ]);
    }
}