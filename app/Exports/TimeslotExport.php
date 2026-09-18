<?php

namespace App\Exports;

use App\Models\Timeslot;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class TimeslotExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Timeslot::orderBy('order_sequence')->get();
    }

    public function headings(): array
    {
        return [
            'Urutan',
            'Nama Sesi',
            'Hari Berlaku',
            'Jam Mulai',
            'Jam Selesai',
            'Durasi',
            'Kategori'
        ];
    }

    /**
     * @param Timeslot $slot
     */
    public function map($slot): array
    {
        $start = Carbon::parse($slot->start_time)->format('H:i');
        $end = Carbon::parse($slot->end_time)->format('H:i');
        $startCarbon = Carbon::parse($slot->start_time);
        $endCarbon = Carbon::parse($slot->end_time);
        $duration = $endCarbon->diffInMinutes($startCarbon) . ' menit';

        return [
            $slot->order_sequence,
            $slot->name,
            $slot->day_of_week,
            $start,
            $end,
            $duration,
            $slot->is_break ? 'Istirahat' : 'Jam Pelajaran'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0EA5E9']
                ],
            ],
        ];
    }
}
