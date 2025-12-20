<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateMapelExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'NISN (WAJIB DIISI)',
            'Nilai (0-100)',
            'Deskripsi (Opsional)'
        ];
    }

    public function array(): array
    {
        // Data Contoh (Dummy) agar user paham cara isinya
        return [
            ['1', 'Budi Santoso (Contoh)', '1234567890', '85', 'Sangat baik dalam memahami materi.'],
            ['2', 'Siti Aminah (Contoh)', '0987654321', '90', 'Perlu ditingkatkan lagi.'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style Header Bold
            1 => ['font' => ['bold' => true]],
        ];
    }
}