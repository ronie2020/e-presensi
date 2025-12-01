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
        // 1. Jeda Acak (PENTING untuk Anti-Banned)
        sleep(rand(2, 6));

        if (!$this->attendance->student) return;

        // 2. Siapkan Data Variabel
        $student   = $this->attendance->student;
        $nomorWA   = $student->parent_wa_number;
        $namaSiswa = $student->name;
        $jamScan   = Carbon::parse($this->attendance->time_in)->format('H:i');
        
        // Cek jam pulang (pastikan tidak null/kosong)
        $jamPulang = (!empty($this->attendance->time_out)) ? Carbon::parse($this->attendance->time_out)->format('H:i') : null;
        
        $tanggal   = Carbon::parse($this->attendance->attendance_date)->translatedFormat('d F Y');
        
        $tipeAbsen = strtolower($this->attendance->type); 
        $aktivitas = strtolower($this->attendance->activity ?? $tipeAbsen);
        $status    = $this->attendance->status;
        $catatan   = $this->attendance->notes ?? ''; // Ambil catatan (misal: "Terlambat 15 menit")

        // Validasi Nomor HP
        if(empty($nomorWA)) {
             return;
        }

        $message = "";

        // --- Variasi Salam ---
        $salam = collect([
            "Assalamualaikum Wr. Wb.", 
            "Selamat Pagi/Siang,", 
            "Yth. Wali Murid,", 
            "Salam Hormat,"
        ])->random();

        // =========================================================================
        // LOGIKA PESAN WA
        // =========================================================================

        // --- SKENARIO 1: ABSEN PULANG ---
        if ($tipeAbsen == 'pulang' || !empty($jamPulang)) {
            
            $templates = [
                "*LAPORAN KEPULANGAN*\n\n{$salam}\nAnanda *{$namaSiswa}* telah menyelesaikan kegiatan belajar dan meninggalkan sekolah.\n\n⏰ Jam Pulang: {$jamPulang} WIB\n\nMohon dipantau kepulangannya. Terima kasih.",
                
                "*INFO PULANG SEKOLAH*\n\n{$salam}\nDiinformasikan bahwa Ananda *{$namaSiswa}* sudah absen pulang pada pukul *{$jamPulang} WIB*.\nHati-hati di jalan dan selamat beristirahat.\n\n- Admin SMPN 3 Lakbok -",
                
                "🔔 *INFO SISWA*\n\nAnanda *{$namaSiswa}* telah pulang sekolah hari ini ({$tanggal}) pukul {$jamPulang} WIB.\nTerima kasih atas kerja samanya."
            ];

            $message = $templates[array_rand($templates)];
        }

        // --- SKENARIO 2: ABSEN MASUK ---
        elseif ($tipeAbsen == 'masuk' || $status == 'Hadir' || $status == 'Terlambat') {
            
            if (str_contains($aktivitas, 'harian') || $tipeAbsen == 'harian' || $tipeAbsen == 'masuk') {
                
                // [BARU] Jika Terlambat, gunakan Template Khusus yang lebih informatif
                if ($status == 'Terlambat') {
                    $templates = [
                        // Template Terlambat A
                        "⚠️ *INFO KETERLAMBATAN*\n\n{$salam}\nKami informasikan Ananda *{$namaSiswa}* telah tiba di sekolah namun tercatat *TERLAMBAT*.\n\n📅 Tanggal: {$tanggal}\n⏰ Jam Masuk: {$jamScan} WIB\n📝 Info: _{$catatan}_\n\nMohon pembinaan agar esok datang lebih awal. Terima kasih.",
                        
                        // Template Terlambat B
                        "*LAPORAN KEHADIRAN*\n\n{$salam}\nAnanda *{$namaSiswa}* hadir di sekolah pada pukul {$jamScan} WIB.\nStatus: *TERLAMBAT*\nKeterangan: {$catatan}\n\nTerima kasih atas perhatiannya."
                    ];
                } 
                // Jika Tepat Waktu (Hadir)
                else {
                    $templates = [
                        // Template Masuk A
                        "*LAPORAN KEHADIRAN SISWA*\n\n{$salam}\nKami informasikan bahwa Ananda *{$namaSiswa}* telah tiba di sekolah.\n\n📅 Tanggal: {$tanggal}\n⏰ Pukul: {$jamScan} WIB\n✅ Status: TEPAT WAKTU\n\nTerima kasih, SMPN 3 Lakbok.",
                        
                        // Template Masuk B
                        "*INFO SEKOLAH*\n\n{$salam} Orang Tua Siswa.\nAnanda *{$namaSiswa}* terdeteksi absen masuk pada pukul *{$jamScan} WIB* hari ini ({$tanggal}).\nStatus: HADIR.\n\nSemoga hari ini menyenangkan.",
                        
                        // Template Masuk C
                        "🔔 *NOTIFIKASI PRESENSI*\n\nHalo Ayah/Bunda,\nAnanda *{$namaSiswa}* sudah siap belajar di sekolah! 🏫\nAbsen masuk tercatat pukul: {$jamScan} WIB.\n\nMohon doanya agar kegiatan belajar berjalan lancar.",
                    ];
                }
                
                $message = $templates[array_rand($templates)];
            } else {
                return; 
            }
        } 
        
        else {
            return; // Tipe lain skip
        }

        // 4. KONFIGURASI API & MULTI DEVICE
        $apiUrl = 'https://app.wapanels.com/api/create-message';
        $authKey = config('app.wapanels_authkey');
        $appKeys = config('app.wapanels_appkeys'); 

        if (empty($appKeys) || empty($authKey)) {
            Log::error('GAGAL WA: AuthKey/AppKeys kosong. Cek config/app.php');
            return; 
        }

        $selectedAppKey = $appKeys[array_rand($appKeys)];

        // 5. KIRIM PESAN
        try {
            $response = Http::asForm()->post($apiUrl, [
                'appkey' => $selectedAppKey,
                'authkey' => $authKey,
                'to' => $nomorWA,
                'message' => $message,
                'sandbox' => 'false'
            ]);

            if ($response->successful()) {
                $deviceCode = substr($selectedAppKey, -5);
                Log::info("WA " . (!empty($jamPulang) ? 'PULANG' : 'MASUK') . " Terkirim ke {$namaSiswa}. Device: ...{$deviceCode}");
            } else {
                Log::error("WA Gagal (WaPanels): " . $response->body());
            }

        } catch (\Exception $e) {
            Log::error("Exception WA: " . $e->getMessage());
        }
    }
}