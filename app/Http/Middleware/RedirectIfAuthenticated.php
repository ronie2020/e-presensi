<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                
                // === MODIFIKASI DIMULAI DISINI ===
                
                // 1. Jika yang sedang login adalah SISWA (guard: student)
                // Lempar mereka kembali ke halaman daftar ujian siswa
                if ($guard === 'student') {
                    return redirect()->route('student.exam.index'); 
                }

                // 2. Jika yang login adalah GURU/ADMIN (guard: web/default)
                // Lempar ke Dashboard Guru
                return redirect(RouteServiceProvider::HOME);
                
                // === MODIFIKASI SELESAI ===
            }
        }

        return $next($request);
    }
}