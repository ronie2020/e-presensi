<?php

namespace App\Imports;

use App\Models\PpdbRegistrant;
use App\Models\Student; // Pastikan model Student di-import
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Log;

class PpdbRegistrantImport implements ToModel, WithHeadingRow, WithValidation
{
    private $sequence = 1;
    private $year;
    
    // Variabel publik untuk menampung data siswa yang baru masuk
    public $importedData = []; 
    // Array untuk melacak NISN yang sudah diproses di file Excel ini
    private $importedNisn = []; 

    /**
     * Cek nomor terakhir di database SEKALI SAJA saat proses import dimulai
     */
    public function __construct()
    {
        $this->year = date('Y');
        
        // Ambil data terakhir di tahun ini
        $latest = PpdbRegistrant::where('academic_year', $this->year)
                                ->orderBy('id', 'desc')
                                ->first();
        
        if ($latest) {
            $lastNumber = intval(substr($latest->registration_number, -4));
            $this->sequence = $lastNumber + 1;
        }
    }

    /**
     * Mapping data dari Excel ke Database
     */
    public function model(array $row)
    {
        $nisn = $row['nisn'];

        // --- PENGECEKAN DUPLIKASI MANUAL (LEBIH AMAN) ---
        
        // 1. Cek apakah NISN ini kembar di dalam file Excel yang sama
        if (in_array($nisn, $this->importedNisn)) {
            Log::warning("Import Kolektif (Skip): NISN $nisn kembar di dalam file Excel.");
            return null; // SKIP baris ini
        }

        // 2. Cek apakah NISN ini sudah ada di tabel pendaftar (ppdb_registrants)
        if (PpdbRegistrant::where('nisn', $nisn)->exists()) {
            Log::warning("Import Kolektif (Skip): NISN $nisn sudah terdaftar di PPDB.");
            return null; // SKIP baris ini
        }

        // 3. Cek apakah NISN ini sudah ada di Data Induk Siswa (jika sudah di-promote sebelumnya)
        if (class_exists(Student::class) && Student::where('nisn', $nisn)->exists()) {
             Log::warning("Import Kolektif (Skip): NISN $nisn sudah berstatus Siswa Aktif.");
             return null; // SKIP baris ini
        }

        // Jika lolos semua pengecekan, masukkan ke daftar NISN yang sedang diproses
        $this->importedNisn[] = $nisn;
        
        // --- SELESAI PENGECEKAN DUPLIKASI ---

        // 1. Generate No Reg Otomatis
        $regNumber = 'REG-' . $this->year . '-' . str_pad($this->sequence, 4, '0', STR_PAD_LEFT);
        
        // Tambahkan +1 untuk siswa selanjutnya
        $this->sequence++;

        // Simpan data penting ke array untuk dilempar ke Controller & View
        $this->importedData[] = [
            'registration_number' => $regNumber,
            'full_name'          => $row['nama_lengkap'],
            'nisn'               => $nisn,
            'school_origin'      => $row['asal_sekolah']
        ];

        // 2. Format Tanggal Lahir (Excel serial date -> Y-m-d)
        $birthDate = null;
        if (isset($row['tanggal_lahir'])) {
            try {
                if (is_numeric($row['tanggal_lahir'])) {
                    $birthDate = Date::excelToDateTimeObject($row['tanggal_lahir']);
                } else {
                    $birthDate = date('Y-m-d', strtotime($row['tanggal_lahir']));
                }
            } catch (\Exception $e) {
                $birthDate = date('Y-m-d'); // Fallback
            }
        }

        // 3. Return Model Baru
        return new PpdbRegistrant([
            'registration_number' => $regNumber,
            'academic_year'      => $this->year,
            'status'             => 'pending', 
            'track'              => 'kolektif',

            // --- MAPPING KOLOM ---
            'nisn'               => $nisn,
            'full_name'          => $row['nama_lengkap'],
            'gender'             => strtoupper($row['jk']),
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
     * Validasi Format Data Excel
     */
    public function rules(): array
    {
        return [
            'nisn' => 'required|numeric|digits:10',
            'nama_lengkap' => 'required|string|max:255',
            'jk' => 'required|in:L,P,l,p',
            'rata_rata_nilai' => 'nullable|numeric', 
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
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
            'nisn.digits' => 'Baris :attribute harus berjumlah tepat 10 digit angka.',
            'nisn.numeric' => 'Baris :attribute hanya boleh berisi angka.',
            'nisn.required' => 'Baris :attribute wajib diisi.',
            'nisn.unique' => 'NISN :input sudah terdaftar di sistem.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'jk.in' => 'Jenis Kelamin harus diisi L atau P.',
            'no_hp_ortu.required' => 'Nomor HP Orang Tua pada baris data Excel tidak boleh kosong.',
        ];
    }
}