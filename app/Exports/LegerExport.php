<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LegerExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $classId;

    public function __construct($classId)
    {
        $this->classId = $classId;
    }

    public function view(): View
    {
        // Mengambil data siswa berdasarkan kelas dan seluruh mata pelajaran
        $students = Student::where('class_id', $this->classId)->orderBy('name')->get();
        $subjects = Subject::orderBy('order')->get();

        return view('grades.exports.leger', [
            'students' => $students,
            'subjects' => $subjects
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Membuat Baris 1 dan 2 (Header) menjadi Bold
            1    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]],
        ];
    }
}