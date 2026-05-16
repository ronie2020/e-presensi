<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentValidationController extends Controller
{
    /**
     * Menampilkan halaman verifikasi mandiri
     */
    public function index()
    {
        // Sesuaikan relasi dengan cara aplikasi Anda mengambil data siswa yang login.
        // Asumsi: User memiliki relasi hasOne ke Student, atau auth()->user() adalah model Student itu sendiri.
        // Jika login langsung menggunakan model Student:
        $student = auth()->user(); 
        
        // Jika model User terpisah dari Student, gunakan: $student = auth()->user()->student;

        return view('students.verify', compact('student'));
    }

    /**
     * Memproses penyimpanan NIK & NISN (Mengunci Data)
     */
    public function process(Request $request)
    {
        $student = auth()->user(); // Sesuaikan jika menggunakan relasi: auth()->user()->student;

        $request->validate([
            'nisn' => [
                'required', 
                'numeric', 
                'digits:10',
                // Pastikan NISN unik, tapi abaikan jika itu miliknya sendiri
                'unique:students,nisn,' . $student->id,
                'unique:students,student_id,' . $student->id 
            ],
            'nik' => [
                'required', 
                'numeric', 
                'digits:16',
                // Pastikan NIK unik
                'unique:students,nik,' . $student->id
            ],
        ], [
            'nisn.digits' => 'NISN harus berisi tepat 10 digit angka.',
            'nik.digits' => 'NIK harus berisi tepat 16 digit angka.',
            'nisn.unique' => 'NISN ini sudah terdaftar pada akun siswa lain.',
            'nik.unique' => 'NIK ini sudah terdaftar pada akun siswa lain.',
        ]);

        // =================================================================
        // VALIDASI CERDAS NIK (ALGORITMA DUKCAPIL)
        // Mengecek apakah NIK milik orang lain berdasarkan Tanggal Lahir & Gender
        // =================================================================
        if ($student->dob && $student->gender) {
            $dob = \Carbon\Carbon::parse($student->dob);
            $day = $dob->format('d');
            $month = $dob->format('m');
            $year = $dob->format('y'); // Mengambil 2 digit tahun terakhir

            // Aturan NIK Dukcapil: Jika perempuan, tanggal lahir ditambah 40
            if (strtoupper($student->gender) === 'P') {
                $day = (int)$day + 40;
            }

            // Format NIK yang diharapkan (DDMMYY)
            $expectedDobInNik = sprintf('%02d%02d%02d', $day, $month, $year);
            
            // Ambil digit ke-7 sampai 12 dari inputan NIK siswa
            $actualDobInNik = substr($request->nik, 6, 6);

            // Jika tidak cocok, berarti dia memasukkan NIK orang lain / asal-asalan
            if ($expectedDobInNik !== $actualDobInNik) {
                return redirect()->back()->withErrors([
                    'nik' => 'NIK Ditolak! NIK tidak cocok dengan Tanggal Lahir ('.$dob->format('d-m-Y').') dan Jenis Kelamin ('.$student->gender.') Anda di sistem. Pastikan Anda memasukkan NIK milik sendiri, bukan milik orang tua.'
                ])->withInput();
            }
        }
        // =================================================================

        // Simpan data dan set flag is_validated menjadi true
        $student->update([
            'nisn' => $request->nisn,
            'student_id' => $request->nisn, // Di-sync karena dipakai untuk Auth Password
            'nik' => $request->nik,
            'is_validated' => true
        ]);

        return redirect()->back()->with('success', 'Data NIK dan NISN Anda berhasil diverifikasi dan dikunci dalam sistem.');
    }
}