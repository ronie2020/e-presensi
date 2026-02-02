<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Student;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Tambahkan Log untuk debugging

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
        try {
            $query = $request->get('q');
            
            // Query yang diperbaiki (Logic OR dikurung agar akurat)
            $studentQuery = Student::withCount(['borrowings' => function($q) {
                        $q->where('status', 'borrowed');
                    }])
                    ->where(function($q) use ($query) {
                        $q->where('student_id', $query)
                          ->orWhere('rfid_id', $query)
                          ->orWhere('nis', $query);
                    });
            
            // Coba load relasi kelas dengan aman
            // (Jika nama relasi salah di model, aplikasi tidak akan crash total)
            try {
                $studentQuery->with('schoolClass');
            } catch (\Exception $e) {
                // Abaikan jika relasi schoolClass tidak ditemukan, lanjut proses
            }

            $student = $studentQuery->first();

            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Anggota tidak ditemukan.']);
            }

            // Ambil data buku yang telat (dengan load relasi book agar judulnya ada)
            $overdueLoans = Borrowing::with('book')
                            ->where('student_id', $student->id)
                            ->where('status', 'borrowed')
                            ->where('due_date', '<', now())
                            ->get();

            // Format judul buku agar aman jika relasi buku terhapus
            $overdueTitles = $overdueLoans->map(function($loan) {
                return $loan->book ? $loan->book->title : 'Judul Tidak Diketahui';
            });

            return response()->json([
                'success' => true,
                'student' => $student,
                'active_loans' => $student->borrowings_count,
                'has_overdue' => $overdueLoans->count() > 0,
                'overdue_titles' => $overdueTitles
            ]);

        } catch (\Exception $e) {
            // Jika ada error (misal salah nama tabel/kolom), kirim pesan errornya ke layar
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
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
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

            // Gunakan lockForUpdate untuk mencegah race condition (dobel klik)
            $book = Book::where('id', $request->book_id)->lockForUpdate()->first();

            if ($book->stock <= 0) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Stok buku habis.']);
            }

            // Cek duplikasi pinjaman
            $isDuplicate = Borrowing::where('student_id', $request->student_id)
                            ->where('book_id', $request->book_id)
                            ->where('status', 'borrowed')
                            ->exists();

            if ($isDuplicate) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Siswa sedang meminjam buku judul ini.']);
            }

            // Proses Transaksi
            $book->decrement('stock');

            Borrowing::create([
                'student_id' => $request->student_id,
                'book_id' => $request->book_id,
                'borrow_date' => now(),
                'due_date' => now()->addDays(7), // Default 7 hari
                'status' => 'borrowed',
                'served_by' => Auth::id(),
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Peminjaman berhasil dicatat.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal Memproses: ' . $e->getMessage()]);
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
                DB::rollBack(); // Rollback karena cuma checking
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

            // Proses Pengembalian
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
            return response()->json(['success' => false, 'message' => 'Error Pengembalian: ' . $e->getMessage()]);
        }
    }
}