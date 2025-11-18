<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Mendefinisikan judul kolom (header) untuk file Excel/CSV.
    */
    public function headings(): array
    {
        return [
            'studentid',
            'nama',
            'kelas',
            'nomorwa',
            'rfidid',
        ];
    }

    /**
    * Mengambil data yang akan diekspor dari database.
    * Kita ambil semua siswa beserta relasi kelasnya (eager loading).
    */
    public function collection()
    {
        return Student::with('schoolClass')->get();
    }

    /**
    * Memetakan data dari setiap $student ke baris di Excel.
    *
    * @param mixed $student
    * @return array
    */
    public function map($student): array
    {
        return [
            $student->student_id,
            $student->name,
            $student->schoolClass->name ?? 'N/A', // Ambil nama kelas dari relasi
            $student->parent_wa_number,
            $student->rfid_id,
        ];
    }
}

