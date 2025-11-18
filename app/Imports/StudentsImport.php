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
        // Kita asumsikan header di CSV adalah 'kelas' (contoh: "7A")
        $classId = $this->classes->get($row['kelas']);

        // 2. Jika nama kelas di CSV tidak ditemukan di database, lewati baris ini.
        if (!$classId) {
            return null;
        }

        // 3. Buat model Student baru
        return new Student([
            'student_id'        => $row['studentid'],
            'name'              => $row['nama'],
            'class_id'          => $classId,
            'parent_wa_number'  => $row['nomorwa'],
            'rfid_id'           => $row['rfidid'],
        ]);
    }

    /**
     * Aturan validasi untuk setiap baris di CSV.
     */
    public function rules(): array
    {
        return [
            'studentid' => 'required|unique:students,student_id',
            'nama' => 'required|string',
            'kelas' => 'required|string|exists:classes,name', // Pastikan nama kelas ada di tabel 'classes'
            'nomorwa' => 'nullable|string',
            'rfidid' => 'nullable|string|unique:students,rfid_id,NULL,id,rfid_id,NULL', // Unik jika tidak null
        ];
    }

    /**
     * Pesan kustom untuk validasi.
     */
    public function customValidationMessages()
    {
        return [
            'studentid.required' => 'Kolom studentid wajib diisi.',
            'studentid.unique' => 'studentid sudah terdaftar.',
            'nama.required' => 'Kolom nama wajib diisi.',
            'kelas.required' => 'Kolom kelas wajib diisi.',
            'kelas.exists' => 'Nama kelas tidak ditemukan di database.',
            'rfidid.unique' => 'rfidid sudah terdaftar.',
        ];
    }
}