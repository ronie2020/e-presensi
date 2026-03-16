<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Subject;
use App\Models\GradeRecord;
use App\Models\GradeItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class LegerImport implements ToCollection
{
    protected $academicYear;
    protected $semester;

    public function __construct($academicYear, $semester)
    {
        $this->academicYear = $academicYear;
        $this->semester = $semester;
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        $subjects = Subject::orderBy('order')->get();

        foreach ($rows as $index => $row) {
            // SKIP BARIS 0 dan 1 KARENA ITU HEADER EXCEL (Judul Kolom)
            if ($index < 2) {
                continue;
            }

            // Jika baris kosong (kolom NISN kosong), abaikan
            if (empty($row[2])) {
                continue;
            }

            // Indeks Excel Array: 0=NO, 1=NAMA, 2=NISN, 3=NIS
            $nisn = $row[2]; 
            $student = Student::where('student_id', $nisn)->first();

            if ($student) {
                // 1. Buat atau ambil GradeRecord utama untuk siswa ini
                $record = GradeRecord::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'academic_year' => $this->academicYear,
                        'semester' => $this->semester,
                    ],
                    [
                        'class_name' => $student->schoolClass->name ?? '-',
                        'report_date' => now(),
                    ]
                );

                // 2. Loop Mapel yang diisi di excel. 
                // Kolom nilai mapel dimulai dari Index ke-4 (Kolom E)
                $colIndex = 4;
                
                foreach ($subjects as $subject) {
                    $score = $row[$colIndex] ?? null;

                    // Hanya simpan jika sel nilainya diisi
                    if ($score !== null && $score !== '') {
                        GradeItem::updateOrCreate(
                            [
                                'grade_record_id' => $record->id,
                                'subject_id' => $subject->id,
                            ],
                            [
                                'score' => $score,
                                'predicate' => $this->calculatePredicate($score)
                            ]
                        );
                    }
                    $colIndex++; // Geser ke kolom mapel berikutnya (F, G, H, dst)
                }
            }
        }
    }

    private function calculatePredicate($score)
    {
        $score = (int) $score;
        if ($score >= 92) return 'A';
        if ($score >= 83) return 'B';
        if ($score >= 75) return 'C';
        return 'D';
    }
}