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

class SendWaScanNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attendance;

    public function __construct(AttendanceSiswa $attendance)
    {
        $this->attendance = $attendance;
    }

    public function handle(): void
    {
        // 1. Beri JEDA ACAK (Throttling) agar tidak "nembak" server WA terus menerus
        // Untuk Scan Realtime, jeda pendek (1-3 detik) sudah cukup
        sleep(rand(1, 3));

        if (!$this->attendance->student) {
            Log::warning('Job WA Scan dibatalkan: Siswa tidak ditemukan.');
            return; 
        }

        // 2. Siapkan Data
        $student = $this->attendance->student;
        $nomorWA = $student->parent_wa_number;
        $namaSiswa = $student->name;
        $jamScan = Carbon::parse($this->attendance->time_in)->format('H:i');
        $tipeAbsen = $this->attendance->type; 
        $catatan = $this->attendance->notes;

        // 3. TEKNIK ANTI-BAN: VARIASI SALAM (Spintax Sederhana)
        // Agar pesan tidak terdeteksi sebagai broadcast identik oleh WA
        $salamList = [
            "Assalamualaikum", 
            "Salam Hormat", 
            "Halo Ayah/Bunda", 
            "Info Sekolah", 
            "Laporan Kehadiran"
        ];
        $salam = $salamList[array_rand($salamList)];

        // 4. Template Pesan
        $message = "";
        if ($tipeAbsen == 'Masuk') {
            $message = "*INFO PRESENSI SMPN 3 LAKBOK*\n\n" .
                       "{$salam}, kami sampaikan bahwa Ananda:\n" .
                       "Nama: *{$namaSiswa}*\n" .
                       "Aktivitas: *ABSEN MASUK*\n" .
                       "Pukul: *{$jamScan} WIB*\n" .
                       "Status: _{$catatan}_\n\n" .
                       "Terima kasih.";
        } 
        elseif ($tipeAbsen == 'Pulang') {
            $message = "*INFO PRESENSI SMPN 3 LAKBOK*\n\n" .
                       "{$salam}, kami sampaikan bahwa Ananda:\n" .
                       "Nama: *{$namaSiswa}*\n" .
                       "Aktivitas: *ABSEN PULANG*\n" .
                       "Pukul: *{$jamScan} WIB*\n\n" .
                       "Hati-hati di jalan. Terima kasih.";
        }
        else {
            return;
        }
        
        // 5. Rotasi AppKey (Sudah Benar)
        $apiUrl = 'https://app.wapanels.com/api/create-message';
        $authKey = config('app.wapanels_authkey');
        $appKeys = config('app.wapanels_appkeys');

        if (empty($appKeys) || empty($appKeys[0])) {
            Log::error('WAPANELS_APP_KEYS kosong.');
            return; 
        }
        $appKey = $appKeys[array_rand($appKeys)]; 

        try {
            $response = Http::post($apiUrl, [
                'appkey' => $appKey,
                'authkey' => $authKey,
                'to' => $nomorWA,
                'message' => $message,
                'sandbox' => 'false'
            ]);
            
            // Log sukses (Opsional: Matikan log ini jika server penuh)
            // Log::info("WA {$tipeAbsen} -> {$namaSiswa} (Key: " . substr($appKey, 0, 4) . "...)");

        } catch (\Exception $e) {
            Log::error("Gagal kirim WA Scan: " . $e->getMessage());
        }
    }
}