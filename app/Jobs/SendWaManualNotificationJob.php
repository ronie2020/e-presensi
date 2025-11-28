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
        // 1. Anti-Banned: Jeda waktu acak (3-7 detik)
        sleep(rand(3, 7));

        if (!$this->attendance->student) return;

        // 2. Siapkan Data
        $student      = $this->attendance->student;
        $nomorWA      = $student->parent_wa_number;
        $namaSiswa    = $student->name;
        $statusManual = $this->attendance->status; // Sakit / Izin / Alfa
        $catatan      = $this->attendance->notes ?? '-';
        $tanggal      = Carbon::parse($this->attendance->attendance_date)->translatedFormat('d F Y');

        if(empty($nomorWA)) return;

        // 3. Rotasi Salam & Template (Anti-Spam Detection)
        $salam = collect([
            "Assalamualaikum,", "Selamat Pagi/Siang,", "Yth. Wali Murid,", "Salam Hormat,"
        ])->random();

        // Kumpulan Template Pesan
        $templates = [
            // Template A (Formal)
            "*PEMBERITAHUAN SEKOLAH*\n\n{$salam}\nKami informasikan status kehadiran Ananda:\n\nNama: *{$namaSiswa}*\nTanggal: {$tanggal}\nStatus: *{$statusManual}*\nKeterangan: _{$catatan}_\n\nTerima kasih atas perhatiannya.",
            
            // Template B (Perhatian)
            "🔔 *INFO PRESENSI*\n\nHalo Ayah/Bunda,\nHari ini ({$tanggal}), Ananda *{$namaSiswa}* tercatat tidak mengikuti KBM dengan keterangan: *{$statusManual}*.\nCatatan: {$catatan}.\n\nSemoga menjadi maklum.",
            
            // Template C (Singkat)
            "*LAPORAN ABSENSI*\n\n{$salam}\nAnanda *{$namaSiswa}* hari ini izin/tidak masuk sekolah.\nStatus: *{$statusManual}*\nInfo: {$catatan}\n\nTerima kasih - SMPN 3 Lakbok."
        ];

        $message = $templates[array_rand($templates)];

        // 4. Konfigurasi Multi Device
        $apiUrl  = 'https://app.wapanels.com/api/create-message';
        $authKey = config('app.wapanels_authkey');
        $appKeys = config('app.wapanels_appkeys');

        if (empty($appKeys) || empty($authKey)) {
            Log::error('GAGAL WA Manual: Config kosong.');
            return;
        }

        // Pilih device secara acak
        $selectedAppKey = $appKeys[array_rand($appKeys)];

        // 5. Kirim Pesan
        try {
            $response = Http::asForm()->post($apiUrl, [
                'appkey'  => $selectedAppKey,
                'authkey' => $authKey,
                'to'      => $nomorWA,
                'message' => $message,
                'sandbox' => 'false'
            ]);

            if ($response->successful()) {
                Log::info("WA Manual Terkirim ke {$namaSiswa} ({$statusManual})");
            } else {
                Log::error("Gagal WA Manual: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Exception WA Manual: ' . $e->getMessage());
        }
    }
}