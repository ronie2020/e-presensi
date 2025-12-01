<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\LibraryVisit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LibraryKioskController extends Controller
{
    public function index()
    {
        // 1. Ambil data kunjungan hari ini
        $recentVisits = LibraryVisit::with('student')
                        ->whereDate('date', Carbon::today())
                        ->latest()
                        ->take(10)
                        ->get()
                        ->map(function ($visit) {
                            return [
                                'name' => $visit->student->name,
                                'status' => true,
                                'message' => 'Tercatat',
                                'time_log' => Carbon::parse($visit->time)->format('H:i') 
                            ];
                        });

        return view('library.kiosk', compact('recentVisits'));
    }

    public function process(Request $request)
    {
        $scanData = $request->scan_data;

        // 1. Cari Siswa
        $student = Student::where('rfid_id', $scanData)
                    ->orWhere('student_id', $scanData)
                    ->first();

        // [UPDATE] Penanganan jika Data Tidak Masuk (Siswa Belum Terdaftar)
        if (!$student) {
            return response()->json([
                'success' => false,
                'error_type' => 'not_found', // Flag khusus untuk frontend
                'scanned_id' => $scanData,   // Kembalikan ID yang discan agar bisa dilihat petugas
                'message' => 'Kartu belum terdaftar.',
            ]);
        }

        $today = Carbon::today();

        // 2. Cek Spam (5 Menit)
        $lastVisit = LibraryVisit::where('student_id', $student->id)
                        ->where('date', $today)
                        ->latest()
                        ->first();

        if ($lastVisit && Carbon::parse($lastVisit->time)->diffInMinutes(now()) < 5) {
            return response()->json([
                'success' => false,
                'error_type' => 'duplicate',
                'student_name' => $student->name,
                'message' => 'Anda sudah mengisi buku tamu barusan.',
            ]);
        }

        // 3. Try-Catch Database Error
        try {
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
        } catch (\Exception $e) {
            Log::error("Kiosk Save Error for {$student->name}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error_type' => 'server_error',
                'student_name' => $student->name,
                'message' => 'Gagal menyimpan data. Hubungi petugas.',
            ]);
        }
    }
}