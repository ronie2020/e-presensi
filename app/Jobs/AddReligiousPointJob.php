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

    public function __construct(AttendanceSiswa $attendance)
    {
        $this->attendance = $attendance;
    }

    public function handle(): void
    {
        // 1. Cek Data Siswa
        if (!$this->attendance->student) {
            return;
        }

        $activity = $this->attendance->activity ?? $this->attendance->type;
        
        // 2. Filter: Hanya proses jika Dhuha atau Dhuhur
        if (!in_array($activity, ['Dhuha', 'Dhuhur'])) {
            return; 
        }

        // 3. Cari Jenis Kebaikan di Database
        // PERBAIKAN: Ubah 'Prestasi' menjadi 'Kebaikan' agar sesuai dengan Controller
        $pointName = "Sholat " . $activity . " Berjamaah";
        
        $pointType = DisciplineType::where('name', $pointName)
                        ->where('type', 'Kebaikan') 
                        ->first();

        // 4. AUTO-CREATE (Jika belum ada jenis kebaikannya, buatkan otomatis)
        if (!$pointType) {
            try {
                $pointType = DisciplineType::create([
                    'name' => $pointName,
                    'type' => 'Kebaikan',
                    'point_value' => 5, // Default 5 poin
                    'description' => "Poin otomatis dari scan kehadiran $activity"
                ]);
                Log::info("Master Data '$pointName' dibuat otomatis.");
            } catch (\Exception $e) {
                Log::error("Gagal membuat master data poin: " . $e->getMessage());
                return;
            }
        }

        // 5. CEK DUPLIKASI (Agar tidak double poin di hari yang sama)
        $today = Carbon::today()->toDateString();
        $alreadyHasPoint = Discipline::where('student_id', $this->attendance->student_id)
                            ->where('discipline_type_id', $pointType->id)
                            ->where('date', $today)
                            ->exists();

        if ($alreadyHasPoint) {
            return; // Sudah dapat poin hari ini, skip.
        }

        // 6. EKSEKUSI SIMPAN POIN
        try {
            Discipline::create([
                'student_id'         => $this->attendance->student_id,
                'discipline_type_id' => $pointType->id,
                'date'               => $today,
                'notes'              => "Otomatis - Scan Absen $activity",
                'recorded_by_user_id' => 1, // Pastikan ID User 1 (Admin) ada di tabel users
            ]);

            Log::info("SUKSES: +5 Poin untuk {$this->attendance->student->name} ($activity)");

        } catch (\Exception $e) {
            Log::error("GAGAL Input Poin: " . $e->getMessage());
        }
    }
}