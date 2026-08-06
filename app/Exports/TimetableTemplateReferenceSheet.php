<?php

namespace App\Exports;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Timeslot;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\NamedRange;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Sheet ke-2 dari template import: berisi daftar nilai yang valid.
 * Kolom H (Slot Waktu gabungan) disembunyikan — hanya dipakai sebagai sumber dropdown.
 */
class TimetableTemplateReferenceSheet implements FromArray, WithTitle, WithColumnWidths, WithStyles, WithEvents
{
    public function title(): string
    {
        return 'Daftar Referensi';
    }

    public function columnWidths(): array
    {
        return ['A' => 14, 'B' => 14, 'C' => 24, 'D' => 24, 'E' => 20, 'F' => 26, 'G' => 20];
    }

    public function array(): array
    {
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $kelas = SchoolClass::orderBy('name')->pluck('name')->all();
        $mapel = Subject::orderBy('name')->pluck('name')->all();
        $guru = User::role(['Guru', 'Guru Mata Pelajaran', 'Wali Kelas'])->orderBy('name')->pluck('name')->all();

        // Kelompokkan slot waktu berdasarkan pola nama (Senin / Jumat / lainnya)
        // Ini hanya bantuan visual, bukan acuan pencocokan sebenarnya
        $slots = Timeslot::where('is_break', false)->orderBy('order_sequence')->pluck('name')->all();
        $slotSenin = [];
        $slotJumat = [];
        $slotLain = [];
        foreach ($slots as $s) {
            $lower = mb_strtolower($s);
            if (str_contains($lower, 'senin')) {
                $slotSenin[] = $s;
            } elseif (str_contains($lower, 'jum')) {
                $slotJumat[] = $s;
            } else {
                $slotLain[] = $s;
            }
        }

        $totalRows = max(
            count($hari), count($kelas), count($mapel), count($guru),
            count($slotSenin), count($slotLain), count($slotJumat), count($slots)
        );

        $grid = [];
        $grid[] = ['Hari yang Valid', 'Kelas yang Valid', 'Mata Pelajaran yang Valid', 'Guru yang Valid', 'Slot Waktu - Senin', 'Slot Waktu - Selasa s.d Kamis', 'Slot Waktu - Jumat', ''];

        for ($i = 0; $i < $totalRows; $i++) {
            $grid[] = [
                $hari[$i] ?? '',
                $kelas[$i] ?? '',
                $mapel[$i] ?? '',
                $guru[$i] ?? '',
                $slotSenin[$i] ?? '',
                $slotLain[$i] ?? '',
                $slotJumat[$i] ?? '',
                $slots[$i] ?? '', // kolom H (disembunyikan): gabungan semua slot, sumber dropdown "Slot Waktu"
            ];
        }

        $grid[] = [];
        $grid[] = ['Catatan: Nama Mata Pelajaran & Guru harus sama persis dengan data terdaftar di aplikasi.'];
        $grid[] = ['Pengelompokan Slot Waktu per hari di atas adalah perkiraan dari pola nama; cek ulang di menu Slot Waktu jika ragu.'];
        $grid[] = ['Kelas "admin" (kalau muncul di daftar) adalah data uji sistem, abaikan.'];

        return $grid;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0EA5E9']],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Kolom H hanya sumber data dropdown, tidak perlu dilihat user
                $sheet->getColumnDimension('H')->setVisible(false);

                // Named Range: dropdown yang sumbernya lintas-sheet HARUS lewat named range,
                // kalau langsung pakai referensi "'Daftar Referensi'!$B$2:..." panah dropdown-nya
                // sering tidak muncul di Excel/LibreOffice walau datanya sebenarnya valid.
                $spreadsheet = $sheet->getParent();
                $spreadsheet->addNamedRange(new NamedRange('HariValid', $sheet, '$A$2:$A$6'));
                $spreadsheet->addNamedRange(new NamedRange('KelasValid', $sheet, '$B$2:$B$500'));
                $spreadsheet->addNamedRange(new NamedRange('MapelValid', $sheet, '$C$2:$C$500'));
                $spreadsheet->addNamedRange(new NamedRange('GuruValid', $sheet, '$D$2:$D$500'));
                $spreadsheet->addNamedRange(new NamedRange('SlotValid', $sheet, '$H$2:$H$500'));
            },
        ];
    }
}