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
        // 1. Jeda Acak (PENTING untuk Anti-Banned WA)
        sleep(rand(2, 6));

        if (!$this->attendance->student) return;

        // 2. Siapkan Data Variabel
        $student   = $this->attendance->student;
        $nomorWA   = $student->parent_wa_number;
        $namaSiswa = $student->name;
        
        // Parsing Waktu
        $timeInRaw = $this->attendance->time_in;
        $timeOutRaw = $this->attendance->time_out;

        $jamScan = ($timeInRaw && $timeInRaw != '00:00:00') ? Carbon::parse($timeInRaw)->format('H:i') : '-';
        
        // PERBAIKAN: Pastikan jam pulang bukan 00:00:00
        $jamPulang = (!empty($timeOutRaw) && $timeOutRaw != '00:00:00') ? Carbon::parse($timeOutRaw)->format('H:i') : null;
        
        $tanggal   = Carbon::parse($this->attendance->attendance_date)->translatedFormat('d F Y');
        
        $tipeAbsen = strtolower($this->attendance->type); 
        $aktivitas = strtolower($this->attendance->activity ?? $tipeAbsen);
        $status    = $this->attendance->status;
        $catatan   = $this->attendance->notes ?? ''; 

        // Validasi Nomor HP
        if(empty($nomorWA)) {
             return; // Skip jika tidak ada nomor WA
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
        // Logika: Jika ada jam pulang valid, maka ini pasti notifikasi PULANG
        if (!empty($jamPulang)) {
            
            $templates = [
                "*LAPORAN KEPULANGAN*\n\n{$salam}\nAnanda *{$namaSiswa}* telah menyelesaikan kegiatan belajar dan meninggalkan sekolah.\n\n⏰ Jam Pulang: {$jamPulang} WIB\n\nMohon dipantau kepulangannya. Terima kasih.",
                
                "*INFO PULANG SEKOLAH*\n\n{$salam}\nDiinformasikan bahwa Ananda *{$namaSiswa}* sudah absen pulang pada pukul *{$jamPulang} WIB*.\nHati-hati di jalan dan selamat beristirahat.\n\n- Admin Sekolah -",
            ];

            $message = $templates[array_rand($templates)];
        }

        // --- SKENARIO 2: ABSEN MASUK ---
        // Logika: Jika tipe Harian/Masuk DAN status Hadir/Terlambat DAN belum pulang
        elseif (in_array($status, ['Hadir', 'Terlambat']) && empty($jamPulang)) {
            
            if (str_contains($aktivitas, 'harian') || $tipeAbsen == 'harian' || $tipeAbsen == 'masuk') {
                
                // Jika Terlambat
                if ($status == 'Terlambat') {
                    $templates = [
                        "⚠️ *INFO KETERLAMBATAN*\n\n{$salam}\nKami informasikan Ananda *{$namaSiswa}* telah tiba di sekolah namun tercatat *TERLAMBAT*.\n\n📅 Tanggal: {$tanggal}\n⏰ Jam Masuk: {$jamScan} WIB\n📝 Info: _{$catatan}_\n\nMohon pembinaan agar esok datang lebih awal.",
                        
                        "*LAPORAN KEHADIRAN*\n\n{$salam}\nAnanda *{$namaSiswa}* hadir di sekolah pada pukul {$jamScan} WIB.\nStatus: *TERLAMBAT*\nKeterangan: {$catatan}\n\nTerima kasih atas perhatiannya."
                    ];
                } 
                // Jika Tepat Waktu (Hadir)
                else {
                    $templates = [
                        "*LAPORAN KEHADIRAN SISWA*\n\n{$salam}\nKami informasikan bahwa Ananda *{$namaSiswa}* telah tiba di sekolah dengan selamat.\n\n📅 Tanggal: {$tanggal}\n⏰ Pukul: {$jamScan} WIB\n✅ Status: TEPAT WAKTU\n\nTerima kasih.",
                        
                        "*INFO SEKOLAH*\n\n{$salam} Orang Tua Siswa.\nAnanda *{$namaSiswa}* terdeteksi absen masuk pada pukul *{$jamScan} WIB* hari ini ({$tanggal}).\nStatus: HADIR / TEPAT WAKTU.\n\nSemoga hari ini menyenangkan.",
                        
                        "🔔 *NOTIFIKASI PRESENSI*\n\nHalo Ayah/Bunda,\nAnanda *{$namaSiswa}* sudah siap belajar di sekolah! 🏫\nAbsen masuk tercatat pukul: {$jamScan} WIB.\n\nMohon doanya agar kegiatan belajar berjalan lancar.",
                    ];
                }
                
                $message = $templates[array_rand($templates)];
            } else {
                return; 
            }
        } 
        
        else {
            return; // Tipe lain (seperti Izin/Sakit manual) ditangani job lain
        }

        // 4. KONFIGURASI API & MULTI DEVICE
        $apiUrl = 'https://app.wapanels.com/api/create-message';
        $authKey = config('app.wapanels_authkey');
        $appKeys = config('app.wapanels_appkeys'); 

        if (empty($appKeys) || empty($authKey)) {
            // Log::error('GAGAL WA: AuthKey/AppKeys kosong.'); // Uncomment untuk debug
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

            // Optional: Log sukses/gagal
            // if ($response->successful()) { ... }

        } catch (\Exception $e) {
            Log::error("Exception WA: " . $e->getMessage());
        }
    }
}