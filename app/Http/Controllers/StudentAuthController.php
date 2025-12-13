<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class StudentAuthController extends Controller
{
    // 1. Tampilkan Form Login (Mengarah ke file view yang ada di editor kanan Anda)
    public function showLoginForm()
    {
        // Pastikan Anda sudah login sebagai siswa atau belum
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.exam.index');
        }
        return view('auth.student_login');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        // Validasi input NISN
        $request->validate([
            'student_id' => 'required|string', 
        ]);

        // Cari siswa di database berdasarkan NISN (student_id)
        $student = Student::where('student_id', $request->student_id)->first();

        // Jika siswa ditemukan
        if ($student) {
            // LOGIN MANUAL menggunakan Guard 'student'
            Auth::guard('student')->login($student);

            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Redirect ke halaman daftar ujian
            return redirect()->intended(route('student.exam.index'));
        }

        // Jika gagal (NISN tidak ditemukan)
        return back()->withErrors([
            'student_id' => 'NISN tidak terdaftar dalam sistem.',
        ])->withInput();
    }
    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login');
    }
}