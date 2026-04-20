<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Book;
use App\Models\SchoolClass; 
use App\Models\Borrowing;  
use Barryvdh\DomPDF\Facade\Pdf;

class LibraryToolsController extends Controller
{
    /**
     * Halaman Utama Tools
     */
    public function index()
    {
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        // TAMBAHAN: Ambil data buku untuk dropdown opsi cetak label per buku
        $books = Book::orderBy('title', 'asc')->get(['id', 'title', 'book_code', 'stock']);
        
        return view('library.tools.index', compact('classes', 'books'));
    }

    /**
     * Preview & Cetak Kartu Anggota (Support Satuan & Satu Kelas)
     */
    public function printCard(Request $request)
    {
        $mode = $request->input('mode', 'single'); 
        $students = collect(); 

        if ($mode === 'single') {
            $request->validate(['nisn' => 'required']);
            
            $student = Student::with('schoolClass')
                        ->where('student_id', $request->nisn) 
                        ->orWhere('nis', $request->nisn)      
                        ->first();

            if ($student) {
                $students->push($student); 
            }
        } 
        elseif ($mode === 'class') {
            $request->validate(['class_id' => 'required']);
            
            $students = Student::with('schoolClass')
                        ->where('class_id', $request->class_id)
                        ->orderBy('name', 'asc')
                        ->get();
        }

        if ($students->isEmpty()) {
            return response()->stream(function() use ($mode) {
                $msg = $mode === 'class' ? 'Kelas ini belum memiliki data siswa.' : 'NISN/NIS tidak ditemukan.';
                echo '<!DOCTYPE html><html lang="id"><head><title>Data Tidak Ditemukan</title><meta name="viewport" content="width=device-width, initial-scale=1.0"><script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script><style>body { font-family: "Segoe UI", sans-serif; background: #f1f5f9; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }</style></head><body><script>document.addEventListener("DOMContentLoaded", function() {Swal.fire({icon: "error",title: "Data Kosong",text: "'.$msg.'",confirmButtonText: "Tutup",confirmButtonColor: "#ef4444",allowOutsideClick: false}).then((result) => {window.close();});});</script></body></html>';
            });
        }

        return view('library.tools.print-card', compact('students'));
    }

    /**
     * Cetak Label Buku (Barcode & Punggung Eksemplar Unik)
     */
    public function printBookLabel(Request $request)
    {
        $copies = collect();

        // Opsi 1: Berdasarkan Kode Buku Manual (dipisah koma)
        if ($request->filled('book_codes')) {
            $codes = array_map('trim', explode(',', $request->book_codes));
            $copies = \App\Models\BookCopy::with(['book.category'])
                        ->whereIn('copy_code', $codes)
                        ->get()
                        ->sortBy('copy_code');
        } 
        // Opsi 2: Berdasarkan Judul Buku (Sangat berguna untuk Buku Paket)
        elseif ($request->filled('book_id')) {
            $copies = \App\Models\BookCopy::with(['book.category'])
                        ->where('book_id', $request->book_id)
                        ->get()
                        ->sortBy('copy_code'); // Mengurutkan barcode 01, 02, 03...
        }
        // Opsi 3: Berdasarkan Jumlah Eksemplar Terakhir yang Dibuat (Default)
        elseif ($request->filled('limit')) {
            $copies = \App\Models\BookCopy::with(['book.category'])
                        ->latest()
                        ->take($request->limit)
                        ->get()
                        ->reverse(); 
        }
        else {
            // Default fallback
            $copies = \App\Models\BookCopy::with(['book.category'])
                        ->latest()
                        ->take(10)
                        ->get()
                        ->reverse(); 
        }
        
        // --- LOGIKA PENANGANAN DATA KOSONG ---
        if ($copies->isEmpty()) {
            return response()->stream(function() {
                echo '
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <title>Data Buku Kosong</title>
                    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <style>body { font-family: "Segoe UI", sans-serif; background: #f1f5f9; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }</style>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            icon: "warning",
                            title: "Eksemplar Tidak Ditemukan",
                            text: "Cek kembali data buku yang Anda masukkan atau pastikan Anda sudah men-generate fisik buku.",
                            confirmButtonText: "Tutup",
                            confirmButtonColor: "#f59e0b"
                        }).then(() => { window.close(); });
                    </script>
                </body>
                </html>
                ';
            });
        }

        return view('library.tools.print-book-label', compact('copies'));
    }
    /**
     * Generate Laporan PDF
     */
    public function generateReport(Request $request)
    {
        $type = $request->type; 
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        $data = [];
        $title = "";

        if ($type == 'monthly') {
            $monthName = date('F', mktime(0, 0, 0, $month, 10));
            $title = "Laporan Sirkulasi - " . $monthName . " " . $year;
            
            $data = Borrowing::whereMonth('borrow_date', $month)
                        ->whereYear('borrow_date', $year)
                        ->with(['student', 'book'])
                        ->orderBy('borrow_date', 'desc')
                        ->get();
        } 
        elseif ($type == 'top_books') {
            $title = "Laporan Buku Terpopuler (Top Borrowed)";            
            $data = Book::withCount('borrowings') 
                        ->orderBy('borrowings_count', 'desc')
                        ->take(20)
                        ->get();
        }

        $pdf = Pdf::loadView('library.reports.pdf-template', compact('data', 'title', 'type'));
        return $pdf->download('Laporan_' . $type . '_' . date('Ymd_His') . '.pdf');
    }
    
    /**
     * API Ambil Siswa Berdasarkan Kelas (Untuk Bulk Borrow)
     */
    public function getStudentsByClass($class_id)
    {
        try {
            $students = Student::where('class_id', $class_id)
                            ->orderBy('name', 'asc')
                            ->get(['id', 'name', 'nisn', 'student_id']); 
            
            return response()->json([
                'success' => true,
                'students' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * 1. Halaman Utama Surat Bebas Pustaka
     */
    public function bebasPustaka()
    {
        return view('library.tools.bebas-pustaka');
    }

    /**
     * 2. API Cek Kelayakan Bebas Pustaka
     */
    public function checkClearanceApi(Request $request)
    {
        $query = $request->q;
        
        $student = Student::with('schoolClass')
                    ->where('student_id', $query)
                    ->orWhere('nis', $query)
                    ->orWhere('nisn', $query)
                    ->orWhere('name', 'like', "%{$query}%")
                    ->first();

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Data Siswa tidak ditemukan.']);
        }

        $activeLoans = Borrowing::with('book')
                        ->where('student_id', $student->id)
                        ->where('status', 'borrowed')
                        ->get()
                        ->map(function($loan) {
                            return [
                                'book_title' => optional($loan->book)->title ?? 'Buku Tidak Dikenal',
                                'item_code'  => $loan->item_code ?? null,
                                'is_late'    => \Carbon\Carbon::now()->gt($loan->due_date)
                            ];
                        });

        return response()->json([
            'success' => true,
            'student' => $student,
            'active_loans' => $activeLoans
        ]);
    }

    /**
     * 3. Menampilkan Halaman Cetak Web-to-Print Bebas Pustaka
     */
    public function printClearance($id)
    {
        $student = Student::with('schoolClass')->findOrFail($id);

        $hasLoans = Borrowing::where('student_id', $student->id)
                        ->where('status', 'borrowed')
                        ->exists();

        if ($hasLoans) {
            return redirect()->route('library.tools.bebas_pustaka')
                             ->with('error', 'Akses Ditolak: ' . $student->name . ' masih memiliki tanggungan peminjaman buku!');
        }

        return view('library.tools.print-bebas-pustaka', compact('student'));
    }
}