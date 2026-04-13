<?php

namespace App\Jobs;

use App\Models\AttendanceSiswa;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Throwable;

class SendWaScanNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attendance;
    public $tries = 3;
    public $backoff = 10;

    public function __construct(AttendanceSiswa $attendance)
    {
        $this->attendance = $attendance;
    }

    public function handle(WhatsAppService $waService): void
    {
        sleep(rand(2, 6)); // Anti-Banned Jeda

        if (!$this->attendance->student) return;

        $student = $this->attendance->student;
        
        // --- SMART FALLBACK LOGIC ---
        // Prioritas 1: parent_wa_number
        // Prioritas 2: parent_phone
        // Prioritas 3: phone (Nomor HP utama di biodata)
        $targetNumber = $student->parent_wa_number ?: ($student->parent_phone ?: $student->phone);

        // 1. Validasi Nomor (Menggunakan Service)
        $nomorWA = $waService->formatPhoneNumber($targetNumber);
        if (!$nomorWA) {
            Log::warning("WA Skip: Nomor tidak valid untuk siswa {$student->name}");
            return;
        }

        // 2. Siapkan Data
        $namaSiswa = $student->name;
        $timeInRaw = $this->attendance->time_in;
        $timeOutRaw = $this->attendance->time_out;
        $jamScan = ($timeInRaw && $timeInRaw != '00:00:00') ? Carbon::parse($timeInRaw)->format('H:i') : '-';
        $jamPulang = (!empty($timeOutRaw) && $timeOutRaw != '00:00:00') ? Carbon::parse($timeOutRaw)->format('H:i') : null;
        $tanggal   = Carbon::parse($this->attendance->attendance_date)->translatedFormat('d F Y');
        $tipeAbsen = strtolower($this->attendance->type); 
        $aktivitas = strtolower($this->attendance->activity ?? $tipeAbsen);
        $status    = $this->attendance->status;

        // 3. Salam Kontekstual (Menggunakan Service - menambah variasi)
        $salam = $waService->getGreeting();
        $message = "";

        // 4. Logika Skenario Pesan & Template Variatif
        if (!empty($jamPulang)) {
            // ============================================
            // TEMPLATE PULANG (5 Variasi)
            // ============================================
            $templates = [
                "*LAPORAN KEPULANGAN*\n\n{$salam}\nAnanda *{$namaSiswa}* telah menyelesaikan kegiatan belajar...\n⏰ Jam Pulang: *{$jamPulang} WIB*\n\nMohon dipantau kepulangannya. Terima kasih.",
                "🔔 *NOTIFIKASI PULANG SEKOLAH*\n\n{$salam}\nDiinformasikan bahwa ananda *{$namaSiswa}* sudah melakukan absensi pulang pada pukul *{$jamPulang} WIB*.\n\nHati-hati di jalan dan selamat beristirahat.",
                "*INFO PRESENSI - PULANG*\n\n{$salam} Ayah/Bunda,\nKami memberitahukan bahwa *{$namaSiswa}* telah meninggalkan area sekolah pada *{$jamPulang} WIB*.\n\nTerima kasih atas kerja samanya.",
                "🏫 *UPDATE KEHADIRAN*\n\n{$salam}\nWaktu belajar hari ini telah usai. Ananda *{$namaSiswa}* tercatat pulang pukul *{$jamPulang} WIB*.\n\nMohon pastikan ananda tiba di rumah dengan selamat.",
                "*PESAN OTOMATIS SEKOLAH*\n\n{$salam}\nAnanda *{$namaSiswa}* baru saja melakukan scan kepulangan jam *{$jamPulang} WIB*.\nTerima kasih telah mempercayakan pendidikan ananda kepada kami."
            ];
            $message = $templates[array_rand($templates)];

        } elseif (in_array($status, ['Hadir', 'Terlambat']) && empty($jamPulang)) {
            if (str_contains($aktivitas, 'harian') || in_array($tipeAbsen, ['harian', 'masuk'])) {
                if ($status == 'Terlambat') {
                    // ============================================
                    // TEMPLATE TERLAMBAT (4 Variasi)
                    // ============================================
                    $templates = [
                        "⚠️ *INFO KETERLAMBATAN*\n\n{$salam}\nKami informasikan Ananda *{$namaSiswa}* telah tiba di sekolah namun tercatat *TERLAMBAT*.\n\n📅 Tanggal: {$tanggal}\n⏰ Jam Masuk: *{$jamScan} WIB*\n\nMohon kerjasamanya untuk mengingatkan ananda agar berangkat lebih awal.",
                        "🚨 *NOTIFIKASI KEHADIRAN (TERLAMBAT)*\n\n{$salam}\nHari ini ({$tanggal}), ananda *{$namaSiswa}* sampai di sekolah pukul *{$jamScan} WIB* (Melewati batas waktu masuk).\n\nMari bersama-sama mendisiplinkan ananda.",
                        "*CATATAN KEDISIPLINAN*\n\n{$salam} Ayah/Bunda,\nKami beritahukan bahwa *{$namaSiswa}* hadir di sekolah terlambat hari ini pada pukul *{$jamScan} WIB*.\n\nTerima kasih atas perhatiannya.",
                        "*LAPORAN PRESENSI*\n\n{$salam}\nAnanda *{$namaSiswa}* telah tiba di sekolah pukul *{$jamScan} WIB* dengan status *Terlambat*.\n\nSemoga besok bisa tiba lebih awal."
                    ];
                } else {
                    // ============================================
                    // TEMPLATE HADIR TEPAT WAKTU (5 Variasi)
                    // ============================================
                    $templates = [
                        "*LAPORAN KEHADIRAN SISWA*\n\n{$salam}\nAnanda *{$namaSiswa}* telah tiba di sekolah dengan selamat.\n\n📅 Tanggal: {$tanggal}\n⏰ Pukul: *{$jamScan} WIB*\n✅ Status: TEPAT WAKTU",
                        "🟢 *NOTIFIKASI MASUK SEKOLAH*\n\n{$salam}\nKami informasikan ananda *{$namaSiswa}* sudah hadir di sekolah pada *{$jamScan} WIB*.\n\nSemoga ananda mendapatkan ilmu yang bermanfaat hari ini.",
                        "*INFO PRESENSI KEHADIRAN*\n\n{$salam} Ayah/Bunda,\nAnanda *{$namaSiswa}* telah melakukan absen masuk pukul *{$jamScan} WIB* (Tepat Waktu).\n\nTerima kasih atas dukungan Ayah/Bunda.",
                        "🏫 *UPDATE KEHADIRAN*\n\n{$salam}\nAlhamdulillah, ananda *{$namaSiswa}* sudah berada di lingkungan sekolah sejak pukul *{$jamScan} WIB*.\n\nSelamat beraktivitas kembali.",
                        "*PESAN OTOMATIS SEKOLAH*\n\n{$salam}\nKehadiran ananda *{$namaSiswa}* hari ini ({$tanggal}) tercatat pada pukul *{$jamScan} WIB*.\n\nTerima kasih."
                    ];
                }
                $message = $templates[array_rand($templates)];
            } else {
                return; 
            }
        } else {
            return;
        }

        // --- 5. INJEKSI UNIQUE REFERENCE ID (ANTI-SPAM) ---
        // Membuat string acak seperti: _Ref: #a1b2_ di akhir pesan
        // Ini memastikan hash text di WA server selalu berbeda
        $uniqueId = substr(md5(uniqid()), 0, 4);
        $finalMessage = $message . "\n\n_Ref: #{$uniqueId}_";

        // 6. Kirim Pesan (Menggunakan Service)
        // Jika API error, exception akan otomatis di-throw dari dalam service
        $waService->sendMessage($nomorWA, $finalMessage);
    }

    public function failed(Throwable $exception): void
    {
        Log::error("WA SCAN GAGAL FINAL [ID: {$this->attendance->id}]: " . $exception->getMessage());
    }
}