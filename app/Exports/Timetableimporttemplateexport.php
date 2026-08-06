<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Template Import Jadwal — 2 sheet:
 * 1. "Isi Jadwal"        -> tempat user mengisi data, sudah dilengkapi dropdown
 * 2. "Daftar Referensi"  -> sumber daftar dropdown & bantuan visual untuk user
 */
class TimetableImportTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Isi Jadwal' => new TimetableTemplateDataSheet(),
            'Daftar Referensi' => new TimetableTemplateReferenceSheet(),
        ];
    }
}