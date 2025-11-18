<?php

namespace App\Jobs;

use App\Models\AttendanceSiswa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http; // Import HTTP Client Laravel
use Illuminate\Support\Facades\Log;  // Import Log
use Carbon\Carbon; // Import Carbon

class SendWaScanNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attendance;

    /**
     * Create a new job instance.
     */
    public function __construct(AttendanceSiswa $attendance)
    {
        $this->attendance = $attendance;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // TAMBAHAN PENGAMAN: Cek apakah data student ada
        if (!$this->attendance->student) {
            Log::warning('Job WA Scan dibatalkan: Siswa untuk attendance ID ' . $this->attendance->id . ' tidak ditemukan (mungkin terhapus).');
            return; // Hentikan job dengan aman
        }

        // 1. Ambil data yang kita butuhkan
        $student = $this->attendance->student;
        $nomorWA = $student->parent_wa_number;
        $namaSiswa = $student->name;
        $jamMasuk = Carbon::parse($this->attendance->time_in)->format('H:i');
        $statusAbsen = $this->attendance->notes; // "Tepat Waktu" atau "Terlambat X menit"

        // 2. Siapkan template pesan
        $message = "Info Absensi SMP NEGERI 3 LAKBOK:\n\n" .
                   "Assalamualaikum, disampaiakan dengan Hormat bahwa Ananda *{$namaSiswa}* telah melakukan absensi Harian pada:\n" .
                   "Jam: *{$jamMasuk} WIB*\n" .
                   "Status: *{$statusAbsen}*\n\n" .
                   "Terima kasih.";
        
        
        // 3. Tentukan kredensial WAPANELS Anda (BACA DARI .ENV - DIPERBARUI)
        $apiUrl = 'https://app.wapanels.com/api/create-message';
        
        // Ambil Auth Key (Kunci Akun - Singular)
        $authKey = config('app.wapanels_authkey');
        
        // Ambil DAFTAR App Key (Kunci Perangkat - Plural)
        $appKeys = config('app.wapanels_appkeys');

        // 4. PILIH SATU APPKEY SECARA ACAK DARI DAFTAR
        if (empty($appKeys) || empty($appKeys[0])) {
            Log::error('WAPANELS_APP_KEYS tidak diatur di .env atau kosong.');
            return; // Hentikan job jika tidak ada key
        }
        $appKey = $appKeys[array_rand($appKeys)]; // Ambil 1 appkey acak

        // 5. Kirim pesan menggunakan Laravel HTTP Client (menerjemahkan cURL)
        try {
            $response = Http::post($apiUrl, [
                'appkey' => $appKey,     // Gunakan appkey yang dipilih acak
                'authkey' => $authKey,   // Gunakan authkey akun yang sama
                'to' => $nomorWA,
                'message' => $message,
                'sandbox' => 'false'
            ]);
            
            Log::info('Notifikasi WA (Scan) terkirim ke: ' . $nomorWA . ' via AppKey ' . substr($appKey, 0, 5) . '... Respon: ' . $response->body());

        } catch (\Exception $e) {
            // Catat error jika pengiriman gagal
            Log::error('Gagal kirim WA notifikasi (Scan) ke ' . $nomorWA . ': ' . $e->getMessage());
        }
    }
}