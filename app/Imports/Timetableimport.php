<?php

namespace App\Imports;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Timeslot;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Import Jadwal Pelajaran dari file Excel yang disusun di luar aplikasi.
 *
 * Format kolom yang diharapkan (header di baris pertama):
 * Hari | Slot Waktu | Kelas | Mata Pelajaran | Guru
 *
 * - "Hari"          : Senin / Selasa / Rabu / Kamis / Jumat
 * - "Slot Waktu"    : harus sama persis dengan nama Timeslot di sistem (mis. "Jam ke-1")
 * - "Kelas"         : harus sama persis dengan nama kelas di sistem
 * - "Mata Pelajaran": harus sama persis dengan nama mapel di sistem
 * - "Guru"          : harus sama persis dengan nama guru di sistem
 */
class TimetableImport implements ToCollection, WithHeadingRow
{
    /** @var string[] Daftar pesan error per baris, untuk ditampilkan ke user */
    public array $errors = [];

    public int $successCount = 0;

    protected bool $overwrite;

    protected array $validDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

    // TAMBAHKAN BLOK KODE INI
    /**
     * Beri tahu Laravel Excel bahwa baris header sebenarnya ada di baris ke-2
     */
    public function headingRow(): int
    {
        return 2;
    }
    // AKHIR BLOK KODE TAMBAHAN

    public function __construct(bool $overwrite = false)
    {
        $this->overwrite = $overwrite;
    }

    public function collection(Collection $rows)
    {
        // Preload data master jadi lookup map (case-insensitive) biar tidak query berulang per baris
        $classes = SchoolClass::pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [mb_strtolower(trim($name)) => $id]);

        $teachers = User::role(['Guru', 'Guru Mata Pelajaran', 'Wali Kelas'])
            ->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [mb_strtolower(trim($name)) => $id]);

        $subjects = Subject::pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [mb_strtolower(trim($name)) => $id]);

        // Slot istirahat sengaja tidak diikutkan karena tidak mungkin diisi mata pelajaran
        $timeslots = Timeslot::where('is_break', false)
            ->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [mb_strtolower($this->normalize($name)) => $id]);

        // Cache bentrok di level file (antar baris dalam 1 file yang sama juga harus saling cek)
        $classSlotCache = [];
        $teacherSlotCache = [];

        foreach ($rows as $i => $row) {
            $lineNumber = $i + 2; // baris 1 = header

            $day = trim($row['hari'] ?? '');
            $slotName = $this->normalize($row['slot_waktu'] ?? '');
            $className = trim($row['kelas'] ?? '');
            $subjectName = trim($row['mata_pelajaran'] ?? '');
            $teacherName = trim($row['guru'] ?? '');

            if (!$day && !$slotName && !$className && !$subjectName && !$teacherName) {
                continue; // baris kosong, lewati diam-diam
            }

            if (!$day || !$slotName || !$className || !$subjectName || !$teacherName) {
                $this->errors[] = "Baris {$lineNumber}: ada kolom yang kosong, baris dilewati.";
                continue;
            }

            if (!in_array($day, $this->validDays, true)) {
                $this->errors[] = "Baris {$lineNumber}: hari '{$day}' tidak dikenali (harus Senin-Jumat).";
                continue;
            }

            $classId = $classes[mb_strtolower($className)] ?? null;
            $teacherId = $teachers[mb_strtolower($teacherName)] ?? null;
            $subjectId = $subjects[mb_strtolower($subjectName)] ?? null;
            $timeslotId = $timeslots[mb_strtolower($slotName)] ?? null;

            if (!$classId) {
                $this->errors[] = "Baris {$lineNumber}: kelas '{$className}' tidak ditemukan.";
                continue;
            }
            if (!$teacherId) {
                $this->errors[] = "Baris {$lineNumber}: guru '{$teacherName}' tidak ditemukan.";
                continue;
            }
            if (!$subjectId) {
                $this->errors[] = "Baris {$lineNumber}: mata pelajaran '{$subjectName}' tidak ditemukan.";
                continue;
            }
            if (!$timeslotId) {
                $this->errors[] = "Baris {$lineNumber}: slot waktu '{$slotName}' tidak ditemukan (atau itu jam istirahat).";
                continue;
            }

            $classKey = "{$day}-{$timeslotId}-{$classId}";
            $teacherKey = "{$day}-{$timeslotId}-{$teacherId}";

            $existingClassEntry = Timetable::where('day_of_week', $day)
                ->where('timeslot_id', $timeslotId)
                ->where('class_id', $classId)
                ->first();

            // Bentrok kelas: slot itu untuk kelas ini sudah terisi mapel lain
            if (($existingClassEntry || isset($classSlotCache[$classKey])) && !$this->overwrite) {
                $this->errors[] = "Baris {$lineNumber}: kelas '{$className}' sudah punya jadwal di {$day}, slot '{$slotName}'.";
                continue;
            }

            // Bentrok guru: guru ini sudah mengajar kelas LAIN di hari & slot yang sama
            $teacherBusyElsewhere = Timetable::where('day_of_week', $day)
                ->where('timeslot_id', $timeslotId)
                ->where('teacher_id', $teacherId)
                ->where('class_id', '!=', $classId)
                ->exists();

            if ($teacherBusyElsewhere || isset($teacherSlotCache[$teacherKey])) {
                $this->errors[] = "Baris {$lineNumber}: guru '{$teacherName}' sudah mengajar kelas lain di {$day}, slot '{$slotName}'.";
                continue;
            }

            if ($existingClassEntry && $this->overwrite) {
                $existingClassEntry->update([
                    'teacher_id' => $teacherId,
                    'subject_id' => $subjectId,
                ]);
            } else {
                Timetable::create([
                    'day_of_week' => $day,
                    'timeslot_id' => $timeslotId,
                    'class_id' => $classId,
                    'teacher_id' => $teacherId,
                    'subject_id' => $subjectId,
                    'status' => 'published',
                ]);
            }

            $classSlotCache[$classKey] = true;
            $teacherSlotCache[$teacherKey] = true;
            $this->successCount++;
        }
    }

    /**
     * Rapikan teks dari Excel: hapus spasi berlebih & samakan tanda kutip pintar
     * (‘ ’ atau “ ”) jadi tanda kutip biasa, supaya "Jum'at" tetap cocok
     * walau Excel otomatis mengubah tanda petiknya saat diketik.
     */
    protected function normalize(string $value): string
    {
        $value = trim($value);
        return str_replace(['’', '‘'], "'", $value);
    }
}