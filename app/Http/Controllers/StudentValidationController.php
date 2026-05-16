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

        // Simpan data dan set flag is_validated menjadi true
        $student->update([
            'nisn' => $request->nisn,
            'student_id' => $request->nisn, // Di-sync karena dipakai untuk Auth Password (berdasarkan Model Anda)
            'nik' => $request->nik,
            'is_validated' => true
        ]);

        return redirect()->back()->with('success', 'Data NIK dan NISN Anda berhasil diverifikasi dan dikunci dalam sistem.');
    }
}