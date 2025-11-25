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
        // 1. Jeda untuk menghindari spamming server WA
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
        // Ambil activity jika tipe bukan Harian
        $aktivitas = $this->attendance->activity ?? $tipeAbsen; 
        $catatan = $this->attendance->notes;

        // 3. Variasi Salam
        $salamList = [
            "Assalamualaikum", 
            "Salam Hormat", 
            "Halo Ayah/Bunda", 
            "Info Sekolah", 
            "Laporan Aktivitas"
        ];
        $salam = $salamList[array_rand($salamList)];

        // 4. Template Pesan
        $message = "";

        // Normalisasi string (huruf kecil semua) agar deteksi lebih akurat
        $tipeCek = strtolower($tipeAbsen);
        $aktivitasCek = strtolower($aktivitas);

        if ($tipeCek == 'masuk') {
            $message = "*INFO PRESENSI SMPN 3 LAKBOK*\n\n" .
                       "{$salam}, kami sampaikan bahwa Ananda:\n" .
                       "Nama: *{$namaSiswa}*\n" .
                       "Aktivitas: *ABSEN MASUK*\n" .
                       "Pukul: *{$jamScan} WIB*\n" .
                       "Status: _{$catatan}_\n\n" .
                       "Terima kasih.";
        } 
        elseif ($tipeCek == 'pulang') {
            $message = "*INFO PRESENSI SMPN 3 LAKBOK*\n\n" .
                       "{$salam}, kami sampaikan bahwa Ananda:\n" .
                       "Nama: *{$namaSiswa}*\n" .
                       "Aktivitas: *ABSEN PULANG*\n" .
                       "Pukul: *{$jamScan} WIB*\n\n" .
                       "Hati-hati di jalan. Terima kasih.";
        }
        // --- BAGIAN INI DINONAKTIFKAN SEMENTARA ---
        // Jika aktivitas adalah Dhuha atau Dhuhur, job akan berhenti (return) tanpa kirim WA.
        elseif (str_contains($aktivitasCek, 'dhuha') || str_contains($tipeCek, 'dhuha')) {
            return; 
        }
        elseif (str_contains($aktivitasCek, 'dhuhur') || str_contains($tipeCek, 'dhuhur') || str_contains($tipeCek, 'duhur')) {
            return;
        }
        // ------------------------------------------
        else {
            // Log warning hanya jika tipe benar-benar asing (bukan Dhuha/Dhuhur)
            // Log::warning("Tipe Absen tidak dikenali untuk WA: " . $tipeAbsen);
            return;
        }
        
        // 5. Kirim Pesan (Hanya untuk Masuk/Pulang)
        $apiUrl = 'https://app.wapanels.com/api/create-message';
        $authKey = config('app.wapanels_authkey');
        $appKeys = config('app.wapanels_appkeys');

        if (empty($appKeys) || empty($appKeys[0])) {
            Log::error('WAPANELS_APP_KEYS kosong.');
            return; 
        }
        $appKey = $appKeys[array_rand($appKeys)]; 

        if(empty($nomorWA)) {
             // Opsional: Log jika nomor kosong
             return;
        }

        try {
            $response = Http::post($apiUrl, [
                'appkey' => $appKey,
                'authkey' => $authKey,
                'to' => $nomorWA,
                'message' => $message,
                'sandbox' => 'false'
            ]);
            
            if ($response->failed()) {
                Log::error("API WaPanels Error: " . $response->body());
                throw new \Exception("Gagal kirim ke WaPanels");
            }

        } catch (\Exception $e) {
            Log::error("Gagal kirim WA Scan: " . $e->getMessage());
            $this->fail($e);
        }
    }
}