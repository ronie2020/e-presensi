<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BkCategory;
use App\Models\BkSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BkStudentController extends Controller 
{
    // Halaman Riwayat & Daftar Konseling
    public function index()
    {
        $studentId = Auth::guard('student')->id();
        
        $histories = BkSession::where('student_id', $studentId)
            ->with(['category', 'teacher'])
            ->latest()
            ->paginate(10);

        return view('students.bk.index', compact('histories'));
    }

    // Form Pengajuan Konseling Baru
    public function create()
    {
        $categories = BkCategory::all();       
        return view('students.bk.create', compact('categories'));
    }

    // Proses Simpan Pengajuan
    public function store(Request $request)
    {
        $request->validate([
            'bk_category_id' => 'required|exists:bk_categories,id',
            'initial_message' => 'required|string|min:10',
            'method' => 'required|in:offline,online',
        ]);

        BkSession::create([
            'student_id' => Auth::guard('student')->id(),
            'bk_category_id' => $request->bk_category_id,
            'initial_message' => $request->initial_message,
            'method' => $request->method,
            'status' => 'pending', 
        ]);
       
        return redirect()->route('student.bk.index')
            ->with('success', 'Pengajuan konseling berhasil dikirim. Menunggu respon Guru BK.');
    }

    // Detail Tiket Konseling
    public function show($id)
    {
        $session = BkSession::where('student_id', Auth::guard('student')->id())
            ->with(['record', 'teacher', 'category'])
            ->findOrFail($id);            
      
        return view('students.bk.show', compact('session'));
    }
}