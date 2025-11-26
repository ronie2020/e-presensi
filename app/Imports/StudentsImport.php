<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\SchoolClass;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    private $classes;

    /**
    * Siapkan data kelas (Nama -> ID) untuk lookup yang cepat.
    */
    public function __construct()
    {
        // Ambil semua kelas, ubah menjadi format: ['7A' => 1, '7B' => 2, ...]
        $this->classes = SchoolClass::pluck('id', 'name');
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. Cari Class ID berdasarkan nama kelas di CSV
        // Pastikan penulisan nama kelas di Excel SAMA PERSIS dengan di Database (misal "7A")
        $classId = $this->classes->get($row['kelas']);

        // Jika nama kelas di Excel tidak ditemukan di database, biarkan kosong (null) atau skip
        // $classId akan null jika tidak ketemu

        // 2. Format Nomor WA (Ubah 08xx jadi 628xx)
        $formattedWa = $this->formatPhoneNumber($row['nomorwa']);

        // 3. Cek apakah siswa sudah ada (berdasarkan studentid / NISN)
        $student = Student::where('student_id', $row['studentid'])->first();

        if ($student) {
            // LOGIC UPDATE: Jika siswa sudah ada, update datanya
            $student->update([
                'name'             => $row['nama'],
                'class_id'         => $classId ?? $student->class_id, // Update kelas hanya jika ditemukan
                'parent_wa_number' => $formattedWa,
                'rfid_id'          => $row['rfidid'] ?? $student->rfid_id,
            ]);
            
            return $student;
        }

        // LOGIC CREATE: Jika siswa belum ada, buat baru
        return new Student([
            'student_id'        => $row['studentid'],
            'name'              => $row['nama'],
            'class_id'          => $classId,
            'parent_wa_number'  => $formattedWa,
            'rfid_id'           => $row['rfidid'],
        ]);
    }

    /**
     * Aturan validasi untuk setiap baris di CSV.
     */
    public function rules(): array
    {
        return [
            'studentid' => 'required', // Hapus 'unique' agar proses update bisa berjalan
            'nama'      => 'required|string',
            'kelas'     => 'required', // Hapus 'exists' agar tidak langsung error jika typo, kita handle di logic
            
            // PERBAIKAN UTAMA: Hapus '|string' agar angka dari Excel diterima
            'nomorwa'   => 'nullable', 
            
            'rfidid'    => 'nullable',
        ];
    }

    /**
     * Pesan kustom untuk validasi.
     */
    public function customValidationMessages()
    {
        return [
            'studentid.required' => 'Kolom studentid wajib diisi.',
            'nama.required'      => 'Kolom nama wajib diisi.',
            'kelas.required'     => 'Kolom kelas wajib diisi.',
        ];
    }

    /**
     * Helper: Format Nomor WA (08xx -> 628xx)
     */
    private function formatPhoneNumber($number)
    {
        if (!$number) return null;

        // Paksa ubah ke string dulu
        $number = (string) $number;
        
        // Hapus spasi atau karakter non-angka jika ada
        $number = preg_replace('/[^0-9]/', '', $number);

        // Jika diawali '0', ganti dengan '62'
        if (substr($number, 0, 1) == '0') {
            $number = '62' . substr($number, 1);
        }

        return $number;
    }
}