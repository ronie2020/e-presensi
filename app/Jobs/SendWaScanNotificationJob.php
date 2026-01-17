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

class SendWaScanNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attendance;

    // --- KONFIGURASI RETRY (PERCOBAAN ULANG) ---
    // Jika gagal, sistem akan mencoba kirim ulang maksimal 3 kali
    public $tries = 3;
    
    // Jeda waktu (detik) sebelum mencoba ulang (Exponential Backoff)
    public $backoff = 10;

    public function __construct(AttendanceSiswa $attendance)
    {
        $this->attendance = $attendance;
    }

    public function handle(): void
    {
        // 1. Jeda Acak (Anti-Banned WA)
        sleep(rand(2, 6));

        if (!$this->attendance->student) return;

        $student   = $this->attendance->student;
        
        // --- 2. STANDARISASI NOMOR HP ---
        // Hapus karakter selain angka
        $nomorWA = preg_replace('/[^0-9]/', '', $student->parent_wa_number);
        
        // Ubah 08xx menjadi 628xx
        if (substr($nomorWA, 0, 2) == '08') {
            $nomorWA = '62' . substr($nomorWA, 1);
        }
        
        // Validasi panjang nomor (minimal 10 digit)
        if (strlen($nomorWA) < 10) {
            // Nomor tidak valid, jangan diretry (langsung return)
            Log::warning("WA Skip: Nomor tidak valid untuk siswa {$student->name}");
            return;
        }

        // --- 3. SIAPKAN DATA ---
        $namaSiswa = $student->name;
        $timeInRaw = $this->attendance->time_in;
        $timeOutRaw = $this->attendance->time_out;
        $jamScan = ($timeInRaw && $timeInRaw != '00:00:00') ? Carbon::parse($timeInRaw)->format('H:i') : '-';
        $jamPulang = (!empty($timeOutRaw) && $timeOutRaw != '00:00:00') ? Carbon::parse($timeOutRaw)->format('H:i') : null;
        $tanggal   = Carbon::parse($this->attendance->attendance_date)->translatedFormat('d F Y');
        $tipeAbsen = strtolower($this->attendance->type); 
        $aktivitas = strtolower($this->attendance->activity ?? $tipeAbsen);
        $status    = $this->attendance->status;
        $catatan   = $this->attendance->notes ?? '';

        // --- 4. SALAM KONTEKSTUAL (Berdasarkan Jam) ---
        $jamSekarang = now()->hour;
        $waktu = ($jamSekarang < 11) ? 'Pagi' : (($jamSekarang < 15) ? 'Siang' : (($jamSekarang < 18) ? 'Sore' : 'Malam'));
        
        $salam = collect([
            "Assalamualaikum Wr. Wb.", 
            "Selamat {$waktu},", 
            "Yth. Wali Murid,", 
            "Salam Hormat,"
        ])->random();

        $message = "";

        // --- LOGIKA PESAN (SKENARIO) ---

        // A. SKENARIO PULANG
        if (!empty($jamPulang)) {
            $templates = [
                "*LAPORAN KEPULANGAN*\n\n{$salam}\nAnanda *{$namaSiswa}* telah menyelesaikan kegiatan belajar dan meninggalkan sekolah.\n\n⏰ Jam Pulang: {$jamPulang} WIB\n\nMohon dipantau kepulangannya. Terima kasih.",
                "*INFO PULANG SEKOLAH*\n\n{$salam}\nDiinformasikan bahwa Ananda *{$namaSiswa}* sudah absen pulang pada pukul *{$jamPulang} WIB*.\nHati-hati di jalan dan selamat beristirahat.\n\n- Admin Sekolah -",
            ];
            $message = $templates[array_rand($templates)];
        }
        // B. SKENARIO MASUK / TERLAMBAT
        elseif (in_array($status, ['Hadir', 'Terlambat']) && empty($jamPulang)) {
            if (str_contains($aktivitas, 'harian') || $tipeAbsen == 'harian' || $tipeAbsen == 'masuk') {
                if ($status == 'Terlambat') {
                    $templates = [
                        "⚠️ *INFO KETERLAMBATAN*\n\n{$salam}\nKami informasikan Ananda *{$namaSiswa}* telah tiba di sekolah namun tercatat *TERLAMBAT*.\n\n📅 Tanggal: {$tanggal}\n⏰ Jam Masuk: {$jamScan} WIB\n📝 Info: _{$catatan}_\n\nMohon pembinaan agar esok datang lebih awal.",
                        "*LAPORAN KEHADIRAN*\n\n{$salam}\nAnanda *{$namaSiswa}* hadir di sekolah pada pukul {$jamScan} WIB.\nStatus: *TERLAMBAT*\nKeterangan: {$catatan}\n\nTerima kasih atas perhatiannya."
                    ];
                } else {
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
        } else {
            return;
        }

        // --- 5. KIRIM KE API DENGAN ERROR HANDLING ---
        $authKey = config('app.wapanels_authkey');
        $appKeys = config('app.wapanels_appkeys'); 

        if (empty($appKeys) || empty($authKey)) {
            // Throw exception agar masuk retry mechanism
            throw new Exception("Konfigurasi API WA Kosong"); 
        }

        $selectedAppKey = $appKeys[array_rand($appKeys)];
        $apiUrl = 'https://app.wapanels.com/api/create-message';

        // Menggunakan timeout 15 detik untuk mencegah process hanging
        $response = Http::timeout(15)->asForm()->post($apiUrl, [
            'appkey' => $selectedAppKey,
            'authkey' => $authKey,
            'to' => $nomorWA,
            'message' => $message,
            'sandbox' => 'false'
        ]);

        // Cek jika API merespon failure (misal kuota habis, device disconnect)
        if ($response->failed()) {
            throw new Exception("API WA Error: " . $response->body());
        }
    }

    // --- 6. HANDLER JIKA GAGAL TOTAL (Setelah 3x percobaan) ---
    public function failed(Throwable $exception): void
    {
        Log::error("WA SCAN GAGAL FINAL [ID: {$this->attendance->id}]: " . $exception->getMessage());
        
        // Opsional: Update status di database agar admin tahu notif gagal
        // $this->attendance->update(['notification_status' => 'failed']);
    }
}