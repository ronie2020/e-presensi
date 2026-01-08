<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class StudentAuthController extends Controller
{
    /**
     * Tampilkan form login (Default ke Portal)
     */
    public function showLoginForm()
    {
        return view('students.portal.index');
    }

    /**
     * Proses Login Siswa
     */
    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'student_id' => 'required|string',
            'intended_app' => 'nullable|string' // Menangkap asal login (lms/cbt)
        ]);

        // 2. Cari Siswa
        $student = Student::where('student_id', $request->student_id)
                    ->orWhere('nis', $request->student_id)
                    ->first();

        if (!$student) {
            return back()->with('error', 'NISN/NIS tidak ditemukan.')->withInput();
        }

        // 3. Proses Login
        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        // Cek Status Alumni
        if ($student->status === 'graduated') {
            return redirect()->route('alumni.dashboard')->with('success', 'Selamat datang kembali, Alumni!');
        }

        // 4. Redirect Khusus (Jika login dari tombol spesifik di halaman depan)
        $intended = $request->input('intended_app');

        if ($intended === 'cbt') {
            return redirect()->route('student.exam.index')->with('success', 'Selamat datang di Ruang Ujian.');
        } 
        
        if ($intended === 'lms') {
            return redirect()->route('students.learning.index')->with('success', 'Selamat datang di Ruang Belajar.');
        }

        // 5. DEFAULT REDIRECT: Ke Student Hub / Dashboard Utama
        // Ini yang kita ubah agar masuk ke tampilan dashboard baru
        return redirect()->route('student.habits.dashboard')->with('success', 'Selamat datang di Dashboard Siswa.');
    }

    /**
     * Logout Siswa
     */
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.index');
    }
}