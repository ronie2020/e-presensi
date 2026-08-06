<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Sheet ke-1 dari template import: tempat user mengisi jadwal.
 * Kolom A-E dipasangi dropdown yang sumbernya dari sheet "Daftar Referensi",
 * supaya user tinggal pilih (tidak ketik manual & rawan typo).
 */
class TimetableTemplateDataSheet implements FromArray, WithTitle, WithColumnWidths, WithStyles, WithEvents
{
    public function title(): string
    {
        return 'Isi Jadwal';
    }

    public function columnWidths(): array
    {
        return ['A' => 12, 'B' => 20, 'C' => 10, 'D' => 24, 'E' => 24];
    }

    public function array(): array
    {
        return [
            ['Baris ke-3 (contoh, huruf miring) boleh dihapus/ditimpa. Isi baris berikutnya dengan klik cell lalu pilih dari dropdown yang muncul.'],
            ['Hari', 'Slot Waktu', 'Kelas', 'Mata Pelajaran', 'Guru'],
            ['Senin', '(pilih dari dropdown)', '(pilih dari dropdown)', '(pilih dari dropdown)', '(pilih dari dropdown)'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => 'EF4444']]],
            2 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0EA5E9']]],
            3 => ['font' => ['italic' => true, 'color' => ['rgb' => '6B7280']]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->mergeCells('A1:E1');
                $sheet->freezePane('A3');

                // Kolom Isi Jadwal -> nama Named Range yang dibuat di sheet Daftar Referensi
                // (bukan referensi sheet langsung, supaya panah dropdown pasti muncul)
               $ranges = [
                    'A' => '=HariValid',
                    'B' => '=SlotValid',
                    'C' => '=KelasValid',
                    'D' => '=MapelValid',
                    'E' => '=GuruValid',
                ];

                foreach ($ranges as $col => $formula) {
                    $validation = new DataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(true);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(false); // false = tampilkan panah dropdown (perilaku PhpSpreadsheet terbalik)
                    $validation->setErrorTitle('Nilai tidak valid');
                    $validation->setError('Silakan pilih dari daftar dropdown yang tersedia.');
                    $validation->setFormula1($formula);

                    for ($row = 3; $row <= 500; $row++) {
                        $sheet->getCell("{$col}{$row}")->setDataValidation(clone $validation);
                    }
                }
            },
        ];
    }
}