<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireSafeExamBrowser
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Dapatkan User Agent
        $userAgent = $request->header('User-Agent');

        // 2. Definisi Kunci Validasi
        // 'SEB' -> Default string dari Aplikasi Resmi SEB (Laptop & HP/iOS/Android)
        $isOfficialSEB = str_contains($userAgent, 'SEB');
        
        // Opsional: Jika Anda punya aplikasi custom buatan sendiri
        $isCustomApp = str_contains($userAgent, 'APLIKASI_UJIAN_SMPN3');

        // 3. Pengecekan (Izinkan jika salah satu benar)
        if (!$isOfficialSEB && !$isCustomApp) {
            
            // Ambil Parameter Ujian dari URL
            $examParam = $request->route('exam');
            
            // FIX: Handle jika Laravel mengembalikan Object Model, bukan ID string
            $examId = null;
            if ($examParam instanceof \Illuminate\Database\Eloquent\Model) {
                $examId = $examParam->id;
            } elseif (is_string($examParam) || is_numeric($examParam)) {
                $examId = $examParam;
            }

            // Jika ID ujian tidak ketemu, lempar ke dashboard utama
            if (!$examId) {
                return redirect()->route('student.exam.index')
                    ->with('error', 'Akses ditolak. Browser tidak dikenali.');
            }

            // Lempar ke Halaman Landing Info SEB (yang ada QR Code & Tombol Deep Link)
            return redirect()->route('cbt.seb_landing', ['exam' => $examId]);
        }

        return $next($request);
    }
}