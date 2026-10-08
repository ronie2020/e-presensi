<?php

namespace App\Exports;

use App\Models\LetterOutgoing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LetterOutgoingExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $year;
    protected $month;
    protected $sifat;
    protected $search;

    public function __construct($year = null, $month = null, $sifat = null, $search = null)
    {
        $this->year = $year;
        $this->month = $month;
        $this->sifat = $sifat;
        $this->search = $search;
    }

    public function collection()
    {
        $query = LetterOutgoing::query()->with('spt');

        if ($this->year) {
            $query->whereYear('tgl_surat', $this->year);
        }

        if ($this->month) {
            $query->whereMonth('tgl_surat', $this->month);
        }

        if ($this->sifat) {
            $query->where('sifat_surat', $this->sifat);
        }

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('tujuan_surat', 'like', "%{$search}%")
                  ->orWhere('nomor_agenda', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('nomor_agenda', 'asc')->get();
    }

    public function map($letter): array
    {
        return [
            $letter->nomor_agenda,
            $letter->nomor_surat,
            Carbon::parse($letter->tgl_surat)->translatedFormat('d F Y'),
            $letter->sifat_surat,
            $letter->tujuan_surat,
            $letter->perihal,
            $letter->spt ? 'Ya (No. SPT: ' . $letter->spt->nomor_spt . ')' : 'Tidak',
            $letter->file_path ? 'Ada' : 'Tidak Ada',
        ];
    }

    public function headings(): array
    {
        return [
            'No. Agenda',
            'Nomor Surat',
            'Tanggal Surat',
            'Sifat',
            'Tujuan / Penerima',
            'Perihal / Isi Ringkas',
            'Integrasi SPT',
            'Lampiran File',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF031D3D'],
                ],
            ],
        ];
    }
}
