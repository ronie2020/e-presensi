<?php

namespace App\Services;

use App\Models\SystemAuditLog;
use App\Models\UserLoginLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Catat Audit Trail Aksi Pengguna
     *
     * @param string $module Contoh: 'kbm', 'cbt', 'lms', 'students', 'grades', 'settings'
     * @param string $action Contoh: 'create', 'update', 'delete', 'export', 'import', 'verify'
     * @param string $description Deskripsi singkat kegiatan
     * @param mixed $subject Model atau objek terkait (opsional)
     * @param array|null $oldValues Nilai sebelum perubahan (opsional)
     * @param array|null $newValues Nilai setelah perubahan (opsional)
     * @return SystemAuditLog|null
     */
    public static function log(
        string $module,
        string $action,
        string $description,
        $subject = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ) {
        try {
            $user = Auth::guard('web')->user() ?? Auth::guard('student')->user();
            $userId = $user ? $user->id : null;
            $userType = $user ? (get_class($user) === 'App\Models\Student' ? 'student' : 'user') : null;
            $userName = $user ? ($user->name ?? 'User #' . $userId) : 'Guest / System';

            $role = 'Guest';
            if ($user) {
                if ($userType === 'student') {
                    $role = 'Siswa';
                } elseif (method_exists($user, 'getRoleNames') && $user->getRoleNames()->isNotEmpty()) {
                    $role = $user->getRoleNames()->first();
                } else {
                    $role = $user->role ?? 'User';
                }
            }

            $subjectType = null;
            $subjectId = null;
            if ($subject) {
                if (is_object($subject)) {
                    $subjectType = get_class($subject);
                    $subjectId = $subject->id ?? null;
                } elseif (is_string($subject)) {
                    $subjectType = $subject;
                }
            }

            return SystemAuditLog::create([
                'user_id'      => $userId,
                'user_type'    => $userType,
                'user_name'    => $userName,
                'role'         => $role,
                'module'       => $module,
                'action'       => $action,
                'description'  => $description,
                'subject_type' => $subjectType,
                'subject_id'   => $subjectId,
                'old_values'   => $oldValues,
                'new_values'   => $newValues,
                'ip_address'   => Request::ip(),
                'user_agent'   => Request::userAgent(),
                'url'          => Request::fullUrl(),
                'method'       => Request::method(),
            ]);
        } catch (\Throwable $e) {
            // Catatan: kegagalan audit log tidak boleh menggagalkan proses utama aplikasi
            \Illuminate\Support\Facades\Log::warning('Gagal mencatat audit log: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Catat Aktivitas Login
     */
    public static function logLogin($user, string $guard = 'web', string $status = 'success', ?string $identifier = null)
    {
        try {
            $ua = Request::userAgent() ?? '';
            $parsed = self::parseUserAgent($ua);

            $userId = $user ? $user->id : null;
            $userType = $user ? (get_class($user) === 'App\Models\Student' ? 'student' : 'user') : 'guest';
            $name = $user ? ($user->name ?? 'User') : ($identifier ?? 'Unknown');

            $role = 'Guest';
            if ($user) {
                if ($userType === 'student') {
                    $role = 'Siswa';
                } elseif (method_exists($user, 'getRoleNames') && $user->getRoleNames()->isNotEmpty()) {
                    $role = $user->getRoleNames()->first();
                } else {
                    $role = $user->role ?? 'User';
                }
            }

            $finalIdentifier = $identifier;
            if (!$finalIdentifier && $user) {
                $finalIdentifier = $user->email ?? $user->student_id ?? $user->nis ?? $user->nip ?? null;
            }

            return UserLoginLog::create([
                'user_id'    => $userId,
                'user_type'  => $userType,
                'name'       => $name,
                'identifier' => $finalIdentifier,
                'role'       => $role,
                'guard'      => $guard,
                'ip_address' => Request::ip(),
                'user_agent' => $ua,
                'device'     => $parsed['device'],
                'browser'    => $parsed['browser'],
                'platform'   => $parsed['platform'],
                'status'     => $status,
                'login_at'   => now(),
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal mencatat login log: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Catat Waktu Logout pada Sesi Terakhir
     */
    public static function logLogout($user, string $guard = 'web')
    {
        try {
            if (!$user) return;

            $userType = get_class($user) === 'App\Models\Student' ? 'student' : 'user';

            // Cari sesi login terakhir yang belum di-logout
            $lastLogin = UserLoginLog::where('user_id', $user->id)
                ->where('user_type', $userType)
                ->whereNull('logout_at')
                ->latest('login_at')
                ->first();

            if ($lastLogin) {
                $lastLogin->update(['logout_at' => now()]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal mencatat logout log: ' . $e->getMessage());
        }
    }

    /**
     * Parser Ringan untuk Deteksi Device, Browser, dan OS
     */
    public static function parseUserAgent(string $userAgent): array
    {
        $device = 'Desktop';
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $userAgent)) {
            $device = 'Tablet';
        } elseif (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile|mobile)/i', $userAgent)) {
            $device = 'Mobile';
        }

        // Deteksi Platform / OS
        $platform = 'Unknown OS';
        if (preg_match('/windows nt 10/i', $userAgent))     $platform = 'Windows 10/11';
        elseif (preg_match('/windows/i', $userAgent))       $platform = 'Windows';
        elseif (preg_match('/android/i', $userAgent))       $platform = 'Android';
        elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) $platform = 'iOS';
        elseif (preg_match('/macintosh|mac os x/i', $userAgent)) $platform = 'macOS';
        elseif (preg_match('/linux/i', $userAgent))         $platform = 'Linux';

        // Deteksi Browser
        $browser = 'Unknown Browser';
        if (preg_match('/edg/i', $userAgent))               $browser = 'Edge';
        elseif (preg_match('/chrome/i', $userAgent))        $browser = 'Chrome';
        elseif (preg_match('/safari/i', $userAgent))        $browser = 'Safari';
        elseif (preg_match('/firefox/i', $userAgent))       $browser = 'Firefox';
        elseif (preg_match('/opera|opr/i', $userAgent))     $browser = 'Opera';

        return [
            'device'   => $device,
            'browser'  => $browser,
            'platform' => $platform,
        ];
    }
}
