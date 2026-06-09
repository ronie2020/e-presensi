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
    private $currentSequence = 0;
    private $year;

    /**
     * Pindahkan query database ke Constructor agar hanya dieksekusi SEKALI.
     * Ini mencegah masalah nomor pendaftaran ganda saat import massal.
     */
    public function __construct()
    {
        $this->year = date('Y');
        
        // Ambil data terakhir di tahun ini
        $latest = PpdbRegistrant::where('academic_year', $this->year)
                    ->orderBy('id', 'desc')
                    ->first();
        
        if ($latest) {
            $this->currentSequence = intval(substr($latest->registration_number, -4));
        }
    }

    /**
    * Mapping data dari Excel ke Database
    */
    public function model(array $row)
    {
        // 1. Generate No Reg Otomatis (Increment di memory, lebih cepat & aman)
        $this->currentSequence++;
        $regNumber = 'REG-' . $this->year . '-' . str_pad($this->currentSequence, 4, '0', STR_PAD_LEFT);

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
            'academic_year'      => $this->year,
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
            
            // Input Nilai Rapor
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
            'nama_lengkap' => 'required|string|max:255',
            'jk' => 'required|in:L,P,l,p',
            'rata_rata_nilai' => 'nullable|numeric', 
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            
            // [PERBAIKAN] Tambahkan required agar dicegat sebelum masuk DB
            'no_hp_ortu' => 'required', 
        ];
    }
    
    public function customValidationAttributes()
    {
        return [
            'nisn' => 'NISN',
            'nama_lengkap' => 'Nama Lengkap',
            'jk' => 'Jenis Kelamin',
            'rata_rata_nilai' => 'Nilai Rata-rata',
            'nama_ayah' => 'Nama Ayah',
            'nama_ibu' => 'Nama Ibu',
            'no_hp_ortu' => 'Nomor HP Orang Tua',
        ];
    }

    /**
     * Kustomisasi pesan error agar lebih ramah dibaca pengguna
     */
    public function customValidationMessages()
    {
        return [
            'no_hp_ortu.required' => 'Nomor HP Orang Tua pada baris data Excel tidak boleh kosong.',
            'nisn.unique' => 'NISN :input sudah terdaftar di sistem.',
        ];
    }
}