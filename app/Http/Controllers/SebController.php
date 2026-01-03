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
       
        return view('cbt.seb_info', compact('exam'));
    }
}