<?php

namespace App\Listeners;

use App\Services\AuditLogService;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $guard = $event->guard ?? 'web';
        $user = $event->user;

        // Catat ke user_login_logs
        AuditLogService::logLogin($user, $guard, 'success');

        // Catat juga ke system_audit_logs
        $userName = $user ? ($user->name ?? 'User') : 'User';
        AuditLogService::log(
            'auth',
            'login',
            "Pengguna {$userName} berhasil masuk ke sistem ({$guard})."
        );
    }
}
