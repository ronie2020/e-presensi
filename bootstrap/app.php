<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. DAFTAR ALIAS MIDDLEWARE
        $middleware->alias([
            'seb' => \App\Http\Middleware\RequireSafeExamBrowser::class,
        ]);

        // 2. MENGATASI ERROR 419 SAAT LOGOUT (CSRF EXCEPTION)
        // Kita whitelist route logout agar tidak kena validasi token saat sesi habis
        $middleware->validateCsrfTokens(except: [
            'logout',           // Untuk logout Admin/Guru
            'student/logout',   // Untuk logout Siswa
        ]);

        // 3. LOGIKA REDIRECT USER YANG BELUM LOGIN
        $middleware->redirectGuestsTo(function (Request $request) {
            // Jika URL yang diakses berawalan "student/...", arahkan ke Login Siswa
            if ($request->is('student/*') || $request->is('student')) {
                return route('student.login');
            }
            
            // Default ke Login Guru
            return route('login');
        });

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();