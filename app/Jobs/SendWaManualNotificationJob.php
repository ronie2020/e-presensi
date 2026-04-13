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

class SendWaManualNotificationJob implements ShouldQueue
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
        sleep(rand(3, 7));

        if (!$this->attendance->student) return;

        $student = $this->attendance->student;
        
        // --- SMART FALLBACK LOGIC ---
        // Prioritas 1: parent_wa_number
        // Prioritas 2: parent_phone
        // Prioritas 3: phone (Nomor HP utama di biodata)
        $targetNumber = $student->parent_wa_number ?: ($student->parent_phone ?: $student->phone);
        
        $nomorWA = $waService->formatPhoneNumber($targetNumber);
        
        // Berhenti jika ketiga kolom tersebut kosong atau tidak valid
        if (!$nomorWA) {
            Log::warning("WA Manual Skip: Tidak ada nomor valid untuk siswa {$student->name}");
            return; 
        }

        $namaSiswa    = $student->name;
        $statusManual = $this->attendance->status; 
        $catatan      = $this->attendance->notes ?? '-';
        $tanggal      = Carbon::parse($this->attendance->attendance_date)->translatedFormat('d F Y');

        $salam = $waService->getGreeting();

        // --- LOGIKA TEMPLATE PESAN (SINKRON DENGAN BLADE) ---
        if (in_array($statusManual, ['Sakit', 'Izin', 'Alfa'])) {
            // Template jika siswa TIDAK MASUK
            $templates = [
                "*PEMBERITAHUAN SEKOLAH*\n\n{$salam}\nKami informasikan status kehadiran Ananda:\n\nNama: *{$namaSiswa}*\nTanggal: {$tanggal}\nStatus: *{$statusManual}*\nKeterangan: _{$catatan}_\n\nTerima kasih atas perhatiannya.",
                "🔔 *INFO PRESENSI*\n\nHalo Ayah/Bunda,\nHari ini ({$tanggal}), Ananda *{$namaSiswa}* tercatat tidak mengikuti KBM dengan keterangan: *{$statusManual}*.\nCatatan: {$catatan}.\n\nSemoga menjadi maklum.",
                "*LAPORAN ABSENSI*\n\n{$salam}\nAnanda *{$namaSiswa}* hari ini tidak masuk sekolah.\nStatus: *{$statusManual}*\nInfo: {$catatan}\n\nTerima kasih."
            ];
        } else {
            // Template jika siswa HADIR / TERLAMBAT (Input Manual)
            $templates = [
                "*LAPORAN KEHADIRAN*\n\n{$salam}\nKami informasikan kehadiran Ananda:\n\nNama: *{$namaSiswa}*\nTanggal: {$tanggal}\nStatus: *{$statusManual}*\nCatatan: _{$catatan}_\n\nTerima kasih atas perhatiannya."
            ];
        }

        $message = $templates[array_rand($templates)];

        // --- KIRIM API ---
        $waService->sendMessage($nomorWA, $message);
        
        Log::info("WA Manual Terkirim ke {$namaSiswa} ({$statusManual}) di nomor {$nomorWA}");
    }

    public function failed(Throwable $exception): void
    {
        Log::error("WA MANUAL GAGAL FINAL [ID: {$this->attendance->id}]: " . $exception->getMessage());
    }
}