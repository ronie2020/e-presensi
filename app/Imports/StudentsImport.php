<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentsImport implements ToModel, WithHeadingRow
{
    // Melacak ID yang sudah diproses selama sesi import berjalan (mencegah duplikat dalam 1 file)
    protected $importedIds = [];

    /**
    * Memetakan setiap baris Excel ke dalam Model Database
    */
    public function model(array $row)
    {
        /* * FLEKSIBILITAS HEADER:
         * Laravel Excel otomatis mengubah nama header (baris 1 di Excel) menjadi huruf kecil 
         * dan spasi menjadi garis bawah (_). Misal: "Nama Lengkap" -> "nama_lengkap"
         */
        $studentId = $row['nis_nisn'] ?? $row['studentid'] ?? $row['nisn'] ?? $row['nis'] ?? null;
        $name      = $row['nama_lengkap'] ?? $row['nama_siswa'] ?? $row['nama'] ?? null;

        // 1. NORMALISASI NISN / NIS (Hapus spasi & bersihkan format .0 bawaan Excel)
        if ($studentId !== null) {
            $studentId = trim((string) $studentId);
            if (str_ends_with($studentId, '.0')) {
                $studentId = substr($studentId, 0, -2);
            }

            // PERBAIKAN CERDAS: Kembalikan angka 0 di depan NISN yang hilang akibat auto-format Excel.
            // NISN resmi di Indonesia selalu berjumlah 10 digit. 
            // Jika terbaca sebagai angka murni dan kurang dari 10 digit, kita beri padding '0' di depan.
            if (is_numeric($studentId) && strlen($studentId) < 10) {
                $studentId = str_pad($studentId, 10, '0', STR_PAD_LEFT);
            }
        }

        // Jika NISN/NIS atau Nama kosong di baris tersebut, lewati saja (hindari error baris kosong)
        if (!$studentId || !$name) {
            return null;
        }

        // 2. CARI ATAU BUAT KELAS OTOMATIS
        $className = $row['kelas'] ?? null;
        $classId = null;
        if ($className) {
            $class = SchoolClass::firstOrCreate(
                ['name' => trim($className)],
                ['name' => trim($className)]
            );
            $classId = $class->id;
        }

        // 3. NORMALISASI JENIS KELAMIN (GENDER)
        $gender = 'L'; // Default Laki-laki
        $rawGender = strtoupper(trim($row['lp'] ?? $row['l_p'] ?? $row['jenis_kelamin'] ?? ''));
        if (in_array($rawGender, ['P', 'PEREMPUAN', 'PR'])) {
            $gender = 'P';
        }

        // 4. TRANSFORMASI TANGGAL LAHIR (FIX ERROR EXCEL NUMBER)
        $dob = null;
        $dobRaw = $row['tanggal_lahir'] ?? null;
        if ($dobRaw) {
            try {
                if (is_numeric($dobRaw)) {
                    $dob = Date::excelToDateTimeObject($dobRaw)->format('Y-m-d');
                } else {
                    $dob = date('Y-m-d', strtotime($dobRaw));
                }
            } catch (\Exception $e) {
                $dob = null;
            }
        }

        // 5. FORMAT NOMOR WA ORTU
        $wa = $this->formatPhoneNumber($row['no_wa_ortu'] ?? $row['wa_ortu'] ?? $row['nomorwa'] ?? null);

        // 6. DATA YANG AKAN DISIMPAN / DI-UPDATE
        $dataToSave = [
            'name'             => trim($name),
            'gender'           => $gender,
            'pob'              => $row['tempat_lahir'] ?? null,
            'dob'              => $dob,
            'religion'         => $row['agama'] ?? null,
            'address'          => $row['alamat'] ?? $row['alamat_lengkap'] ?? null,
            'father_name'      => $row['father_name'] ?? $row['nama_ayah'] ?? null,
            'father_job'       => $row['father_job'] ?? $row['pekerjaan_ayah'] ?? null,
            'mother_name'      => $row['mother_name'] ?? $row['nama_ibu'] ?? null,
            'mother_job'       => $row['mother_job'] ?? $row['pekerjaan_ibu'] ?? null,
            'parent_wa_number' => $wa,
            'rfid_id'          => $row['rfid_id'] ?? $row['rfidid'] ?? null,
        ];

        // 7. CEK ANTRIAN INTERNAL (HINDARI DUPLIKAT DI FILE EXCEL YANG SAMA)
        if (in_array($studentId, $this->importedIds)) {
            // Gunakan pencarian super aman bypass global scopes, soft-deletes, dan spasi kosong
            $studentQuery = Student::withoutGlobalScopes();
            if (method_exists(Student::class, 'withTrashed') || in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive(Student::class))) {
                $studentQuery = $studentQuery->withTrashed();
            }
            $student = $studentQuery->whereRaw('TRIM(student_id) = ?', [trim($studentId)])->first();
            
            if ($student) {
                if (method_exists($student, 'restore')) {
                    $student->restore();
                }
                if ($classId) {
                    $dataToSave['class_id'] = $classId;
                }
                $student->update($dataToSave);
            }
            return null; // Langsung abaikan insert dari library excel agar tidak terjadi crash SQL
        }

        // 8. PROSES UPDATE (JIKA SISWA SUDAH ADA DI DATABASE SEBELUMNYA)
        $studentQuery = Student::withoutGlobalScopes();
        if (method_exists(Student::class, 'withTrashed') || in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive(Student::class))) {
            $studentQuery = $studentQuery->withTrashed();
        }
        $student = $studentQuery->whereRaw('TRIM(student_id) = ?', [trim($studentId)])->first();

        if ($student) {
            // Restore jika data ditemukan terhapus sementara (soft-deleted)
            if (method_exists($student, 'restore')) {
                $student->restore();
            }
            
            // Update kelas hanya jika di excel kolom kelas ada isinya
            if ($classId) {
                $dataToSave['class_id'] = $classId;
            }
            $student->update($dataToSave);
            
            // Catat ID ke tracker
            $this->importedIds[] = $studentId;

            return null; // Return null agar library Excel tidak memicu query INSERT baru
        }

        // 9. PROSES CREATE (SISWA BARU)
        $dataToSave['student_id'] = $studentId;
        $dataToSave['class_id']   = $classId;
        $dataToSave['password']   = Hash::make($studentId); // Default password pakai NISN/NIS

        // Catat ID ke tracker
        $this->importedIds[] = $studentId;

        return new Student($dataToSave);
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