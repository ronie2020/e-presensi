<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendGeneralWaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phoneNumber;
    protected $message;

    public function __construct($phoneNumber, $message)
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
    }

    public function handle(): void
    {
        if (empty($this->phoneNumber)) return;

        // 1. Anti-Banned: Jeda lebih lama untuk Broadcast (5-10 detik)
        sleep(rand(5, 10));

        // 2. Variasi Pesan (Anti-Spam Filter)
        // Menambahkan ID unik kecil di akhir pesan agar setiap pesan dianggap "berbeda" oleh sistem WA
        $uniqueId = substr(md5(uniqid()), 0, 4);
        $finalMessage = $this->message . "\n\n_Ref: #{$uniqueId}_";

        // 3. Konfigurasi Multi Device
        $apiUrl  = 'https://app.wapanels.com/api/create-message';
        $authKey = config('app.wapanels_authkey');
        $appKeys = config('app.wapanels_appkeys');

        if (empty($appKeys) || empty($authKey)) {
            Log::error('GAGAL Broadcast: Config kosong.');
            return;
        }

        // Load Balancer: Pilih device acak
        $selectedAppKey = $appKeys[array_rand($appKeys)];

        // 4. Kirim Pesan
        try {
            $response = Http::asForm()->post($apiUrl, [
                'appkey'  => $selectedAppKey,
                'authkey' => $authKey,
                'to'      => $this->phoneNumber,
                'message' => $finalMessage,
                'sandbox' => 'false'
            ]);

            if ($response->failed()) {
                Log::error("Gagal Broadcast WA: " . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Exception Broadcast WA: ' . $e->getMessage());
        }
    }
}