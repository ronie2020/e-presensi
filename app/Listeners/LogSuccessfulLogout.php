<?php

namespace App\Listeners;

use App\Services\AuditLogService;
use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        $guard = $event->guard ?? 'web';
        $user = $event->user;

        if ($user) {
            AuditLogService::logLogout($user, $guard);

            $userName = $user->name ?? 'User';
            AuditLogService::log(
                'auth',
                'logout',
                "Pengguna {$userName} keluar dari sistem ({$guard})."
            );
        }
    }
}
