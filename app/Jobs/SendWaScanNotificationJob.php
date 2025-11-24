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
        // 1. Jeda anti-spam (1-3 detik)
        sleep(rand(1, 3));

        if (!$this->attendance->student) {
            return; 
        }

        // 2. Siapkan Data Dasar
        $student = $this->attendance->student;
        $nomorWA = $student->parent_wa_number;
        $namaSiswa = $student->name;
        $catatan = $this->attendance->notes;
        
        // --- LOGIKA DETEKSI STATUS (MASUK / PULANG) ---
        $tipeDB = $this->attendance->type; // 'Harian' atau 'Keagamaan'
        
        // Default awal
        $statusNotif = $tipeDB; 
        $waktuScan = $this->attendance->time_in;

        // Jika tipe di database 'Harian', cek kolom time_out
        if ($tipeDB == 'Harian') {
            if (!empty($this->attendance->time_out)) {
                $statusNotif = 'Pulang';
                $waktuScan = $this->attendance->time_out;
            } else {
                $statusNotif = 'Masuk';
                $waktuScan = $this->attendance->time_in;
            }
        }

        // Format Tanggal & Jam
        $tanggalObj = Carbon::parse($this->attendance->attendance_date);
        $tanggalStr = $tanggalObj->translatedFormat('l, d F Y');
        $jamScanStr = Carbon::parse($waktuScan)->format('H:i');
        
        $aktivitas = $this->attendance->activity ?? $statusNotif; 

        // 3. Variasi Salam
        $salamList = ["Assalamualaikum", "Salam Hormat", "Halo Orang Tua/Wali", "Info Sekolah", "Laporan Aktivitas"];
        $salam = $salamList[array_rand($salamList)];

        // 4. Template Pesan
        $message = "";
        $tipeCek = strtolower($statusNotif); 
        $aktivitasCek = strtolower($aktivitas);

        if ($tipeCek == 'masuk') {
            $message = "*INFO PRESENSI SMPN 3 LAKBOK*\n\n" .
                       "{$salam}, kami sampaikan bahwa Ananda:\n" .
                       "Nama: *{$namaSiswa}*\n" .
                       "Hari/Tgl: {$tanggalStr}\n" .
                       "Aktivitas: *ABSEN MASUK*\n" .
                       "Pukul: *{$jamScanStr} WIB*\n" .
                       "Status: _{$catatan}_\n\n" .
                       "Terima kasih.";
        } 
        elseif ($tipeCek == 'pulang') {
            $message = "*INFO PRESENSI SMPN 3 LAKBOK*\n\n" .
                       "{$salam}, kami sampaikan bahwa Ananda:\n" .
                       "Nama: *{$namaSiswa}*\n" .
                       "Hari/Tgl: {$tanggalStr}\n" .
                       "Aktivitas: *ABSEN PULANG*\n" .
                       "Pukul: *{$jamScanStr} WIB*\n\n" .
                       "Hati-hati di jalan. Terima kasih.";
        }
        // --- FILTER KEAGAMAAN (DINONAKTIFKAN SEMENTARA) ---
        elseif (str_contains($aktivitasCek, 'dhuha') || str_contains($tipeCek, 'dhuha')) {
            return; 
        }
        elseif (str_contains($aktivitasCek, 'dhuhur') || str_contains($tipeCek, 'dhuhur') || str_contains($tipeCek, 'duhur')) {
            return;
        }
        else {
            return;
        }
        
        // 5. Kirim via WaPanels dengan MULTI DEVICE SUPPORT
        $apiUrl = 'https://app.wapanels.com/api/create-message';
        $authKey = config('app.wapanels_authkey');
        
        // --- PERBAIKAN UTAMA: Handle String to Array ---
        $appKeysRaw = config('app.wapanels_appkeys');
        
        // Jika formatnya string (karena dari .env), kita pecah jadi array
        if (is_string($appKeysRaw)) {
            // Hapus spasi jika ada, lalu explode berdasarkan koma
            $appKeys = explode(',', str_replace(' ', '', $appKeysRaw));
        } elseif (is_array($appKeysRaw)) {
            $appKeys = $appKeysRaw;
        } else {
            $appKeys = [];
        }

        // Validasi jika key kosong
        if (empty($appKeys) || empty($appKeys[0])) {
            Log::error('WAPANELS_APP_KEYS kosong atau format salah di .env');
            return; 
        }

        // Pilih satu device secara acak (Load Balancing)
        $appKey = $appKeys[array_rand($appKeys)]; 

        if(empty($nomorWA)) return;

        try {
            $response = Http::post($apiUrl, [
                'appkey' => $appKey,
                'authkey' => $authKey,
                'to' => $nomorWA,
                'message' => $message,
                'sandbox' => 'false'
            ]);
            
            if ($response->failed()) {
                Log::error("API WaPanels Error (Key: ...".substr($appKey, -4)."): " . $response->body());
                throw new \Exception("Gagal kirim ke WaPanels");
            }

        } catch (\Exception $e) {
            Log::error("Gagal kirim WA Scan: " . $e->getMessage());
            $this->fail($e);
        }
    }
}