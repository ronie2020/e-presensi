<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CbtScoreExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $results;
    protected $passingGrade;

    /**
     * Constructor menerima data Collection dari Controller
     * agar sinkron dengan logika "Hitung Manual" di CbtController
     */
    public function __construct($results, $passingGrade)
    {
        $this->results = $results;
        $this->passingGrade = $passingGrade;
    }

    /**
     * Mengembalikan data collection yang dikirim dari Controller
     */
    public function collection()
    {
        // Pastikan ini adalah Collection, jika array ubah jadi collect()
        return collect($this->results);
    }

    /**
     * Judul Kolom di Excel
     */
    public function headings(): array
    {
        return [
            'NAMA SISWA',
            'NISN',
            'KELAS',
            'BENAR',
            'SALAH',
            'NILAI AKHIR',
            'STATUS KELULUSAN',
        ];
    }

    /**
     * Mapping data per baris
     * Menyesuaikan nama field dari query di CbtController->recap()
     */
    public function map($row): array
    {
        // Logika kelulusan
        $status = $row->total_score >= $this->passingGrade ? 'LULUS' : 'REMEDIAL';

        return [
            $row->student_name,         // Dari alias di controller
            $row->student_nisn ?? '-',  // Dari alias di controller
            $row->class_name ?? '-',    // Dari alias di controller
            $row->correct_answers ?? 0, // Hasil hitung manual controller
            $row->wrong_answers ?? 0,   // Hasil hitung manual controller
            $row->total_score ?? 0,
            $status,
        ];
    }

    /**
     * Styling Header (Bold)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}