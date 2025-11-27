<?php

namespace App\Jobs;

use App\Models\Achievement;
use App\Models\Discipline;
use App\Models\DisciplineType;
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

    public function __construct(Achievement $achievement)
    {
        $this->achievement = $achievement;
    }

    public function handle(): void
    {
        // 1. Validasi: Hanya proses jika yang berprestasi adalah SISWA
        if ($this->achievement->type !== 'Siswa' || !$this->achievement->student_id) {
            return;
        }

        // 2. Konfigurasi Poin Berdasarkan Tingkat (Bisa Anda ubah sesuai kebijakan sekolah)
        $pointsMap = [
            'Sekolah'       => 10,  // Poin terkecil
            'Kecamatan'     => 15,
            'Kabupaten'     => 25,
            'Provinsi'      => 50,
            'Nasional'      => 75,
            'Internasional' => 100, // Poin terbesar
        ];

        $level = $this->achievement->level; // Contoh: 'Kabupaten'
        $points = $pointsMap[$level] ?? 10; // Default 10 jika level tidak ada di list

        // 3. Cari atau Buat Kategori Poin (DisciplineType)
        // Kita beri nama misal: "Prestasi Tingkat Kabupaten"
        // Penting: Type harus 'Kebaikan' agar masuk ke rekap poin positif
        $typeName = "Prestasi Tingkat " . $level;
        
        $pointType = DisciplineType::firstOrCreate(
            ['name' => $typeName],
            [
                'type' => 'Kebaikan', 
                'point_value' => $points,
                'description' => "Poin penghargaan otomatis dari prestasi $level"
            ]
        );

        // Update poin jika ternyata data lama poinnya beda (Opsional, agar konsisten)
        if ($pointType->point_value != $points) {
            $pointType->update(['point_value' => $points]);
        }

        // 4. Simpan ke Catatan Disiplin
        try {
            Discipline::create([
                'student_id'         => $this->achievement->student_id,
                'discipline_type_id' => $pointType->id,
                'date'               => $this->achievement->date, // Sesuaikan tanggal poin dengan tanggal prestasi
                'notes'              => "Otomatis: " . $this->achievement->title, // Catatan: Judul Juara
                'recorded_by_user_id' => 1, // ID System/Admin
            ]);

            Log::info("Poin Prestasi Berhasil: {$this->achievement->student->name} dapat {$points} poin ($level)");

        } catch (\Exception $e) {
            Log::error("Gagal input poin prestasi: " . $e->getMessage());
        }
    }
}