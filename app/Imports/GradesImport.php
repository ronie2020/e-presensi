<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\GradeRecord;
use App\Models\GradeItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GradesImport implements ToCollection, WithStartRow
{
    protected $class_id;
    protected $subject_id;
    protected $academic_year;
    protected $semester;
    protected $className;

    public function __construct($class_id, $subject_id, $academic_year, $semester)
    {
        $this->class_id = $class_id;
        $this->subject_id = $subject_id;
        $this->academic_year = $academic_year;
        $this->semester = $semester;
        
        // Simpan nama kelas untuk efisiensi
        $this->className = SchoolClass::find($class_id)?->name ?? 'Unknown Class';
    }

    /**
     * Mulai membaca dari baris ke-2 (Baris 1 biasanya Header)
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Logic memproses setiap baris Excel
     * Asumsi Template:
     * Kolom A (0) : No
     * Kolom B (1) : Nama Siswa
     * Kolom C (2) : NISN / Student ID (KUNCI PENCARIAN)
     * Kolom D (3) : Nilai (0-100)
     * Kolom E (4) : Deskripsi (Opsional)
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Ambil data dari kolom
            $nisn = $row[2]; // NISN
            $score = $row[3]; // Nilai
            $description = $row[4] ?? null; // Deskripsi

            // Validasi data minimal
            if (empty($nisn) || $score === null) continue;

            // 1. Cari Siswa berdasarkan NISN dan Kelas
            $student = Student::where('student_id', $nisn)
                              ->where('class_id', $this->class_id)
                              ->first();

            // Jika siswa tidak ditemukan, lewati
            if (!$student) continue;

            // 2. Buat/Ambil Record Rapor Utama
            $record = GradeRecord::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'academic_year' => $this->academic_year,
                    'semester' => $this->semester,
                ],
                [
                    'class_name' => $this->className,
                    'report_date' => now(),
                ]
            );

            // 3. Simpan Item Nilai
            GradeItem::updateOrCreate(
                [
                    'grade_record_id' => $record->id,
                    'subject_id' => $this->subject_id,
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