<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. DAFTAR ALIAS MIDDLEWARE
        $middleware->alias([
            'seb' => \App\Http\Middleware\RequireSafeExamBrowser::class,
        ]);

        // 2. MENGATASI ERROR 419 SAAT LOGOUT (CSRF EXCEPTION)
        $middleware->validateCsrfTokens(except: [
            'logout',           
            'student/logout',   
        ]);

        // 3. LOGIKA REDIRECT USER YANG BELUM LOGIN
        $middleware->redirectGuestsTo(function (Request $request) {
            // Perbaikan: Menambahkan cek untuk 'students/*' (jamak) dan 'portal/*'
            if ($request->is('student/*') || $request->is('students/*') || $request->is('portal/*')) {
                return route('student.login');
            }
            
            // Default ke Login Guru
            return route('login');
        });
        // 4. MENGATASI ERROR 419 SAAT SCAN QR CODE (CSRF EXCEPTION)
        $middleware->validateCsrfTokens(except: [
            'scan',
            'scan/*'
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();