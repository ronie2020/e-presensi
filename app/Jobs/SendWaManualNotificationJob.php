<?php

namespace App\Jobs;

use App\Models\AttendanceSiswa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Throwable;
use Exception;

class SendWaManualNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attendance;

    // Retry 3 kali jika gagal
    public $tries = 3;
    public $backoff = 10;

    public function __construct(AttendanceSiswa $attendance)
    {
        $this->attendance = $attendance;
    }

    public function handle(): void
    {
        sleep(rand(3, 7));

        if (!$this->attendance->student) return;

        $student = $this->attendance->student;
        
        // --- VALIDASI NOMOR HP ---
        $nomorWA = preg_replace('/[^0-9]/', '', $student->parent_wa_number);
        if (substr($nomorWA, 0, 2) == '08') $nomorWA = '62' . substr($nomorWA, 1);
        
        if (strlen($nomorWA) < 10) return;

        // --- DATA ---
        $namaSiswa    = $student->name;
        $statusManual = $this->attendance->status; 
        $catatan      = $this->attendance->notes ?? '-';
        $tanggal      = Carbon::parse($this->attendance->attendance_date)->translatedFormat('d F Y');

        // --- SALAM KONTEKSTUAL ---
        $jamSekarang = now()->hour;
        $waktu = ($jamSekarang < 11) ? 'Pagi' : (($jamSekarang < 15) ? 'Siang' : 'Sore');
        
        $salam = collect([
            "Assalamualaikum,", 
            "Selamat {$waktu},", 
            "Yth. Wali Murid,", 
            "Salam Hormat,"
        ])->random();

        // --- TEMPLATE PESAN ---
        $templates = [
            "*PEMBERITAHUAN SEKOLAH*\n\n{$salam}\nKami informasikan status kehadiran Ananda:\n\nNama: *{$namaSiswa}*\nTanggal: {$tanggal}\nStatus: *{$statusManual}*\nKeterangan: _{$catatan}_\n\nTerima kasih atas perhatiannya.",
            "🔔 *INFO PRESENSI*\n\nHalo Ayah/Bunda,\nHari ini ({$tanggal}), Ananda *{$namaSiswa}* tercatat tidak mengikuti KBM dengan keterangan: *{$statusManual}*.\nCatatan: {$catatan}.\n\nSemoga menjadi maklum.",
            "*LAPORAN ABSENSI*\n\n{$salam}\nAnanda *{$namaSiswa}* hari ini izin/tidak masuk sekolah.\nStatus: *{$statusManual}*\nInfo: {$catatan}\n\nTerima kasih - SMPN 3 Lakbok."
        ];

        $message = $templates[array_rand($templates)];

        // --- KIRIM API ---
        $authKey = config('app.wapanels_authkey');
        $appKeys = config('app.wapanels_appkeys');

        if (empty($appKeys) || empty($authKey)) {
            throw new Exception("Config WA Kosong");
        }

        $selectedAppKey = $appKeys[array_rand($appKeys)];
        $apiUrl = 'https://app.wapanels.com/api/create-message';

        $response = Http::timeout(15)->asForm()->post($apiUrl, [
            'appkey'  => $selectedAppKey,
            'authkey' => $authKey,
            'to'      => $nomorWA,
            'message' => $message,
            'sandbox' => 'false'
        ]);

        if ($response->failed()) {
            throw new Exception("API WA Error: " . $response->body());
        } else {
            Log::info("WA Manual Terkirim ke {$namaSiswa} ({$statusManual})");
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::error("WA MANUAL GAGAL FINAL [ID: {$this->attendance->id}]: " . $exception->getMessage());
    }
}