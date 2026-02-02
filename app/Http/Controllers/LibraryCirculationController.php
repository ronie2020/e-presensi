<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Student;
use App\Models\Borrowing; // Pastikan pakai model Borrowing
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LibraryCirculationController extends Controller
{
    /**
     * MENAMPILKAN HALAMAN SIRKULASI
     */
    public function index()
    {
        // PERBAIKAN UTAMA DI SINI:
        // Kita ambil 10 data peminjaman terakhir yang statusnya 'borrowed'
        // agar variabel $recentActiveLoans tersedia di view.
        
        $recentActiveLoans = Borrowing::with(['student', 'book'])
                            ->where('status', 'borrowed')
                            ->orderBy('borrow_date', 'desc')
                            ->limit(10)
                            ->get();

        // Kirim variabel tersebut ke view menggunakan compact
        return view('library.circulation', compact('recentActiveLoans'));
    }

    /**
     * API: Cari Anggota berdasarkan NISN atau RFID
     */
    public function searchStudent(Request $request)
    {
        try {
            $query = $request->get('q');
            
            // 1. CARI SISWA
            $studentQuery = Student::where('student_id', $query)
                        ->orWhere('rfid_id', $query)
                        ->orWhere('nis', $query);

            // Coba load relasi schoolClass dengan aman
            try {
                $studentQuery->with('schoolClass');
            } catch (\Exception $e) {}

            // Coba load count borrowings dengan aman
            try {
                $studentQuery->withCount(['borrowings' => function($q) {
                    $q->where('status', 'borrowed');
                }]);
            } catch (\Exception $e) {
                // Jika relasi borrowings belum ada di model Student, kembalikan pesan jelas
                return response()->json([
                    'success' => false, 
                    'message' => 'Error Code: Relasi borrowings() tidak ditemukan di Model Student.php'
                ]);
            }

            $student = $studentQuery->first();

            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Anggota tidak ditemukan.']);
            }

            // 2. CEK BUKU OVERDUE
            $overdueLoans = Borrowing::with('book')
                            ->where('student_id', $student->id)
                            ->where('status', 'borrowed')
                            ->where('due_date', '<', now())
                            ->get();

            $overdueTitles = $overdueLoans->map(function($loan) {
                return $loan->book ? $loan->book->title : 'Judul Tidak Diketahui';
            });

            // 3. AMBIL DETAIL BUKU YANG SEDANG DIPINJAM
            $activeLoanDetails = Borrowing::with('book')
                                ->where('student_id', $student->id)
                                ->where('status', 'borrowed')
                                ->orderBy('borrow_date', 'desc')
                                ->get()
                                ->map(function($loan) {
                                    return [
                                        'title' => $loan->book ? $loan->book->title : 'Judul Tidak Diketahui',
                                        'due_date' => Carbon::parse($loan->due_date)->format('d M Y'),
                                        'is_overdue' => Carbon::now()->gt($loan->due_date)
                                    ];
                                });

            return response()->json([
                'success' => true,
                'student' => $student,
                'active_loans' => $student->borrowings_count ?? 0,
                'active_loan_details' => $activeLoanDetails,
                'has_overdue' => $overdueLoans->count() > 0,
                'overdue_titles' => $overdueTitles
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error Server: ' . $e->getMessage()
            ], 200);
        }
    }

    /**
     * API: Cari Buku berdasarkan Barcode/Kode
     */
    public function searchBook(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error Buku: ' . $e->getMessage()]);
        }
    }

    /**
     * PROSES PEMINJAMAN
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'student_id' => 'required|exists:students,id',
                'book_id' => 'required|exists:books,id',
            ]);

            $book = Book::where('id', $request->book_id)->lockForUpdate()->first();

            if ($book->stock <= 0) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Stok buku habis.']);
            }

            // Cek Duplikasi
            $isDuplicate = Borrowing::where('student_id', $request->student_id)
                            ->where('book_id', $request->book_id)
                            ->where('status', 'borrowed')
                            ->exists();

            if ($isDuplicate) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Siswa sedang meminjam buku judul ini.']);
            }

            $book->decrement('stock');

            Borrowing::create([
                'student_id' => $request->student_id,
                'book_id' => $request->book_id,
                'borrow_date' => now(),
                'due_date' => now()->addDays(7),
                'status' => 'borrowed',
                'served_by' => Auth::id(),
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Peminjaman berhasil dicatat.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal Proses: ' . $e->getMessage()]);
        }
    }

    /**
     * PROSES PENGEMBALIAN
     */
    public function returnBook(Request $request)
    {
        DB::beginTransaction();
        try {
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
                DB::rollBack();
                return response()->json([
                    'success' => true,
                    'action' => 'confirm_needed',
                    'data' => [
                        'borrowing_id' => $borrowing->id,
                        'student_name' => $borrowing->student ? $borrowing->student->name : 'Siswa Tidak Ditemukan',
                        'borrow_date' => $borrowing->borrow_date,
                        'due_date' => $borrowing->due_date,
                        'late_days' => $lateDays,
                        'fine' => $fine
                    ]
                ]);
            }

            $borrowing->update([
                'status' => 'returned',
                'return_date' => now(),
                'fine_amount' => $fine,
            ]);

            $book->increment('stock');
            
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Buku berhasil dikembalikan.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error Kembali: ' . $e->getMessage()]);
        }
    }
}