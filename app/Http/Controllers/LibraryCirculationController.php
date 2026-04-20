<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Student;
use App\Models\Borrowing; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SchoolClass;
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
        $recentActiveLoans = Borrowing::with(['student', 'book'])
                            ->where('status', 'borrowed')
                            ->orderBy('borrow_date', 'desc')
                            ->limit(10)
                            ->get();
        
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
            try {
                $studentQuery->with('schoolClass');
            } catch (\Exception $e) {}

            $student = $studentQuery->first();

            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Anggota tidak ditemukan.']);
            }

            // 2. CEK BUKU OVERDUE (Terlambat)
            $overdueLoans = Borrowing::with('book')
                            ->where('student_id', $student->id)
                            ->where('status', 'borrowed')
                            ->where('due_date', '<', now())
                            ->get();

            $overdueTitles = $overdueLoans->map(function($loan) {
                return $loan->book ? $loan->book->title : 'Judul Tidak Diketahui';
            });

            // 3. AMBIL DETAIL BUKU YANG SEDANG DIPINJAM
            $activeLoans = Borrowing::with('book')
                                ->where('student_id', $student->id)
                                ->where('status', 'borrowed')
                                ->orderBy('borrow_date', 'desc')
                                ->get();

            $activeLoanDetails = $activeLoans->map(function($loan) {
                                    return [
                                        'title' => $loan->book ? $loan->book->title : 'Judul Tidak Diketahui',
                                        'due_date' => Carbon::parse($loan->due_date)->format('d M Y'),
                                        'is_overdue' => Carbon::now()->gt($loan->due_date)
                                    ];
                                });

            return response()->json([
                'success' => true,
                'student' => $student,
                'active_loans' => $activeLoans->count(),
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
            
            // Mengubah query untuk memprioritaskan item_code dari BookCopy jika Anda menggunakan Eksemplar
            // Namun fallback sementara ke book_code buku induk sesuai kode awal Anda
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
     * PROSES PEMINJAMAN REGULER (Maksimal 3 Buku, 1 Minggu)
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

            // LOGIKA TAMBAHAN 1: Cek batas maksimal pinjaman reguler (Maksimal 3 Buku)
            // KITA HANYA MENGHITUNG BUKU NON-PAKET (Reguler)
            $activeRegularLoansCount = Borrowing::where('student_id', $request->student_id)
                ->where('status', 'borrowed')
                ->whereHas('book', function($query) {
                    $query->where('is_textbook', false); // Abaikan buku paket (11 mapel)
                })
                ->count();

            if ($activeRegularLoansCount >= 3) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Siswa telah mencapai batas maksimal peminjaman (3 Buku Reguler).']);
            }

            // LOGIKA TAMBAHAN 2: Cek Duplikasi Judul
            $isDuplicate = Borrowing::where('student_id', $request->student_id)
                            ->where('book_id', $request->book_id)
                            ->where('status', 'borrowed')
                            ->exists();

            if ($isDuplicate) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Siswa sedang meminjam judul buku ini.']);
            }

            $book->decrement('stock');

            Borrowing::create([
                'student_id' => $request->student_id,
                'book_id' => $request->book_id,
                'borrow_date' => now(),
                'due_date' => now()->addDays(7), // Logika 1 Minggu (Sudah Benar)
                'status' => 'borrowed',
                'type' => 'regular', // Menandakan ini pinjaman biasa
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
            $scannedCode = $request->get('book_code');

            // LOGIKA BARU: Cari berdasarkan 'item_code' (Eksemplar Buku Paket/Reguler)
            $borrowing = Borrowing::with('student')
                            ->where('item_code', $scannedCode)
                            ->where('status', 'borrowed')
                            ->first();

            // Fallback cari berdasarkan 'book_code' induk (jika peminjaman reguler belum pakai eksemplar)
            if (!$borrowing) {
                $book = Book::where('book_code', $scannedCode)->first();
                if (!$book) {
                    return response()->json(['success' => false, 'message' => 'Kode Buku / Eksemplar tidak ditemukan di sistem.']);
                }

                $borrowing = Borrowing::with('student')
                                ->where('book_id', $book->id)
                                ->where('status', 'borrowed')
                                ->latest() 
                                ->first();
            } else {
                $book = Book::find($borrowing->book_id);
            }

            if (!$borrowing) {
                return response()->json(['success' => false, 'message' => 'Buku ini sedang tidak dipinjam (Stok ada di rak).']);
            }

            $dueDate = Carbon::parse($borrowing->due_date);
            $now = Carbon::now();
            $fine = 0;
            $lateDays = 0;

            if ($now->gt($dueDate)) {
                $lateDays = $now->diffInDays($dueDate);
                // Denda Rp 500 per hari
                $fine = $lateDays * 500; 
            }

             if ($request->has('check_only')) {
                DB::rollBack();
                $extraMsg = $borrowing->item_code ? " (Kode Eksemplar: {$borrowing->item_code})" : "";
                
                return response()->json([
                    'success' => true,
                    'action' => 'confirm_needed',
                    'data' => [
                        'borrowing_id' => $borrowing->id,
                        'student_name' => $borrowing->student ? $borrowing->student->name : 'Siswa Tidak Ditemukan',
                        'book_title' => $book->title . $extraMsg,
                        'borrow_date' => Carbon::parse($borrowing->borrow_date)->format('d-m-Y'),
                        'due_date' => Carbon::parse($borrowing->due_date)->format('d-m-Y'),
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
    
    /**
     * MENAMPILKAN HALAMAN DISTRIBUSI MASSAL BUKU PAKET
     */
    public function bulkBorrow()
    {
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        // Hanya panggil buku paket
        $textbooks = Book::where('is_textbook', true)->orderBy('title')->get(); 
        
        return view('library.circulation.bulk-borrow', compact('classes', 'textbooks'));
    }

    /**
     * PROSES DISTRIBUSI MASSAL BUKU PAKET (1 Tahun Ajaran)
     */
    public function storeBulk(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'class_id' => 'required|exists:classes,id',
                'book_id' => 'required|exists:books,id',
                'due_date' => 'required|date',
                'item_codes' => 'required|array' // Array: [student_id => item_code]
            ]);

            $book = Book::where('id', $request->book_id)->lockForUpdate()->first();
            $itemCodesData = array_filter($request->item_codes);
            $validCount = count($itemCodesData);

            if ($validCount == 0) {
                return back()->with('error', 'Tidak ada kode eksemplar yang dimasukkan.');
            }

            if ($book->stock < $validCount) {
                DB::rollBack();
                return back()->with('error', "Stok tidak cukup! Butuh {$validCount} buku, stok tersisa {$book->stock}.");
            }

            $borrowingsData = [];
            $now = now();
            $assignedCount = 0;

            foreach ($itemCodesData as $studentId => $itemCode) {
                // Pastikan eksemplar tidak sedang dipinjam orang lain
                $isUsed = Borrowing::where('item_code', $itemCode)->where('status', 'borrowed')->exists();
                
                if ($isUsed) {
                    DB::rollBack();
                    return back()->with('error', "Kode eksemplar {$itemCode} sedang dipinjam oleh siswa lain! Periksa fisik buku.");
                }

                // Opsional: Cek jika siswa sudah punya buku paket ini
                $hasBook = Borrowing::where('student_id', $studentId)
                            ->where('book_id', $book->id)
                            ->where('status', 'borrowed')
                            ->exists();
                
                if ($hasBook) {
                    continue; // Skip jika sudah punya agar tidak duplikat
                }

                $borrowingsData[] = [
                    'student_id' => $studentId,
                    'book_id' => $book->id,
                    'item_code' => $itemCode,
                    'borrow_date' => $now,
                    'due_date' => $request->due_date, // Sesuai isian (Akhir Tahun Ajaran)
                    'status' => 'borrowed',
                    'type' => 'textbook', // Penanda Buku Paket
                    'served_by' => Auth::id(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $assignedCount++;
            }

            if ($assignedCount > 0) {
                Borrowing::insert($borrowingsData);
                $book->decrement('stock', $assignedCount);
            }

            DB::commit();
            return back()->with('success', "{$assignedCount} buku paket berhasil didistribusikan ke kelas ini.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }
}