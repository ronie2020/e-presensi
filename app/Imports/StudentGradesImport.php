<?php

namespace App\Imports;

use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\GradeRecord;
use App\Models\GradeItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentGradesImport implements ToCollection, WithStartRow
{
    protected $class_id;
    protected $student_id;
    protected $academic_year;
    protected $semester;
    protected $className;

    public function __construct($class_id, $student_id, $academic_year, $semester)
    {
        $this->class_id = $class_id;
        $this->student_id = $student_id;
        $this->academic_year = $academic_year;
        $this->semester = $semester;

        $this->className = SchoolClass::find($class_id)?->name ?? 'Unknown Class';
    }

    public function startRow(): int
    {
        return 2;
    }

    /**
     * Asumsi Template Excel Per Siswa:
     * Kolom A (0) : No
     * Kolom B (1) : Nama Mata Pelajaran (KUNCI PENCARIAN)
     * Kolom C (2) : Nilai
     * Kolom D (3) : Deskripsi
     */
    public function collection(Collection $rows)
    {
        // 1. Buat Record Rapor Siswa Sekali Saja
        $record = GradeRecord::firstOrCreate(
            [
                'student_id' => $this->student_id,
                'academic_year' => $this->academic_year,
                'semester' => $this->semester,
            ],
            [
                'class_name' => $this->className,
                'report_date' => now(),
            ]
        );

        foreach ($rows as $row) {
            $subjectName = $row[1];
            $score = $row[2];
            $description = $row[3] ?? null;

            if (empty($subjectName) || $score === null) continue;

            // 2. Cari Subject ID berdasarkan Nama Mapel
            // Gunakan 'like' agar tidak sensitif huruf besar/kecil
            $subject = Subject::where('name', 'like', trim($subjectName))->first();

            // Jika mapel tidak ditemukan di database, lewati
            if (!$subject) continue;

            // 3. Simpan Nilai
            GradeItem::updateOrCreate(
                [
                    'grade_record_id' => $record->id,
                    'subject_id' => $subject->id,
                ],
                [
                    'score' => $score,
                    'description' => $description,
                    'predicate' => $this->calculatePredicate($score),
                ]
            );
        }
    }

    private function calculatePredicate($score)
    {
        if ($score >= 92) return 'A';
        if ($score >= 83) return 'B';
        if ($score >= 75) return 'C';
        return 'D';
    }
}