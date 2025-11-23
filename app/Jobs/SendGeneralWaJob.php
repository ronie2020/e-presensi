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

        // ANTI-BAN BROADCAST: Jeda EKSTRA PANJANG (5-10 detik)
        // Broadcast tidak harus realtime, yang penting aman.
        // Jika 500 siswa, butuh waktu sekitar 1-1.5 jam untuk selesai. Ini sangat aman.
        sleep(rand(5, 10));

        $apiUrl = 'https://app.wapanels.com/api/create-message';
        $authKey = config('app.wapanels_authkey');
        $appKeys = config('app.wapanels_appkeys');

        if (empty($appKeys) || empty($appKeys[0])) {
            Log::error('WAPANELS_APP_KEYS kosong.');
            return; 
        }
        
        // Rotasi Device tetap dilakukan
        $appKey = $appKeys[array_rand($appKeys)]; 

        try {
            Http::post($apiUrl, [
                'appkey' => $appKey,
                'authkey' => $authKey,
                'to' => $this->phoneNumber,
                'message' => $this->message,
                'sandbox' => 'false'
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal kirim Broadcast WA: ' . $e->getMessage());
        }
    }
}