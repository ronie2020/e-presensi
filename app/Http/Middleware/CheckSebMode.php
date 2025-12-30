<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSebMode
{
    public function handle(Request $request, Closure $next): Response
    {
        $userAgent = $request->header('User-Agent');
        // Deteksi string "SEB" (Browser Ujian)
        $isSEB = str_contains($userAgent, 'SEB');

        // Daftar Route yang BOLEH diakses saat pakai SEB
        // (Saya sesuaikan dengan nama route di web.php Anda)
        $allowedExamRoutes = [
            'student.exam.index',       // Halaman list ujian
            'student.exam.show',        // Halaman konfirmasi start
            'student.exam.start',       // Proses mulai
            'student.exam.run',         // Halaman pengerjaan (di web.php namanya 'run')
            'student.exam.saveAnswer',  // Simpan jawaban
            'student.exam.finish',      // Selesai
            'student.logout',           // Logout
            'sanctum.csrf-cookie',      // CSRF
            'seb.login'                 // Login khusus SEB (jika ada)
        ];

        $currentRouteName = $request->route()->getName();

        // LOGIKA: Jika siswa pakai SEB, dia DILARANG akses route selain yg di atas.
        // Jadi kalau dia coba buka 'students.learning.index', dia ditendang ke 'student.exam.index'
        if ($isSEB) {
            if (!in_array($currentRouteName, $allowedExamRoutes)) {
                return redirect()->route('student.exam.index');
            }
        }

        return $next($request);
    }
}