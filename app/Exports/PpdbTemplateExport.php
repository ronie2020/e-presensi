<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PpdbTemplateExport implements WithHeadings, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Judul Header Kolom (Wajib sama dengan Import)
     */
    public function headings(): array
    {
        return [
            'nisn',           // Wajib, Unik
            'nama_lengkap',   // Wajib
            'jk',             // L/P
            'tempat_lahir',
            'tanggal_lahir',  // Format: YYYY-MM-DD
            'alamat',
            'asal_sekolah',
            'rata_rata_nilai', // <--- [BARU] Kolom Nilai Rapor
            'nama_ayah',
            'nama_ibu',
            'no_hp_ortu',
        ];
    }

    /**
     * Styling Header (Bold)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function title(): string
    {
        return 'Format Pendaftaran';
    }
}