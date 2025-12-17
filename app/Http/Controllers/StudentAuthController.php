<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class StudentAuthController extends Controller
{
    // 1. Tampilkan Form Login
    public function showLoginForm()
    {
        // Jika sudah login, langsung lempar ke Dashboard Belajar (LMS)
        if (Auth::guard('student')->check()) {
            // Update route ke 'students.' (jamak)
            return redirect()->route('students.learning.index'); 
        }
        
        return redirect()->route('portal.index', ['tab' => 'login']);
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string', 
        ]);

        $student = Student::where('student_id', $request->student_id)->first();

        if ($student) {
            Auth::guard('student')->login($student);
            $request->session()->regenerate();

            // ===> PERBAIKAN: Redirect ke route 'students.learning.index' <===
            return redirect()->intended(route('students.learning.index'));
        }

        return back()->withErrors([
            'student_id' => 'NISN tidak terdaftar.',
        ])->withInput();
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.index');
    }
}