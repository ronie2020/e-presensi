<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CbtScoreExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $examId;
    protected $passingGrade;

    public function __construct($examId, $passingGrade)
    {
        $this->examId = $examId;
        $this->passingGrade = $passingGrade;
    }

    /**
     * Ambil data dari database
     * FIX: Menggunakan tabel 'classes' dan kolom 'class_id'
     */
    public function collection()
    {
        return DB::table('cbt_student_exams')
            ->join('students', 'cbt_student_exams.student_id', '=', 'students.id')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id') // <-- FIX: class_id
            ->where('cbt_student_exams.cbt_exam_id', $this->examId)
            ->where('cbt_student_exams.status', 'finished')
            ->select(
                'students.name',
                'students.student_id as nisn', 
                'classes.name as class_name',
                'cbt_student_exams.correct_answers',
                'cbt_student_exams.wrong_answers',
                'cbt_student_exams.total_score'
            )
            ->orderBy('cbt_student_exams.total_score', 'desc')
            ->get();
    }

    /**
     * Judul Kolom di Excel
     */
    public function headings(): array
    {
        return [
            'Nama Siswa',
            'NISN / ID',
            'Kelas',
            'Jumlah Benar',
            'Jumlah Salah',
            'Nilai Akhir',
            'Status Kelulusan',
        ];
    }

    /**
     * Mapping data per baris
     */
    public function map($row): array
    {
        $status = $row->total_score >= $this->passingGrade ? 'LULUS' : 'REMEDIAL';

        return [
            $row->name,
            $row->nisn,
            $row->class_name ?? '-',
            $row->correct_answers ?? 0,
            $row->wrong_answers ?? 0,
            $row->total_score,
            $status,
        ];
    }

    /**
     * Styling sederhana (Bold Header)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}