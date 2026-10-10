<?php

namespace App\Listeners;

use App\Services\AuditLogService;
use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        $guard = $event->guard ?? 'web';
        $credentials = $event->credentials ?? [];
        $identifier = $credentials['email'] ?? $credentials['student_id'] ?? $credentials['nip'] ?? 'Unknown';

        // Catat ke user_login_logs dengan status failed
        AuditLogService::logLogin(null, $guard, 'failed', $identifier);

        // Catat juga ke system_audit_logs untuk deteksi keamanan / brute-force
        AuditLogService::log(
            'auth',
            'failed_login',
            "Percobaan login gagal untuk identitas: {$identifier} ({$guard})."
        );
    }
}
