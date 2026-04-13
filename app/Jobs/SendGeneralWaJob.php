<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendGeneralWaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phoneNumber;
    protected $message;

    // Retry broadcast lebih penting karena sering kena rate limit
    public $tries = 3;
    public $backoff = 20; // Jeda lebih lama untuk broadcast

    public function __construct($phoneNumber, $message)
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
    }

    public function handle(WhatsAppService $waService): void
    {
        if (empty($this->phoneNumber)) return;

        // 1. Anti-Banned: Jeda lebih lama untuk Broadcast (5-10 detik)
        sleep(rand(5, 10));

        // 2. Validasi Nomor HP (Gunakan service agar formatnya seragam)
        $nomorWA = $waService->formatPhoneNumber($this->phoneNumber);
        if (!$nomorWA) {
            Log::warning("Broadcast Skip: Nomor tidak valid {$this->phoneNumber}");
            return;
        }

        // 3. Variasi Pesan (Anti-Spam Filter) & Ref ID
        $uniqueId = substr(md5(uniqid()), 0, 4);
        $finalMessage = $this->message . "\n\n_Ref: #{$uniqueId}_";

        // 4. Kirim Pesan (Menggunakan Service)
        $waService->sendMessage($nomorWA, $finalMessage);
    }

    public function failed(Throwable $exception): void
    {
        Log::error("BROADCAST GAGAL FINAL [To: {$this->phoneNumber}]: " . $exception->getMessage());
    }
}