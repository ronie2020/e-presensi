<?php

namespace App\Imports;

use App\Models\TeachingLoad;
use App\Models\User;
use App\Models\Subject;
use App\Models\SchoolClass;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log; // Tambahan untuk mencatat error ke Log

class TeachingLoadImport implements ToCollection, WithHeadingRow
{
    /**
     * Memproses setiap baris dari file Excel
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Pastikan baris memiliki header yang sesuai
            if (!isset($row['nama_guru']) || !isset($row['nama_mapel']) || !isset($row['nama_kelas']) || !isset($row['jp'])) {
                continue; // Skip baris ini jika tidak valid
            }

            // Cari ID berdasarkan teks Nama
            $teacher = User::where('name', 'LIKE', '%' . trim($row['nama_guru']) . '%')->first();
            $subject = Subject::where('name', 'LIKE', '%' . trim($row['nama_mapel']) . '%')->first();
            $class = SchoolClass::where('name', 'LIKE', '%' . trim($row['nama_kelas']) . '%')->first();

            // Hanya input jika Guru, Mapel, dan Kelas tersebut benar-benar ada di database
            if ($teacher && $subject && $class) {
                // SOLUSI BENTROK GURU: 
                // Kunci pencarian hanya menggunakan Mapel dan Kelas. 
                // Jika sudah ada (misal Agustiyanto di 7A), maka TIMPA dengan guru dari Excel (Mistam).
                TeachingLoad::updateOrCreate(
                    [
                        'subject_id' => $subject->id,
                        'class_id'   => $class->id,
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'hours_per_week' => (int) $row['jp']
                    ]
                );
            } else {
                // SOLUSI DATA HILANG (SILENT SKIP):
                // Jika data tidak masuk (seperti PJOK), catat alasan persisnya di file storage/logs/laravel.log
                $missing = [];
                if (!$teacher) $missing[] = "Guru: '" . trim($row['nama_guru']) . "'";
                if (!$subject) $missing[] = "Mapel: '" . trim($row['nama_mapel']) . "'";
                if (!$class) $missing[] = "Kelas: '" . trim($row['nama_kelas']) . "'";
                
                Log::warning("Baris Excel dilewati karena tidak ditemukan di DB -> " . implode(', ', $missing));
            }
        }
    }
}