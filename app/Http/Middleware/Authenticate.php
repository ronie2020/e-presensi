<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            
            // === LOGIKA MODIFIKASI ===
            
            // Jika user mencoba mengakses halaman yang URL-nya mengandung kata 'student' atau 'portal'
            // Maka arahkan dia ke halaman login khusus SISWA
            if ($request->is('student*') || $request->is('portal*')) {
                return route('student.login');
            }

            // Selain itu (default), arahkan ke halaman login GURU/ADMIN
            return route('login');
        }
    }
}