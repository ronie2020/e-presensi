<?php

namespace App\Http\Middleware;

use App\Services\AuditLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditActivityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya catat aksi mutasi data (POST, PUT, PATCH, DELETE) dan response sukses/redirect
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']) && $response->getStatusCode() < 400) {
            $this->recordAudit($request);
        }

        return $response;
    }

    /**
     * Proses pencatatan audit log dari request
     */
    protected function recordAudit(Request $request): void
    {
        $path = $request->path();

        // Daftar rute yang dikecualikan (polling, auth internal, chat, scan kiosk)
        $excludedPatterns = [
            'login*',
            'logout*',
            'student/login*',
            'student/logout*',
            '*/chat/*',
            'kiosk/*',
            'livewire/*',
            '_debugbar/*',
        ];

        foreach ($excludedPatterns as $pattern) {
            if ($request->is($pattern)) {
                return;
            }
        }

        // Tentukan modul dari URL
        $module = $this->resolveModule($path);

        // Tentukan jenis aksi
        $method = $request->method();
        $action = match ($method) {
            'POST'   => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default  => strtolower($method),
        };

        // Deteksi aksi khusus dari URL
        if (str_contains($path, 'import'))  $action = 'import';
        if (str_contains($path, 'export'))  $action = 'export';
        if (str_contains($path, 'verify'))  $action = 'verify';
        if (str_contains($path, 'reset'))   $action = 'reset';
        if (str_contains($path, 'sync'))    $action = 'sync';

        // Filter payload sensitif
        $payload = $request->except([
            'password',
            'password_confirmation',
            '_token',
            '_method',
            'token',
        ]);

        $description = sprintf(
            'Aksi %s pada modul [%s] melalui endpoint %s (%s)',
            strtoupper($action),
            strtoupper($module),
            $request->path(),
            $request->method()
        );

        AuditLogService::log(
            $module,
            $action,
            $description,
            null,
            null,
            !empty($payload) ? $payload : null
        );
    }

    /**
     * Petakan path URL ke modul sistem
     */
    protected function resolveModule(string $path): string
    {
        if (str_contains($path, 'teaching'))                                return 'kbm';
        if (str_contains($path, 'cbt') || str_contains($path, 'exam') || str_contains($path, 'bank-soal')) return 'cbt';
        if (str_contains($path, 'lms') || str_contains($path, 'learning')) return 'lms';
        if (str_contains($path, 'grade'))                                  return 'penilaian';
        if (str_contains($path, 'library'))                                return 'perpustakaan';
        if (str_contains($path, 'discipline') || str_contains($path, 'permit') || str_contains($path, 'bk')) return 'kedisiplinan_bk';
        if (str_contains($path, 'student') || str_contains($path, 'class') || str_contains($path, 'promotion')) return 'master_siswa';
        if (str_contains($path, 'letter') || str_contains($path, 'sppd') || str_contains($path, 'spt')) return 'persuratan';
        if (str_contains($path, 'attendance') || str_contains($path, 'reports')) return 'presensi_laporan';
        if (str_contains($path, 'user'))                                   return 'pengguna';
        if (str_contains($path, 'settings') || str_contains($path, 'schedule')) return 'pengaturan_jadwal';

        return 'umum';
    }
}
