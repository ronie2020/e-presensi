<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireSafeExamBrowser
{
    public function handle(Request $request, Closure $next): Response
    {
        $userAgent = $request->header('User-Agent');

        // KUNCI RAHASIA UNTUK HP (Bisa diganti sesuka hati)
        // Pastikan kata kunci ini nanti dimasukkan ke settingan aplikasi Android
        $mobileAppKey = 'APLIKASI_UJIAN_SMPN3'; 

        // 1. Cek apakah Browser adalah SEB (Desktop/iOS)
        $isSEB = str_contains($userAgent, 'SEB');

        // 2. Cek apakah Browser adalah Aplikasi Android Khusus
        $isMobileApp = str_contains($userAgent, $mobileAppKey);

        // Jika BUKAN SEB dan BUKAN Aplikasi Mobile, tolak akses
        if (!$isSEB && !$isMobileApp) {
            
            $examId = $request->route('exam');

            // Jika tidak ada ID ujian, kembalikan ke dashboard siswa
            if (!$examId) {
                return redirect()->route('student.exam.index');
            }

            // Lempar ke halaman Landing (Suruh download SEB atau APK)
            return redirect()->route('cbt.seb_landing', ['exam' => $examId]);
        }

        return $next($request);
    }
}