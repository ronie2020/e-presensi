<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Mengambil semua user kecuali user yang sedang login (opsional) atau ambil semua
        return User::all();
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->role,
            $user->position ?? '-',
            $user->nip ?? '-',
            $user->phone ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'Email',
            'Role',
            'Jabatan',
            'NIP',
            'No HP',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style row 1 (Header) jadi bold
            1 => ['font' => ['bold' => true]],
        ];
    }
}