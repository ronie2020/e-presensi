<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Student;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LibraryCirculationController extends Controller
{
    public function index()
    {
        return view('library.circulation');
    }

    /**
     * API: Cari Anggota berdasarkan NISN atau RFID
     */
    public function searchStudent(Request $request)
    {
        $query = $request->get('q');
        
        // PERBAIKAN DI SINI:
        // Gunakan 'schoolClass' (sesuai nama fungsi di Student.php), bukan 'school_class'
        $student = Student::with('schoolClass') 
                    ->withCount(['borrowings' => function($q) {
                        $q->where('status', 'borrowed');
                    }])
                    ->where('student_id', $query) // NISN
                    ->orWhere('rfid_id', $query)  // RFID
                    ->orWhere('nis', $query)
                    ->first();

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Anggota tidak ditemukan.']);
        }

        // Cek apakah ada buku yang telat
        $overdueLoans = Borrowing::where('student_id', $student->id)
                        ->where('status', 'borrowed')
                        ->where('due_date', '<', now())
                        ->get();

        return response()->json([
            'success' => true,
            'student' => $student,
            'active_loans' => $student->borrowings_count,
            'has_overdue' => $overdueLoans->count() > 0,
            'overdue_titles' => $overdueLoans->pluck('book.title')
        ]);
    }

    /**
     * API: Cari Buku berdasarkan Barcode/Kode
     */
    public function searchBook(Request $request)
    {
        $query = $request->get('q');
        
        $book = Book::where('book_code', $query)->first();

        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Buku tidak ditemukan.']);
        }

        return response()->json([
            'success' => true,
            'book' => $book,
            'is_available' => $book->stock > 0
        ]);
    }

    /**
     * PROSES PEMINJAMAN
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::find($request->book_id);

        if ($book->stock <= 0) {
            return response()->json(['success' => false, 'message' => 'Stok buku habis.']);
        }

        // Cek apakah siswa sedang meminjam buku yang sama
        $isDuplicate = Borrowing::where('student_id', $request->student_id)
                        ->where('book_id', $request->book_id)
                        ->where('status', 'borrowed')
                        ->exists();

        if ($isDuplicate) {
            return response()->json(['success' => false, 'message' => 'Siswa sedang meminjam buku judul ini.']);
        }

        DB::transaction(function () use ($request, $book) {
            // 1. Kurangi Stok
            $book->decrement('stock');

            // 2. Catat Transaksi
            Borrowing::create([
                'student_id' => $request->student_id,
                'book_id' => $request->book_id,
                'borrow_date' => now(),
                'due_date' => now()->addDays(7), // Default pinjam 7 hari
                'status' => 'borrowed',
                'served_by' => Auth::id(),
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Peminjaman berhasil dicatat.']);
    }

    /**
     * PROSES PENGEMBALIAN
     */
    public function returnBook(Request $request)
    {
        $bookCode = $request->get('book_code');

        $book = Book::where('book_code', $bookCode)->first();
        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Buku tidak terdaftar di sistem.']);
        }

        $borrowing = Borrowing::with('student')
                        ->where('book_id', $book->id)
                        ->where('status', 'borrowed')
                        ->latest() 
                        ->first();

        if (!$borrowing) {
            return response()->json(['success' => false, 'message' => 'Buku ini sedang tidak dipinjam (Stok ada di rak).']);
        }

        $dueDate = Carbon::parse($borrowing->due_date);
        $now = Carbon::now();
        $fine = 0;
        $lateDays = 0;

        if ($now->gt($dueDate)) {
            $lateDays = $now->diffInDays($dueDate);
            $fine = $lateDays * 500; 
        }

        if ($request->has('check_only')) {
            return response()->json([
                'success' => true,
                'action' => 'confirm_needed',
                'data' => [
                    'borrowing_id' => $borrowing->id,
                    'student_name' => $borrowing->student->name,
                    'borrow_date' => $borrowing->borrow_date,
                    'due_date' => $borrowing->due_date,
                    'late_days' => $lateDays,
                    'fine' => $fine
                ]
            ]);
        }

        DB::transaction(function () use ($borrowing, $book, $fine) {
            $borrowing->update([
                'status' => 'returned',
                'return_date' => now(),
                'fine_amount' => $fine,
            ]);

            $book->increment('stock');
        });

        return response()->json(['success' => true, 'message' => 'Buku berhasil dikembalikan.']);
    }
}