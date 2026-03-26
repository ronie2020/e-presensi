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
        // ==========================================
        // 1. STATISTIK UTAMA (EXISTING)
        // ==========================================
        $totalBooks = Book::count();
        $activeMembers = Student::count(); 
        
        $membersBorrowingCount = Borrowing::where('status', 'borrowed')
                                    ->distinct('student_id')
                                    ->count('student_id');

        // ==========================================
        // 2. GRAFIK TREN 7 HARI (EXISTING)
        // ==========================================
        
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

        // Fallback label chart jika salah satu kosong
        if(count($visitChartLabels) == 0 && count($loanChartLabels) > 0) {
            $visitChartLabels = $loanChartLabels;
        }

        // ==========================================
        // 3. SIRKULASI & AKTIVITAS (EXISTING)
        // ==========================================
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

        // ==========================================
        // 4. GRAFIK PER KELAS (EXISTING - WAJIB ADA)
        // ==========================================
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

        // ==========================================
        // 5. E-BOOK (EXISTING)
        // ==========================================
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

        // ==========================================
        // 6. ANALITIK JAM SIBUK & STOK (TAMBAHAN)
        // ==========================================
        
        // Analitik Jam Kunjungan (untuk grafik baru)
        $busyHoursStats = LibraryVisit::select(DB::raw('HOUR(time) as hour'), DB::raw('count(*) as count'))
                            ->groupBy('hour')
                            ->orderBy('hour')
                            ->get();
        
        $busyHoursLabels = $busyHoursStats->map(fn($item) => sprintf('%02d:00', $item->hour));
        $busyHoursData = $busyHoursStats->pluck('count');

        // Widget Stok Menipis (untuk sidebar)
        $attentionBooks = Book::where('stock', '<=', 0)->take(5)->get();

        return view('library.dashboard', compact(
            'totalBooks', 'activeMembers', 'membersBorrowingCount',
            'todayVisits', 'visitChartLabels', 'visitChartData',
            'borrowedBooks', 'overdueBooks', 
            'recentActivities', 
            'popularBooks', 
            'loanChartLabels', 'loanChartData', 
            'classChartLabels', 'classChartData', 
            'ebookReadsThisMonth', 'popularEbooks',
            'busyHoursLabels', 'busyHoursData', 
            'attentionBooks' 
        ));
    }

    /**
     * Method AJAX untuk Cek Status Siswa (DIPERBAIKI)
     */
    public function checkStudent(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json(['success' => false, 'message' => 'Input kosong']);
        }

        // PERBAIKAN: Memasukkan student_id, rfid_id, dan nis agar seragam dengan fungsi Sirkulasi
        // Sehingga ketika di-scan pakai Barcode, siswa tetap terbaca
        $student = Student::with('schoolClass') 
                    ->where('student_id', $query)
                    ->orWhere('rfid_id', $query)
                    ->orWhere('nis', $query)
                    ->orWhere('nisn', $query)
                    ->orWhere('name', 'like', "%{$query}%")
                    ->first();

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan']);
        }

        $activeLoans = Borrowing::where('student_id', $student->id)
                        ->where('status', 'borrowed')
                        ->count();

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