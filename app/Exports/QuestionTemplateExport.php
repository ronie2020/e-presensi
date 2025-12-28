<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuestionTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function headings(): array
    {
        // HEADER WAJIB (Sesuai QuestionsImport.php)
        return [
            'soal',
            'opsi_a',
            'opsi_b',
            'opsi_c',
            'opsi_d',
            'opsi_e',
            'jawaban',
            'bobot',
        ];
    }

    public function array(): array
    {
        // CONTOH DATA (Agar guru paham cara isinya)
        return [
            [
                'Siapakah Presiden pertama Indonesia?', // soal
                'Soeharto', // opsi_a
                'B.J. Habibie', // opsi_b
                'Ir. Soekarno', // opsi_c
                'Megawati', // opsi_d
                'Jokowi', // opsi_e
                'C', // jawaban (Harus Huruf Kunci)
                '2' // bobot
            ],
            [
                'Hasil dari 5 x 5 adalah...', 
                '10', 
                '20', 
                '25', 
                '30', 
                '50', 
                'C', 
                '2' 
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bikin Header jadi Bold biar cantik
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}