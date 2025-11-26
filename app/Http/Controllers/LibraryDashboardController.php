<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LibraryDashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama
        $totalBooks = Book::count();
        $activeMembers = Student::count(); 
        
        // --- TAMBAHAN BARU: STATISTIK ANGGOTA ---
        // Menghitung jumlah siswa yang sedang meminjam buku (distinct/unik)
        $membersBorrowingCount = Borrowing::where('status', 'borrowed')
                                    ->distinct('student_id')
                                    ->count('student_id');

        // Menghitung jumlah siswa yang diblokir (karena punya buku terlambat)
        $blockedMembersCount = Borrowing::where('status', 'borrowed')
                                    ->where('due_date', '<', Carbon::now())
                                    ->distinct('student_id')
                                    ->count('student_id');
        // ----------------------------------------

        // 2. Statistik Sidebar Kanan
        $borrowedBooks = Borrowing::where('status', 'borrowed')->count();
        $overdueBooks = Borrowing::where('status', 'borrowed')
                            ->where('due_date', '<', Carbon::now())
                            ->count();

        // 3. Aktivitas Terkini
        $recentActivities = Borrowing::with(['student', 'book'])
                            ->orderBy('updated_at', 'desc')
                            ->limit(6)
                            ->get();

        // 4. Daftar Terlambat
        $overdueList = Borrowing::with(['student', 'book'])
                            ->where('status', 'borrowed')
                            ->where('due_date', '<', Carbon::now())
                            ->orderBy('due_date', 'asc')
                            ->limit(5)
                            ->get();

        // 5. Buku Populer
        $popularBooks = Book::withCount('borrowings')
                            ->orderBy('borrowings_count', 'desc')
                            ->limit(5)
                            ->get();

        // 6. Grafik
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
            'totalBooks', 'activeMembers', 
            'membersBorrowingCount', 'blockedMembersCount', // Kirim variabel baru
            'borrowedBooks', 'overdueBooks', 
            'recentActivities', 'overdueList', 
            'popularBooks', 'chartLabels', 'chartData'
        ));
    }
}