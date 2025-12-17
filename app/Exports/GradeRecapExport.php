<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class GradeRecapExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('lms.grades.export_excel', $this->data);
    }

    /**
     * Manipulasi Tampilan Excel setelah data dimuat
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // 1. SETUP HALAMAN UNTUK PRINT
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToWidth(1); // Paksa muat 1 halaman lebar
                $sheet->getPageSetup()->setFitToHeight(0); // Tinggi otomatis

                // 2. DETEKSI BARIS TERAKHIR
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();
                
                // Asumsi Header dimulai di baris 5 (sesuai Blade)
                $headerRow = 5;
                
                // 3. STYLE JUDUL UTAMA (Baris 1-3)
                $sheet->getStyle('A1:A3')->getFont()->setBold(true);
                $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // 4. STYLE HEADER TABEL (Baris 5)
                $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4472C4'], // Warna Biru Header
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                // 5. BERI GARIS (BORDER) KE SELURUH DATA TABEL
                $styleBorder = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];
                // Terapkan border dari Header sampai baris terakhir data siswa
                // Kita kurangi baris tanda tangan (sekitar 6 baris dari bawah) agar tidak kena border kotak
                // Namun untuk aman, kita hitung baris data siswa saja
                $totalStudents = count($this->data['students']);
                $endDataRow = $headerRow + $totalStudents;
                
                $sheet->getStyle("A{$headerRow}:{$lastColumn}{$endDataRow}")->applyFromArray($styleBorder);

                // 6. RAPIKAN ALIGNMENT DATA
                // Kolom A (No) -> Center
                $sheet->getStyle("A6:A{$endDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                // Kolom B (Nama) -> Left
                $sheet->getStyle("B6:B{$endDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                // Sisanya (Nilai) -> Center
                $sheet->getStyle("C6:{$lastColumn}{$endDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}