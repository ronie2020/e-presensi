<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CbtExam;

class SebController extends Controller
{
    /**
     * Menampilkan Halaman Landing Page SEB
     * (Halaman info jika user belum pakai SEB atau baru pemeriksaan)
     */
    public function landing(CbtExam $exam)
    {
        // PERBAIKAN:
        // Panggil 'cbt.seb_info' (Halaman Konten), BUKAN 'cbt.seb_landing' (Layout).
        // File seb_info.blade.php adalah file yang barusan kita buat (yang ada pilihan Laptop/HP).
        
        return view('cbt.seb_info', compact('exam'));
    }
}