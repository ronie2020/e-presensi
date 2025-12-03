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
        
        // --- LOGIKA REDIRECT USER YANG BELUM LOGIN ---
        $middleware->redirectGuestsTo(function (Request $request) {
            // 1. Jika URL yang diakses berawalan "student/...", arahkan ke Login Siswa
            if ($request->is('student/*') || $request->is('student')) {
                return route('student.login');
            }
            
            // 2. Default ke Login Guru
            return route('login');
        });

    })

    ->withMiddleware(function (Middleware $middleware) {
            $middleware->alias([
                'seb' => \App\Http\Middleware\RequireSafeExamBrowser::class,
            ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

    