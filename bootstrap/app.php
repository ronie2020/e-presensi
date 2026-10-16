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
            'seb'   => \App\Http\Middleware\RequireSafeExamBrowser::class,
            'audit' => \App\Http\Middleware\AuditActivityMiddleware::class,
        ]);

        // 1b. AUDIT TRAIL LOGGING OTOMATIS
        $middleware->web(append: [
            \App\Http\Middleware\AuditActivityMiddleware::class,
        ]);

        // 2. MENGATASI ERROR 419 (CSRF EXCEPTION)
        $middleware->validateCsrfTokens(except: [
            'logout',           
            'student/logout',   
            'kiosk/process',
            'help-tickets',
        ]);

        // 3. LOGIKA REDIRECT USER YANG BELUM LOGIN
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('student/*') || $request->is('students/*') || $request->is('portal/*')) {
                return route('student.login');
            }
            
            return route('login');
        });


    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();