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
            if ($index < 2) {
                continue;
            }
                       
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

                // 2. Loop Mapel yang diisi di excel (Mulai Kolom E / Index 4)
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
                                'predicate' => $this->calculatePredicate($score),
                                'description' => $this->generateDescription($score, $subject->name)
                            ]
                        );
                    }
                    $colIndex++; 
                }

                // 3. SIMPAN DATA KETIDAKHADIRAN (Sakit, Izin, Alpa)               
                $sakit = $row[$colIndex + 2] ?? 0;
                $izin  = $row[$colIndex + 3] ?? 0;
                $alpa  = $row[$colIndex + 4] ?? 0;

                // Update record Rapor dengan data absensi                
                $record->update([
                    'sick' => $sakit !== '' ? $sakit : 0,
                    'permission' => $izin !== '' ? $izin : 0,
                    'absent' => $alpa !== '' ? $alpa : 0,
                ]);
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

    private function generateDescription($score, $subjectName)
    {
        $score = (int) $score;
        
        if ($score >= 92) {
            return "Menunjukkan penguasaan yang Sangat Baik dalam memahami dan menerapkan materi {$subjectName}.";
        } elseif ($score >= 83) {
            return "Menunjukkan penguasaan yang Baik dalam memahami kompetensi dasar materi {$subjectName}.";
        } elseif ($score >= 75) {
            return "Menunjukkan penguasaan yang Cukup dalam materi {$subjectName}, namun perlu sedikit pemantapan pada beberapa konsep.";
        } else {
            return "Masih memerlukan bimbingan dan pendampingan lebih lanjut untuk menguasai kompetensi materi {$subjectName}.";
        }
    }
}