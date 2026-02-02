<?php

namespace App\Imports;

use App\Models\PpdbRegistrant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Log;

class PpdbRegistrantImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * Mapping data dari Excel ke Database
    */
    public function model(array $row)
    {
        // 1. Generate No Reg Otomatis per Baris
        $year = date('Y');
        // Menggunakan lockForUpdate untuk mencegah nomor ganda saat import banyak
        $latest = PpdbRegistrant::where('academic_year', $year)->lockForUpdate()->latest()->first();
        
        $sequence = 1;
        if ($latest) {
            // Ambil 4 digit terakhir REG-YYYY-XXXX
            // Pastikan format di database konsisten REG-YYYY-XXXX (13 karakter)
            // Jika format berbeda, sesuaikan substr
            $lastNumber = intval(substr($latest->registration_number, -4));
            $sequence = $lastNumber + 1;
        }
        
        $regNumber = 'REG-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        // 2. Format Tanggal Lahir (Excel serial date -> Y-m-d)
        $birthDate = null;
        if (isset($row['tanggal_lahir'])) {
            try {
                if (is_numeric($row['tanggal_lahir'])) {
                    $birthDate = Date::excelToDateTimeObject($row['tanggal_lahir']);
                } else {
                    // Coba parsing string biasa
                    $birthDate = date('Y-m-d', strtotime($row['tanggal_lahir']));
                }
            } catch (\Exception $e) {
                $birthDate = date('Y-m-d'); // Fallback jika format error
            }
        }

        // 3. Return Model Baru
        return new PpdbRegistrant([
            'registration_number' => $regNumber,
            'academic_year'      => $year,
            'status'             => 'pending', 
            'track'              => 'kolektif',

            // --- MAPPING KOLOM ---
            'nisn'               => $row['nisn'],
            'full_name'          => $row['nama_lengkap'],
            'gender'             => strtoupper($row['jk']), // Pastikan L/P uppercase
            'school_origin'      => $row['asal_sekolah'],
            'birth_place'        => $row['tempat_lahir'],
            'birth_date'         => $birthDate,
            'address'            => $row['alamat'],
            
            // [BARU] Input Nilai Rapor
            'average_grade'      => $row['rata_rata_nilai'] ?? 0, 
            
            'father_name'        => $row['nama_ayah'],
            'mother_name'        => $row['nama_ibu'],
            'parent_phone'       => $row['no_hp_ortu'],
        ]);
    }

    /**
     * Validasi Data Excel agar tidak error saat insert DB
     */
    public function rules(): array
    {
        return [
            'nisn' => 'required|unique:ppdb_registrants,nisn',
            'nama_lengkap' => 'required',
            'jk' => 'required|in:L,P,l,p',
            'rata_rata_nilai' => 'nullable|numeric', // Validasi angka
        ];
    }
    
    public function customValidationAttributes()
    {
        return [
            'nisn' => 'NISN',
            'nama_lengkap' => 'Nama Lengkap',
            'jk' => 'Jenis Kelamin',
            'rata_rata_nilai' => 'Nilai Rata-rata',
        ];
    }
}