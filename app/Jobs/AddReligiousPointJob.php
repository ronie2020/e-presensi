<?php

namespace App\Jobs;

use App\Models\AttendanceSiswa;
use App\Models\Discipline;
use App\Models\DisciplineType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AddReligiousPointJob implements ShouldQueue
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
        // 1. Cek apakah data siswa ada
        if (!$this->attendance->student) {
            return;
        }

        $activity = strtolower($this->attendance->activity ?? $this->attendance->type);
        
        // 2. Filter: Hanya proses jika aktivitas adalah Dhuha atau Dhuhur
        // Sesuaikan kata kuncinya dengan data yang masuk dari QR Code
        $isReligious = str_contains($activity, 'dhuha') || 
                       str_contains($activity, 'dhuhur') || 
                       str_contains($activity, 'duhur');

        if (!$isReligious) {
            return; // Stop jika bukan absen keagamaan
        }

        // 3. Cari Tipe Poin di Database (Hardcode nama atau ID-nya)
        // Pastikan Anda punya Master Data "Sholat Berjamaah" atau "Ibadah Harian" bertipe 'Prestasi'
        // Tips: Lebih aman pakai ID jika nama sering berubah, tapi pakai Nama lebih mudah dibaca dev.
        $pointType = DisciplineType::where('name', 'LIKE', '%Sholat%')
                        ->where('type', 'Prestasi') // Pastikan tipenya Kebaikan/Prestasi
                        ->first();

        // Jika tidak ditemukan master datanya, buat default atau return
        if (!$pointType) {
            Log::warning("Auto-Point Gagal: Master data 'DisciplineType' untuk Sholat tidak ditemukan.");
            return;
        }

        // 4. CEK DUPLIKASI (PENTING!)
        // Jangan sampai scan 2x dapat poin 10. Cek apakah hari ini sudah dapat poin untuk tipe ini?
        $today = Carbon::today()->toDateString();
        $alreadyHasPoint = Discipline::where('student_id', $this->attendance->student_id)
                            ->where('discipline_type_id', $pointType->id)
                            ->where('date', $today)
                            ->exists();

        if ($alreadyHasPoint) {
            // Log::info("Siswa {$this->attendance->student->name} sudah dapat poin sholat hari ini.");
            return;
        }

        // 5. Tambahkan Poin
        try {
            Discipline::create([
                'student_id'         => $this->attendance->student_id,
                'discipline_type_id' => $pointType->id,
                'date'               => $today,
                'notes'              => "Otomatis dari Scan " . ucwords($activity),
                'user_id'            => 1, // ID Admin/Sistem (sesuaikan dengan ID user sistem Anda)
            ]);

            Log::info("Auto-Point Berhasil: +{$pointType->point_value} poin untuk {$this->attendance->student->name} ({$activity})");

        } catch (\Exception $e) {
            Log::error("Gagal input auto-point: " . $e->getMessage());
        }
    }
}