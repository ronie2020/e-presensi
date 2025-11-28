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
        // Memberi jeda 2-6 detik antara setiap pesan agar tidak dianggap bot spamming
        sleep(rand(2, 6));

        if (!$this->attendance->student) return;

        // 2. Siapkan Data Variabel
        $student   = $this->attendance->student;
        $nomorWA   = $student->parent_wa_number;
        $namaSiswa = $student->name;
        $jamScan   = Carbon::parse($this->attendance->time_in)->format('H:i');
        $jamPulang = $this->attendance->time_out ? Carbon::parse($this->attendance->time_out)->format('H:i') : '';
        $tanggal   = Carbon::parse($this->attendance->attendance_date)->translatedFormat('d F Y');
        
        $tipeAbsen = strtolower($this->attendance->type); 
        $aktivitas = strtolower($this->attendance->activity ?? $tipeAbsen);
        $status    = $this->attendance->status;

        // Validasi Nomor HP
        if(empty($nomorWA)) {
             // Log::warning("Skip WA: {$namaSiswa} tidak ada nomor HP.");
             return;
        }

        // 3. LOGIKA TEMPLATE PESAN (ACAK)
        $message = "";

        // --- Variasi Salam ---
        $salam = collect([
            "Assalamualaikum Wr. Wb.", 
            "Selamat Pagi/Siang,", 
            "Yth. Wali Murid,", 
            "Salam Hormat,"
        ])->random();

        // --- SKENARIO 1: ABSEN MASUK ---
        if ($tipeAbsen == 'masuk' || $status == 'Hadir' || $status == 'Terlambat') {
            if (str_contains($aktivitas, 'harian') || $tipeAbsen == 'harian') {
                
                // Kumpulan Template Masuk (Agar pesan tidak identik)
                $templates = [
                    // Template A (Formal)
                    "*LAPORAN KEHADIRAN SISWA*\n\n{$salam}\nKami informasikan bahwa Ananda *{$namaSiswa}* telah tiba di sekolah.\n\n📅 Tanggal: {$tanggal}\n⏰ Pukul: {$jamScan} WIB\n✅ Status: {$status}\n\nTerima kasih, SMPN 3 Lakbok.",
                    
                    // Template B (Singkat)
                    "*INFO SEKOLAH*\n\n{$salam} Orang Tua Siswa.\nAnanda *{$namaSiswa}* terdeteksi absen masuk pada pukul *{$jamScan} WIB* hari ini ({$tanggal}).\nStatus kehadiran: {$status}.\n\nSemoga hari ini menyenangkan.",
                    
                    // Template C (Ceria)
                    "🔔 *NOTIFIKASI PRESENSI*\n\nHalo Ayah/Bunda,\nAnanda *{$namaSiswa}* sudah siap belajar di sekolah! 🏫\nAbsen masuk tercatat pukul: {$jamScan} WIB.\n\nMohon doanya agar kegiatan belajar berjalan lancar.",
                ];
                
                $message = $templates[array_rand($templates)];
            } else {
                return; // Skip jika bukan harian
            }
        } 
        
        // --- SKENARIO 2: ABSEN PULANG ---
        elseif ($tipeAbsen == 'pulang' || !empty($this->attendance->time_out)) {
            
            // Kumpulan Template Pulang
            $templates = [
                // Template A (Formal)
                "*LAPORAN KEPULANGAN*\n\n{$salam}\nAnanda *{$namaSiswa}* telah menyelesaikan kegiatan belajar dan meninggalkan sekolah.\n\n⏰ Jam Pulang: {$jamPulang} WIB\n\nMohon dipantau kepulangannya. Terima kasih.",
                
                // Template B (Perhatian)
                "*INFO PULANG SEKOLAH*\n\n{$salam}\nDiinformasikan bahwa Ananda *{$namaSiswa}* sudah absen pulang pada pukul *{$jamPulang} WIB*.\nHati-hati di jalan dan selamat beristirahat.\n\n- Admin SMPN 3 Lakbok -",
                
                // Template C (Singkat)
                "🔔 *INFO SISWA*\n\nAnanda *{$namaSiswa}* telah pulang sekolah hari ini ({$tanggal}) pukul {$jamPulang} WIB.\nTerima kasih atas kerja samanya."
            ];

            $message = $templates[array_rand($templates)];
        }
        else {
            return; // Tipe lain skip
        }

        // 4. KONFIGURASI API & MULTI DEVICE
        // Mengambil config dari app.php yang sudah memproses .env
        $apiUrl = 'https://app.wapanels.com/api/create-message';
        $authKey = config('app.wapanels_authkey');
        $appKeys = config('app.wapanels_appkeys'); // Ini sudah berbentuk Array

        if (empty($appKeys) || empty($authKey)) {
            Log::error('GAGAL WA: AuthKey/AppKeys kosong. Cek config/app.php');
            return; 
        }

        // --- LOAD BALANCING (Pilih Device Secara Acak) ---
        // Ini kunci untuk membagi beban pengiriman ke 2 device atau lebih
        $selectedAppKey = $appKeys[array_rand($appKeys)];

        // 5. KIRIM PESAN (Format Form Data)
        try {
            $response = Http::asForm()->post($apiUrl, [
                'appkey' => $selectedAppKey,
                'authkey' => $authKey,
                'to' => $nomorWA,
                'message' => $message,
                'sandbox' => 'false'
            ]);

            if ($response->successful()) {
                // Log device mana yang dipakai (mengambil 5 karakter terakhir key)
                $deviceCode = substr($selectedAppKey, -5);
                Log::info("WA Terkirim ke {$namaSiswa}. Device: ...{$deviceCode}");
            } else {
                Log::error("WA Gagal (WaPanels): " . $response->body());
            }

        } catch (\Exception $e) {
            Log::error("Exception WA: " . $e->getMessage());
        }
    }
}