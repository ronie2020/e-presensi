<?php

namespace App\Imports;

use App\Models\TeachingLoad;
use App\Models\User;
use App\Models\Subject;
use App\Models\SchoolClass;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TeachingLoadImport implements ToCollection, WithHeadingRow
{
    public int $successCount = 0;
    public array $errors = [];

    /**
     * Memproses setiap baris dari file Excel/CSV
     */
    public function collection(Collection $rows)
    {
        // Preload master data untuk lookup cepat
        $allClasses = SchoolClass::all();
        $allSubjects = Subject::all();

        foreach ($rows as $index => $row) {
            $lineNumber = $index + 2; // header baris 1, data baris 2 dst

            $rawGuru = isset($row['nama_guru']) ? trim(strval($row['nama_guru'])) : '';
            $rawMapel = isset($row['nama_mapel']) ? trim(strval($row['nama_mapel'])) : '';
            $rawKelas = isset($row['nama_kelas']) ? trim(strval($row['nama_kelas'])) : '';
            $rawJp = isset($row['jp']) ? trim(strval($row['jp'])) : '';

            // Abaikan baris kosong
            if (empty($rawGuru) && empty($rawMapel) && empty($rawKelas) && empty($rawJp)) {
                continue;
            }

            // Abaikan baris template contoh jika ada
            if (str_contains(mb_strtolower($rawGuru), 'contoh') || str_contains(mb_strtolower($rawKelas), 'x-a')) {
                // jika nama guru contoh "Budi Santoso" tapi tidak ada di db, biar masuk ke pengecekan normal
            }

            if (empty($rawGuru) || empty($rawMapel) || empty($rawKelas) || empty($rawJp)) {
                $this->errors[] = "Baris {$lineNumber}: Kolom belum lengkap (Guru, Mapel, Kelas, atau JP kosong).";
                continue;
            }

            $jp = (int) $rawJp;
            if ($jp <= 0 || $jp > 40) {
                $this->errors[] = "Baris {$lineNumber}: Jumlah JP ('{$rawJp}') tidak valid (harus angka 1 - 40).";
                continue;
            }

            // 1. Cari Guru (Coba exact, lalu LIKE)
            $teacher = User::where('name', $rawGuru)->first() 
                ?: User::where('name', 'LIKE', '%' . $rawGuru . '%')->first();

            // 2. Cari Mapel (Coba exact, lalu LIKE)
            $subject = $allSubjects->first(function($s) use ($rawMapel) {
                return strcasecmp($s->name, $rawMapel) === 0;
            });
            if (!$subject) {
                $subject = Subject::where('name', 'LIKE', '%' . $rawMapel . '%')->first();
            }

            // 3. Cari Kelas (Coba exact, lalu LIKE, lalu normalisasi strip/spasi misalnya "7-A" -> "7A")
            $cleanKelas = mb_strtolower(str_replace(['-', ' '], '', $rawKelas));
            $class = $allClasses->first(function($c) use ($rawKelas, $cleanKelas) {
                $cleanC = mb_strtolower(str_replace(['-', ' '], '', $c->name));
                return strcasecmp($c->name, $rawKelas) === 0 || $cleanC === $cleanKelas;
            });
            if (!$class) {
                $class = SchoolClass::where('name', 'LIKE', '%' . $rawKelas . '%')->first();
            }

            // Validasi keberadaan Guru, Mapel, dan Kelas
            if ($teacher && $subject && $class) {
                TeachingLoad::updateOrCreate(
                    [
                        'subject_id' => $subject->id,
                        'class_id'   => $class->id,
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'hours_per_week' => $jp
                    ]
                );
                $this->successCount++;
            } else {
                $missing = [];
                if (!$teacher) $missing[] = "Guru '{$rawGuru}'";
                if (!$subject) $missing[] = "Mapel '{$rawMapel}'";
                if (!$class) $missing[] = "Kelas '{$rawKelas}'";
                
                $errMsg = "Baris {$lineNumber}: Tidak ditemukan data untuk " . implode(', ', $missing) . ".";
                $this->errors[] = $errMsg;
                Log::warning("TeachingLoadImport dilewati -> " . $errMsg);
            }
        }
    }
}