<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateStudentExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return [
            'No',
            'Nama Mata Pelajaran (WAJIB SESUAI APLIKASI)',
            'Nilai (0-100)',
            'Deskripsi (Opsional)'
        ];
    }

    public function array(): array
    {
        return [
            ['1', 'Pendidikan Agama dan Budi Pekerti', '85', 'Tuntas'],
            ['2', 'Pendidikan Pancasila', '90', 'Sangat Baik'],
            ['3', 'Bahasa Indonesia', '88', ''],
            ['4', 'Matematika', '75', 'Perlu Remedial'],
            ['5', 'Ilmu Pengetahuan Alam (IPA)', '80', 'Baik'],
            ['6', 'Ilmu Pengetahuan Sosial (IPS)', '92', 'Sangat Baik'],
            ['7', 'Bahasa Inggris', '85', 'Tuntas'],
            ['8', 'PJOK', '78', 'Cukup'],
            ['9', 'Informatika', '95', 'Sangat Baik'],
            ['10', 'Seni dan Prakarya', '89', 'Baik'],
            ['11', 'Muatan Lokal (Bahasa Sunda)', '89', 'Baik'],
            ['11', 'Projek Penguatan Profil Pelajar Pancasila (P5)', '89', 'Baik'],            
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}