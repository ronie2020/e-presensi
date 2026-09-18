<?php

namespace App\Exports;

use App\Models\TeachingLoad;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TeachingLoadExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected int $rowNumber = 0;

    public function collection()
    {
        return TeachingLoad::with(['teacher', 'subject', 'studentClass'])
            ->join('classes', 'teaching_loads.class_id', '=', 'classes.id')
            ->orderBy('classes.name')
            ->select('teaching_loads.*')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Guru',
            'NIP',
            'Mata Pelajaran',
            'Kelas',
            'Alokasi JP / Minggu'
        ];
    }

    /**
     * @param TeachingLoad $load
     */
    public function map($load): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $load->teacher?->name ?? '-',
            $load->teacher?->nip ?? '-',
            $load->subject?->name ?? '-',
            $load->studentClass?->name ?? '-',
            $load->hours_per_week . ' JP'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669'] // Emerald-600
                ],
            ],
        ];
    }
}
