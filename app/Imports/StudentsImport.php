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
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. PERBAIKAN: Gunakan nama key yang KONSISTEN sesuai header Excel & Rules
        // Asumsi Header Excel: studentid, nama, kelas, nomorwa, rfidid
        if (!isset($row['nama']) || !isset($row['studentid'])) {
            return null;
        }

        // 2. PERBAIKAN: Cari Kelas dengan 'firstOrCreate' agar tidak Error jika kelas belum ada
        // Ini mencegah error "class_id cannot be null"
        $className = $row['kelas'];
        $classId = null;

        if ($className) {
            // Cari kelas berdasarkan nama, kalau tidak ada, buat baru otomatis
            $class = SchoolClass::firstOrCreate(
                ['name' => $className], // Pencarian
                ['name' => $className]  // Data baru jika tidak ketemu
            );
            $classId = $class->id;
        }

        // 3. Format Nomor WA
        $formattedWa = $this->formatPhoneNumber($row['nomorwa'] ?? null);

        // 4. Cek apakah siswa sudah ada (berdasarkan studentid / NISN)
        $student = Student::where('student_id', $row['studentid'])->first();

        if ($student) {
            // LOGIC UPDATE: Jika siswa sudah ada, update datanya
            $student->update([
                'name'             => $row['nama'],
                'class_id'         => $classId ?? $student->class_id, // Update kelas hanya jika ditemukan di excel
                'parent_wa_number' => $formattedWa,
                'rfid_id'          => $row['rfidid'] ?? $student->rfid_id,
            ]);
            
            return $student;
        }

        // LOGIC CREATE: Jika siswa belum ada, buat baru
        // Pastikan $classId ada. Jika null (excel kelas kosong), ini akan error kecuali database diizinkan null.
        // Sebaiknya pastikan excel kolom kelas terisi.
        return new Student([
            'student_id'        => $row['studentid'],
            'name'              => $row['nama'],
            'class_id'          => $classId, 
            'parent_wa_number'  => $formattedWa,
            'rfid_id'           => $row['rfidid'] ?? null,
        ]);
    }

    /**
     * Aturan validasi untuk setiap baris di CSV.
     */
    public function rules(): array
    {
        return [
            'studentid' => 'required',
            'nama'      => 'required',
            'kelas'     => 'required', // Wajib ada agar tidak error class_id null
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

        $number = (string) $number;
        $number = preg_replace('/[^0-9]/', '', $number);

        if (substr($number, 0, 1) == '0') {
            $number = '62' . substr($number, 1);
        }

        return $number;
    }
}