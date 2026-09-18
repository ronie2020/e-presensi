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
 * Format kolom yang diharapkan (header di baris ke-2):
 * Hari | Slot Waktu | Kelas | Mata Pelajaran | Guru
 */
class TimetableImport implements ToCollection, WithHeadingRow
{
    /** @var string[] Daftar pesan error per baris, untuk ditampilkan ke user */
    public array $errors = [];

    public int $successCount = 0;

    protected bool $overwrite;

    protected array $validDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

    /**
     * Header sebenarnya berada pada baris ke-2
     */
    public function headingRow(): int
    {
        return 2;
    }

    public function __construct(bool $overwrite = false)
    {
        $this->overwrite = $overwrite;
    }

    public function collection(Collection $rows)
    {
        // 1. Preload master kelas
        $classes = [];
        foreach (SchoolClass::all() as $cls) {
            $classes[mb_strtolower(trim($cls->name))] = $cls->id;
            $classes[mb_strtolower(str_replace(['-', ' '], '', $cls->name))] = $cls->id;
        }

        // 2. Preload guru (termasuk role Spatie dan kolom role)
        $teachers = User::whereHas('roles', function($q) {
                $q->whereIn('name', ['Guru', 'Guru Mata Pelajaran', 'Wali Kelas', 'Guru Piket', 'Kepala Sekolah']);
            })
            ->orWhere('role', 'like', '%guru%')
            ->orWhere('role', 'like', '%Guru%')
            ->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [mb_strtolower(trim($name)) => $id])
            ->all();

        // 3. Preload mata pelajaran
        $subjects = Subject::pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [mb_strtolower(trim($name)) => $id])
            ->all();

        // 4. Preload seluruh slot waktu non-istirahat
        $allTimeslots = Timeslot::where('is_break', false)->orderBy('order_sequence')->get();

        // Cache bentrok di level file
        $classSlotCache = [];
        $teacherSlotCache = [];

        foreach ($rows as $i => $row) {
            // Header di baris ke-2, baris data pertama di Excel adalah baris ke-3
            $lineNumber = $i + 3;

            $day = trim($row['hari'] ?? '');
            $slotName = $this->normalize($row['slot_waktu'] ?? '');
            $className = trim($row['kelas'] ?? '');
            $subjectName = trim($row['mata_pelajaran'] ?? '');
            $teacherName = trim($row['guru'] ?? '');

            // Baris kosong, lewati
            if (!$day && !$slotName && !$className && !$subjectName && !$teacherName) {
                continue;
            }

            // Abaikan baris contoh bawaan template
            if (str_contains($slotName, 'pilih dari dropdown') ||
                str_contains($className, 'pilih dari dropdown') ||
                str_contains($subjectName, 'pilih dari dropdown') ||
                str_contains($teacherName, 'pilih dari dropdown')) {
                continue;
            }

            if (!$day || !$slotName || !$className || !$subjectName || !$teacherName) {
                $this->errors[] = "Baris {$lineNumber}: Ada kolom yang masih kosong, baris dilewati.";
                continue;
            }

            if (!in_array($day, $this->validDays, true)) {
                $this->errors[] = "Baris {$lineNumber}: Hari '{$day}' tidak dikenali (harus Senin-Jumat).";
                continue;
            }

            // Lookup Kelas (toleran tanda strip dan spasi)
            $cleanClassKey = mb_strtolower(str_replace(['-', ' '], '', $className));
            $classId = $classes[mb_strtolower($className)] ?? $classes[$cleanClassKey] ?? null;

            // Lookup Guru
            $teacherId = $teachers[mb_strtolower($teacherName)] ?? null;
            if (!$teacherId) {
                // Fallback pencarian fleksibel jika nama memiliki gelar atau variasi spasi
                $foundUser = User::where('name', 'LIKE', '%' . $teacherName . '%')->first();
                if ($foundUser) {
                    $teacherId = $foundUser->id;
                }
            }

            // Lookup Mapel
            $subjectId = $subjects[mb_strtolower($subjectName)] ?? null;
            if (!$subjectId) {
                $foundSubj = Subject::where('name', 'LIKE', '%' . $subjectName . '%')->first();
                if ($foundSubj) {
                    $subjectId = $foundSubj->id;
                }
            }

            // Lookup Slot Waktu yang cerdas (mencocokkan nama dan hari berlakunya slot)
            $matchedSlot = $allTimeslots->first(function($slot) use ($day, $slotName) {
                if (mb_strtolower($this->normalize($slot->name)) !== mb_strtolower($slotName)) {
                    return false;
                }
                $slotDays = array_map('trim', explode(',', $slot->day_of_week));
                return in_array($day, $slotDays) 
                    || strcasecmp($slot->day_of_week, 'Semua Hari') === 0 
                    || (strcasecmp($slot->day_of_week, 'Selain Senin') === 0 && $day !== 'Senin') 
                    || (strcasecmp($slot->day_of_week, 'Selain Jumat') === 0 && $day !== 'Jumat');
            });

            // Jika tidak ditemukan slot spesifik hari itu, fallback ke pencocokan nama
            if (!$matchedSlot) {
                $matchedSlot = $allTimeslots->first(function($slot) use ($slotName) {
                    return mb_strtolower($this->normalize($slot->name)) === mb_strtolower($slotName);
                });
            }

            $timeslotId = $matchedSlot ? $matchedSlot->id : null;

            // Validasi keberadaan data master
            if (!$classId) {
                $this->errors[] = "Baris {$lineNumber}: Kelas '{$className}' tidak ditemukan di sistem.";
                continue;
            }
            if (!$teacherId) {
                $this->errors[] = "Baris {$lineNumber}: Guru '{$teacherName}' tidak ditemukan di sistem.";
                continue;
            }
            if (!$subjectId) {
                $this->errors[] = "Baris {$lineNumber}: Mata Pelajaran '{$subjectName}' tidak ditemukan di sistem.";
                continue;
            }
            if (!$timeslotId) {
                $this->errors[] = "Baris {$lineNumber}: Slot waktu '{$slotName}' tidak ditemukan (atau jam istirahat).";
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
                $this->errors[] = "Baris {$lineNumber}: Kelas '{$className}' sudah punya jadwal di {$day}, slot '{$slotName}'.";
                continue;
            }

            // Bentrok guru: guru ini sudah mengajar kelas LAIN di hari & slot yang sama
            $teacherBusyElsewhere = Timetable::where('day_of_week', $day)
                ->where('timeslot_id', $timeslotId)
                ->where('teacher_id', $teacherId)
                ->where('class_id', '!=', $classId)
                ->exists();

            if ($teacherBusyElsewhere || isset($teacherSlotCache[$teacherKey])) {
                $this->errors[] = "Baris {$lineNumber}: Guru '{$teacherName}' sudah mengajar kelas lain di {$day}, slot '{$slotName}'.";
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
     */
    protected function normalize(string $value): string
    {
        $value = trim($value);
        return str_replace(['’', '‘'], "'", $value);
    }
}