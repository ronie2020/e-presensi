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
        sleep(rand(2, 4));

        if (!$this->attendance->student) {
            return; 
        }

        $student = $this->attendance->student;
        $nomorWA = $student->parent_wa_number;
        $namaSiswa = $student->name;
        $statusManual = $this->attendance->status;
        $catatan = $this->attendance->notes;

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
        
        // --- PERBAIKAN: Handle String to Array (Multi Device) ---
        $appKeysRaw = config('app.wapanels_appkeys');
        
        if (is_string($appKeysRaw)) {
            $appKeys = explode(',', str_replace(' ', '', $appKeysRaw));
        } elseif (is_array($appKeysRaw)) {
            $appKeys = $appKeysRaw;
        } else {
            $appKeys = [];
        }

        if (empty($appKeys) || empty($appKeys[0])) return;
        
        $appKey = $appKeys[array_rand($appKeys)];

        try {
            $response = Http::post($apiUrl, [
                'appkey' => $appKey,
                'authkey' => $authKey,
                'to' => $nomorWA,
                'message' => $message,
                'sandbox' => 'false'
            ]);

            if ($response->failed()) {
                Log::error("Gagal WA Manual: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Gagal kirim WA Manual: ' . $e->getMessage());
        }
    }
}