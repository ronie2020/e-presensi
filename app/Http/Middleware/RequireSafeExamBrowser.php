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

        // 1. Cek User Agent (SEB Resmi atau App Sekolah)
        $isOfficialSEB = str_contains($userAgent, 'SEB');
        $isMobileApp = str_contains($userAgent, 'APLIKASI_UJIAN_SMPN3'); // Opsional

        // 2. LOGIKA BYPASS (SOLUSI 1)
        // Cek apakah ada request untuk mode darurat (strict_mode) dari URL atau Session
        $isStrictMode = $request->query('strict_mode') == '1' || session('monitoring_mode') == 'strict_js';

        // Jika Browser Valid (SEB/App) -> Izinkan & Hapus Mode Strict
        if ($isOfficialSEB || $isMobileApp) {
            session()->forget('monitoring_mode');
            return $next($request);
        }

        // Jika Bukan SEB, tapi Mode Darurat diaktifkan -> Izinkan & Set Session Strict
        if ($isStrictMode) {
            session(['monitoring_mode' => 'strict_js']);
            return $next($request);
        }

        // ===========================================
        // JIKA TIDAK MEMENUHI SYARAT DI ATAS -> TOLAK
        // ===========================================
        
        // Ambil Parameter Ujian untuk Redirect
        $examParam = $request->route('exam');
        $examId = null;

        if ($examParam instanceof \Illuminate\Database\Eloquent\Model) {
            $examId = $examParam->id;
        } elseif (is_string($examParam) || is_numeric($examParam)) {
            $examId = $examParam;
        }

        // Jika ID ujian tidak ketemu, kembalikan ke dashboard
        if (!$examId) {
            return redirect()->route('student.exam.index')
                ->with('error', 'Akses ditolak. Browser tidak dikenali.');
        }

        // Lempar ke Halaman Landing (Pilih Device)
        return redirect()->route('cbt.seb_landing', ['exam' => $examId]);
    }
}