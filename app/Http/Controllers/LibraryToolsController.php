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
        // 1: Ambil data kelas untuk mengisi dropdown "Pilih Kelas" di halaman Index
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        
        return view('library.tools.index', compact('classes'));
    }

    /**
     * Preview & Cetak Kartu Anggota (Support Satuan & Satu Kelas)
     */
    public function printCard(Request $request)
    {
        $mode = $request->input('mode', 'single'); 
        $students = collect(); // Koleksi kosong untuk menampung hasil

        // LOGIKA 1: CETAK SATUAN
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
        // 2: LOGIKA CETAK SATU KELAS
        elseif ($mode === 'class') {
            $request->validate(['class_id' => 'required']);
            
            $students = Student::with('schoolClass')
                        ->where('class_id', $request->class_id)
                        ->orderBy('name', 'asc')
                        ->get();
        }

        // JIKA DATA KOSONG (Tampilkan SweetAlert Error)
        if ($students->isEmpty()) {
            return response()->stream(function() use ($mode) {
                $msg = $mode === 'class' ? 'Kelas ini belum memiliki data siswa.' : 'NISN/NIS tidak ditemukan.';
                echo '
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <title>Data Tidak Ditemukan</title>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <style>body { font-family: "Segoe UI", sans-serif; background: #f1f5f9; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }</style>
                </head>
                <body>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: "error",
                                title: "Data Kosong",
                                text: "'.$msg.'",
                                confirmButtonText: "Tutup",
                                confirmButtonColor: "#ef4444",
                                allowOutsideClick: false
                            }).then((result) => {
                                window.close();
                            });
                        });
                    </script>
                </body>
                </html>
                ';
            });
        }

        // Kirim variable $students (jamak) ke view print-card
        return view('library.tools.print-card', compact('students'));
    }

    /**
     * Cetak Label Buku (Barcode & Punggung)
     */
    public function printBookLabel(Request $request)
    {
        $books = collect();

        // Opsi 1: Berdasarkan Kode Buku Manual (dipisah koma)
        if ($request->filled('book_codes')) {
            $codes = array_map('trim', explode(',', $request->book_codes));
            $books = Book::with('category')->whereIn('book_code', $codes)->get();
        } 
        // Opsi 2: Berdasarkan Jumlah Buku Terakhir (Default)
        elseif ($request->filled('limit')) {
            $books = Book::with('category')->latest()->take($request->limit)->get();
        }
        else {
            // Default fallback
            $books = Book::with('category')->latest()->take(10)->get();
        }
        
        // --- LOGIKA PENANGANAN DATA KOSONG ---
        if ($books->isEmpty()) {
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
                            title: "Buku Tidak Ditemukan",
                            text: "Cek kembali kode buku yang Anda masukkan.",
                            confirmButtonText: "Tutup",
                            confirmButtonColor: "#f59e0b"
                        }).then(() => { window.close(); });
                    </script>
                </body>
                </html>
                ';
            });
        }

        return view('library.tools.print-book-label', compact('books'));
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

        // PERBAIKAN 3: Gunakan Model Borrowing dan kolom borrow_date
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
     * 1. Halaman Utama Surat Bebas Pustaka (Form Pencarian)
     */
    public function bebasPustaka()
    {
        return view('library.tools.bebas-pustaka');
    }

    /**
     * 2. API Cek Kelayakan Bebas Pustaka (Dipanggil oleh Javascript via fetch)
     */
    public function checkClearanceApi(Request $request)
    {
        $query = $request->q;
        
        // Cari Siswa berdasarkan NISN, NIS, ID, atau Nama
        $student = Student::with('schoolClass')
                    ->where('student_id', $query)
                    ->orWhere('nis', $query)
                    ->orWhere('nisn', $query)
                    ->orWhere('name', 'like', "%{$query}%")
                    ->first();

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Data Siswa tidak ditemukan.']);
        }

        // Cari pinjaman buku siswa ini yang statusnya masih 'borrowed'
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

        // Validasi Ekstra Keamanan: Pastikan siswa benar-benar tidak punya pinjaman
        $hasLoans = Borrowing::where('student_id', $student->id)
                        ->where('status', 'borrowed')
                        ->exists();

        if ($hasLoans) {
            // Jika siswa iseng menebak URL id, sistem akan menolak
            return abort(403, 'Akses Ditolak: Siswa masih memiliki tanggungan peminjaman buku!');
        }

        // Arahkan ke file Blade cetak yang telah disesuaikan dengan format SPPD
        return view('library.reports.print-bebas-pustaka', compact('student'));
    }
}