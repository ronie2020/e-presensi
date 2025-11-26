<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\LibraryVisit;
use Carbon\Carbon;

class LibraryKioskController extends Controller
{
    public function index()
    {
        // Tampilkan halaman Kiosk khusus Perpustakaan
        return view('library.kiosk');
    }

    public function process(Request $request)
    {
        $scanData = $request->scan_data;

        // 1. Cari Siswa (Berdasarkan RFID atau NISN/Student ID)
        $student = Student::where('rfid_id', $scanData)
                    ->orWhere('student_id', $scanData)
                    ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu/Siswa tidak dikenali.',
            ]);
        }

        $today = Carbon::today();

        // 2. Cek apakah sudah scan dalam 5 menit terakhir (Mencegah spam scan)
        $lastVisit = LibraryVisit::where('student_id', $student->id)
                        ->where('date', $today)
                        ->latest()
                        ->first();

        if ($lastVisit && Carbon::parse($lastVisit->time)->diffInMinutes(now()) < 5) {
            return response()->json([
                'success' => false,
                'student_name' => $student->name,
                'message' => 'Anda sudah mengisi buku tamu barusan.',
            ]);
        }

        // 3. Catat Kunjungan
        LibraryVisit::create([
            'student_id' => $student->id,
            'date' => $today,
            'time' => now(),
        ]);

        return response()->json([
            'success' => true,
            'student_name' => $student->name,
            'message' => 'Selamat Datang di Perpustakaan!',
        ]);
    }
}