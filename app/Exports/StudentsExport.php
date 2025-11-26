<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
    * Mengambil data siswa dari database.
    */
    public function collection()
    {
        return Student::with('schoolClass')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->select('students.*')
            ->orderBy('classes.name', 'asc')
            ->orderBy('students.name', 'asc')
            ->get();
    }

    /**
    * Menentukan Judul Kolom (Header) di Excel.
    */
    public function headings(): array
    {
        return [
            'NIS / NISN',
            'Nama Lengkap',
            'Kelas',
            'L/P',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Alamat',
            'Nama Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'Pekerjaan Ibu',
            'No. WA Ortu',
            'RFID ID',
            'Status', // Aktif/Tidak (jika ada)
        ];
    }

    /**
    * Memetakan data per baris.
    */
    public function map($student): array
    {
        return [
            $student->student_id,
            $student->name,
            $student->schoolClass ? $student->schoolClass->name : '-',
            $student->gender,
            $student->pob,
            $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d-m-Y') : '-',
            $student->religion,
            $student->address,
            $student->father_name,
            $student->father_job,
            $student->mother_name,
            $student->mother_job,
            $student->parent_wa_number,
            $student->rfid_id,
            'Aktif', 
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}