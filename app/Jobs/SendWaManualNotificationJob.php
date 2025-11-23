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

class SendWaManualNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attendance;

    public function __construct(AttendanceSiswa $attendance)
    {
        $this->attendance = $attendance;
    }

    public function handle(): void
    {
        // ANTI-BAN: Jeda 2-4 detik per pesan
        sleep(rand(2, 4));

        if (!$this->attendance->student) {
            return; 
        }

        $student = $this->attendance->student;
        $nomorWA = $student->parent_wa_number;
        $namaSiswa = $student->name;
        $statusManual = $this->attendance->status;
        $catatan = $this->attendance->notes;

        // ANTI-BAN: Variasi Salam
        $salamList = ["Assalamualaikum", "Selamat Pagi/Siang", "Pemberitahuan Sekolah", "Yth. Wali Murid"];
        $salam = $salamList[array_rand($salamList)];

        $message = "*Laporan Absensi SMPN 3 LAKBOK*\n\n" .
                   "{$salam}, diinformasikan bahwa Ananda:\n" .
                   "Nama: *{$namaSiswa}*\n" .
                   "Status Hari Ini: *{$statusManual}*\n" .
                   "Keterangan: {$catatan}\n\n" .
                   "Terima kasih atas kerja samanya.";
        
        $apiUrl = 'https://app.wapanels.com/api/create-message';
        $authKey = config('app.wapanels_authkey');
        $appKeys = config('app.wapanels_appkeys');

        if (empty($appKeys) || empty($appKeys[0])) return;
        
        $appKey = $appKeys[array_rand($appKeys)];

        try {
            Http::post($apiUrl, [
                'appkey' => $appKey,
                'authkey' => $authKey,
                'to' => $nomorWA,
                'message' => $message,
                'sandbox' => 'false'
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal kirim WA Manual: ' . $e->getMessage());
        }
    }
}