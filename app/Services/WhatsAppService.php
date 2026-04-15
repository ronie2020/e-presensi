<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppService
{
    /**
     * Memformat nomor HP lokal ke format internasional (62)
     */
    public function formatPhoneNumber(?string $phoneNumber): ?string
    {
        if (empty($phoneNumber)) return null;

        $nomorWA = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        if (substr($nomorWA, 0, 2) == '08') {
            $nomorWA = '62' . substr($nomorWA, 1);
        }
        
        return strlen($nomorWA) >= 10 ? $nomorWA : null;
    }

    /**
     * Menghasilkan salam kontekstual berdasarkan waktu saat ini
     */
    public function getGreeting(): string
    {
        $jamSekarang = now()->hour;
        $waktu = ($jamSekarang < 11) ? 'Pagi' : (($jamSekarang < 15) ? 'Siang' : (($jamSekarang < 18) ? 'Sore' : 'Malam'));
        
        return collect([
            "Assalamualaikum Wr. Wb.", 
            "Selamat {$waktu},", 
            "Yth. Wali Murid,", 
            "Salam Hormat,"
        ])->random();
    }

    /**
     * Mengirim pesan via API WAPanels
     */
    public function sendMessage(string $to, string $message): void
    {
        $nomorWA = $this->formatPhoneNumber($to);

        if (!$nomorWA) {
            Log::warning("WA Skip: Nomor tidak valid {$to}");
            return;
        }

        $authKey = env('WAPANELS_AUTH_KEY'); 
        $appKeysString = env('WAPANELS_APP_KEYS'); 

        if (empty($appKeysString) || empty($authKey)) {
            throw new Exception("Konfigurasi API WA Kosong di .env");
        }

        // --- PERBAIKAN: Pecah string menjadi array ---
        $appKeysArray = explode(',', $appKeysString);        
        $appKeysArray = array_map('trim', $appKeysArray);       
        $selectedAppKey = $appKeysArray[array_rand($appKeysArray)];

        $apiUrl = 'https://app.wapanels.com/api/create-message';

        $response = Http::withoutVerifying()
            ->timeout(20)
            ->asForm()
            ->post($apiUrl, [
                'appkey'  => $selectedAppKey,
                'authkey' => $authKey,
                'to'      => $nomorWA,
                'message' => $message,
                'sandbox' => 'false'
            ]);

        if ($response->failed()) {
            throw new Exception("API WA Error: " . $response->body());
        }
    }
}