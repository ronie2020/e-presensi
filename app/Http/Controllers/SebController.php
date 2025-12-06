<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CbtExam;

class SebController extends Controller
{
    /**
     * Menampilkan Halaman Landing Page SEB
     * (Halaman pilihan device HP / Laptop)
     */
    public function landing(CbtExam $exam)
    {
        // Pastikan file view ada di resources/views/cbt/seb_landing.blade.php
        return view('cbt.seb_landing', compact('exam'));
    }
}