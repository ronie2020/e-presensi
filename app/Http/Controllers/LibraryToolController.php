<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Book;
use App\Models\Loan; 
use Barryvdh\DomPDF\Facade\Pdf;
// HAPUS: use SimpleSoftwareIO\QrCode\Facades\QrCode; (Penyebab Error)

class LibraryToolController extends Controller
{
    /**
     * Menampilkan halaman menu Tools (Pusat Cetak & Laporan)
     */
    public function index()
    {
        return view('library.tools.index');
    }

    /**
     * Preview & Cetak Kartu Anggota
     */
    public function printMemberCard(Request $request)
    {
        $request->validate(['nisn' => 'required']);
        
        $student = Student::where('student_id', $request->nisn)->first();

        if (!$student) {
            // Tampilkan SweetAlert2 jika siswa tidak ditemukan
            return response()->stream(function() {
                echo '
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <title>Siswa Tidak Ditemukan</title>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <style>body { font-family: "Segoe UI", sans-serif; background: #f1f5f9; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }</style>
                </head>
                <body>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: "error",
                                title: "Siswa Tidak Ditemukan!",
                                text: "NISN yang Anda masukkan tidak terdaftar dalam database.",
                                confirmButtonText: "Tutup Window",
                                confirmButtonColor: "#ef4444",
                                allowOutsideClick: false,
                                allowEscapeKey: false
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

        // HAPUS: Bagian generate QR Code lokal yang menyebabkan error
        // $qrcode = QrCode::size(100)->generate($student->student_id);

        // Kirim data siswa saja, QR Code ditangani oleh View (Blade) via API
        return view('library.tools.print-card', compact('student'));
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
            $books = Book::whereIn('book_code', $codes)->get();
        } 
        // Opsi 2: Berdasarkan Jumlah Buku Terakhir (Default)
        elseif ($request->filled('limit')) {
            $books = Book::latest()->take($request->limit)->get();
        }
        else {
            // Default fallback
            $books = Book::latest()->take(10)->get();
        }
        
        // --- LOGIKA PENANGANAN DATA KOSONG DENGAN SWEETALERT2 ---
        if ($books->isEmpty()) {
            return response()->stream(function() {
                echo '
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <title>Data Buku Kosong</title>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <style>body { font-family: "Segoe UI", sans-serif; background: #f1f5f9; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }</style>
                </head>
                <body>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: "warning",
                                title: "Data Buku Tidak Ditemukan",
                                text: "Tidak ada buku yang cocok dengan kriteria pencarian Anda. Pastikan kode buku benar atau data sudah diinput.",
                                confirmButtonText: "Mengerti & Tutup",
                                confirmButtonColor: "#f59e0b",
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

        // Fix: Pastikan relasi kategori dimuat agar tidak error di view
        $books->load('category'); 

        return view('library.tools.print-book-label', compact('books'));
    }

    /**
     * Generate Laporan PDF
     */
    public function generateReport(Request $request)
    {
        $type = $request->type; // 'monthly', 'top_books'
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        $data = [];
        $title = "";

        if ($type == 'monthly') {
            $monthName = date('F', mktime(0, 0, 0, $month, 10));
            $title = "Laporan Sirkulasi - " . $monthName . " " . $year;
            
            // Mengambil data peminjaman
            $data = Loan::whereMonth('loan_date', $month)
                        ->whereYear('loan_date', $year)
                        ->with(['student', 'book'])
                        ->get();
        } 
        elseif ($type == 'top_books') {
            $title = "Laporan Buku Terpopuler (Top Borrowed)";
            // Mengambil buku dengan jumlah peminjaman terbanyak
            $data = Book::withCount('loans')
                        ->orderBy('loans_count', 'desc')
                        ->take(20)
                        ->get();
        }

        // Cek jika data kosong, tampilkan pesan PDF kosong atau alert
        if ($data->isEmpty() && $type == 'monthly') {
             // Opsional: Jika ingin alert juga untuk laporan, bisa pakai cara stream di atas.
             // Tapi biasanya laporan PDF tetap digenerate meski kosong (hanya tabel kosong).
        }

        $pdf = Pdf::loadView('library.reports.pdf-template', compact('data', 'title', 'type'));
        
        return $pdf->download('Laporan_Perpustakaan_' . date('Ymd_His') . '.pdf');
    }
}