<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentPointHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointResetController extends Controller
{
    /**
     * Menampilkan halaman form untuk Tutup Buku / Reset Poin
     */
    public function index()
    {
        // Hitung total siswa aktif yang akan terdampak
        $activeStudentsCount = Student::where('status', 'active')->count();
        
        // Ambil riwayat tahun-tahun sebelumnya untuk ditampilkan di tabel bawah
        $histories = StudentPointHistory::select('academic_year', DB::raw('count(*) as total_students'), DB::raw('avg(final_score) as average_score'))
                        ->groupBy('academic_year')
                        ->orderBy('academic_year', 'desc')
                        ->get();

        // Mengarah ke file view yang Anda minta
        return view('admin.bk.student_point_reset', compact('activeStudentsCount', 'histories'));
    }

    /**
     * Mengeksekusi proses salin ke riwayat dan reset poin menjadi 0
     */
    public function resetYearlyPoints(Request $request)
    {
        // Validasi input tahun ajaran yang akan ditutup
        $request->validate([
            'academic_year' => 'required|string|max:20', // Contoh: "2023/2024"
        ]);

        $academicYear = $request->academic_year;

        try {
            DB::beginTransaction();

            // 1. Ambil semua siswa yang statusnya aktif
            $students = Student::with('schoolClass')->where('status', 'active')->get();

            if ($students->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada siswa aktif untuk direset.');
            }

            $historyData = [];
            $now = now();
            
            foreach ($students as $student) {
                $historyData[] = [
                    'student_id'    => $student->id,
                    'academic_year' => $academicYear,
                    'class_name'    => $student->schoolClass->name ?? '-',
                    'final_score'   => $student->score, 
                    'notes'         => 'Penutupan buku tahun ajaran ' . $academicYear,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
            }

            // Insert massal
            foreach (array_chunk($historyData, 500) as $chunk) {
                StudentPointHistory::insert($chunk);
            }

            // Reset poin semua siswa aktif menjadi 0
            Student::where('status', 'active')->update(['score' => 0]);

            DB::commit();

            return redirect()->back()->with('success', 'Poin berhasil direset ke 0! Riwayat tahun ajaran ' . $academicYear . ' telah diarsipkan dengan aman.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}