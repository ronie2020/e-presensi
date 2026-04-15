<?php

namespace App\Jobs;

use App\Models\Achievement;
use App\Models\DisciplineRecord; 
use App\Models\DisciplineType;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AddAchievementPointJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $achievement;
    protected $userId;

    // === PERBAIKAN: Menerima userId dari Controller ===
    public function __construct(Achievement $achievement, $userId = null)
    {
        $this->achievement = $achievement;
        $this->userId = $userId;
    }

    public function handle(): void
    {
        // 1. Validasi: Hanya proses jika yang berprestasi adalah SISWA
        if ($this->achievement->type !== 'Siswa' || !$this->achievement->student_id) {
            return;
        }

        // 2. Konfigurasi Poin Berdasarkan Tingkat
        $pointsMap = [
            'Sekolah'       => 10,
            'Kecamatan'     => 15,
            'Kabupaten'     => 25,
            'Provinsi'      => 50,
            'Nasional'      => 75,
            'Internasional' => 100,
        ];

        $level = $this->achievement->level; 
        $points = $pointsMap[$level] ?? 10; 

        // 3. Cari atau Buat Kategori Poin (DisciplineType)
        $typeName = "Prestasi Tingkat " . $level;
        
        $pointType = DisciplineType::firstOrCreate(
            ['name' => $typeName],
            [
                'type' => 'Kebaikan', 
                'point_value' => $points,
                'description' => "Poin penghargaan otomatis dari prestasi $level"
            ]
        );

        if ($pointType->point_value != $points) {
            $pointType->update(['point_value' => $points]);
        }

        // === PERBAIKAN: Fallback ke User Admin pertama jika Job berjalan via console/seeder ===
        $recordedBy = $this->userId;
        if (!$recordedBy) {
            $adminUser = User::first(); // Ambil user pertama sebagai fallback (biasanya admin)
            $recordedBy = $adminUser ? $adminUser->id : null;
        }

        // 4. Simpan ke Catatan Disiplin
        try {
            DisciplineRecord::create([
                'student_id'         => $this->achievement->student_id,
                'discipline_type_id' => $pointType->id,
                'date'               => $this->achievement->date, 
                'notes'              => "Otomatis: " . $this->achievement->title, 
                'recorded_by_user_id' => $recordedBy, // Menggunakan User ID yang valid
            ]);

            Log::info("Poin Prestasi Berhasil: {$this->achievement->student->name} dapat {$points} poin ($level)");

        } catch (\Exception $e) {
            Log::error("Gagal input poin prestasi: " . $e->getMessage());
        }
    }
}