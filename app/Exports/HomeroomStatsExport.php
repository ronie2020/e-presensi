<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class HomeroomStatsExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $className;
    protected $periodName;

    public function __construct(array $data, $className, $periodName)
    {
        $this->data = $data;
        $this->className = $className;
        $this->periodName = $periodName;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        // Baris pertama untuk Judul Besar, baris kedua untuk Header Kolom
        return [
            ['REKAPITULASI KEDISIPLINAN DAN TUGAS SISWA'],
            ['KELAS: ' . strtoupper($this->className)],
            ['PERIODE: ' . strtoupper($this->periodName)],
            [''], // Baris kosong sebagai pemisah
            [
                'No',
                'NISN / NIS',
                'Nama Lengkap',
                'L/P',
                'Total Alpa (Hari)',
                'Terlambat (Kali)',
                'Poin Pelanggaran',
                'Poin Prestasi (Bintang)',
                'Jurnal Literasi (Buku)',
                'Jurnal Pembiasaan (Hari)'
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->data) + 5; // +5 karena ada 4 baris header tambahan

        // Styling untuk Judul Utama
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->mergeCells('A3:J3');
        
        $sheet->getStyle('A1:A3')->getFont()->setBold(true)->setSize(12);
        
        // Styling untuk Header Tabel (Baris ke-5)
        $sheet->getStyle('A5:J5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FF0D52A1'], // Warna biru gelap seperti tema web
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Memberikan border untuk semua isi data
        $sheet->getStyle('A6:J' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        return [];
    }
}