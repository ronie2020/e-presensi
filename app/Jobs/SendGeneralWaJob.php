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

        // Jeda agar aman dari banned
        sleep(rand(5, 10));

        $apiUrl = 'https://app.wapanels.com/api/create-message';
        $authKey = config('app.wapanels_authkey');
        
        // --- PERBAIKAN: Handle String to Array (Multi Device) ---
        $appKeysRaw = config('app.wapanels_appkeys');
        
        if (is_string($appKeysRaw)) {
            $appKeys = explode(',', str_replace(' ', '', $appKeysRaw));
        } elseif (is_array($appKeysRaw)) {
            $appKeys = $appKeysRaw;
        } else {
            $appKeys = [];
        }

        if (empty($appKeys) || empty($appKeys[0])) {
            Log::error('WAPANELS_APP_KEYS kosong.');
            return; 
        }
        
        // Rotasi Device
        $appKey = $appKeys[array_rand($appKeys)]; 

        try {
            $response = Http::post($apiUrl, [
                'appkey' => $appKey,
                'authkey' => $authKey,
                'to' => $this->phoneNumber,
                'message' => $this->message,
                'sandbox' => 'false'
            ]);

            if ($response->failed()) {
                Log::error("Gagal Broadcast WA: " . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Gagal kirim Broadcast WA: ' . $e->getMessage());
        }
    }
}