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
        // Ambil siswa beserta data kelasnya, urutkan berdasarkan nama kelas lalu nama siswa
        return Student::with('schoolClass')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->select('students.*') // Ambil kolom students saja agar tidak bentrok
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
            'ID Siswa (NISN)',
            'Nama Lengkap',
            'Kelas',
            'RFID ID',
            'Nomor WA Orang Tua',
            'Tanggal Terdaftar'
        ];
    }

    /**
    * Memetakan data per baris agar sesuai urutan Header.
    */
    public function map($student): array
    {
        return [
            $student->student_id,
            $student->name,
            $student->schoolClass ? $student->schoolClass->name : 'Tanpa Kelas',
            $student->rfid_id,
            $student->parent_wa_number,
            $student->created_at ? $student->created_at->format('d-m-Y') : '-',
        ];
    }

    /**
    * Styling sederhana (Bold pada header).
    */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}