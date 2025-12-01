<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AttendanceSiswa;
use App\Models\DisciplineRecord;
use App\Models\LibraryVisit;
use App\Models\Borrowing;
use App\Models\Achievement;
use Illuminate\Http\Request;

class StudentPortalController extends Controller
{
    public function index()
    {
        return view('portal.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
        ]);

        // Cari berdasarkan NISN (student_id) atau NIS
        $student = Student::where('student_id', $request->student_id)
                    ->orWhere('nis', $request->student_id)
                    ->first();

        if (!$student) {
            return back()->with('error', 'Data siswa tidak ditemukan. Periksa kembali Nomor Induk Anda.');
        }

        return redirect()->route('portal.show', $student->id);
    }

    public function show($id)
    {
        // 1. LOAD SISWA + RELASI
        $student = Student::with(['schoolClass', 'disciplineRecords.disciplineType'])->findOrFail($id);
        
        $year = date('Y');

        // --- 1. REKAP ABSENSI HARIAN ---
        $hadir = AttendanceSiswa::where('student_id', $student->id)
                    ->whereYear('attendance_date', $year)
                    ->where('type', 'Harian')
                    ->whereIn('status', ['Hadir', 'Terlambat']) 
                    ->count();
        
        $sakit = AttendanceSiswa::where('student_id', $student->id)
                    ->whereYear('attendance_date', $year)
                    ->where('status', 'Sakit')
                    ->count();

        $izin = AttendanceSiswa::where('student_id', $student->id)
                    ->whereYear('attendance_date', $year)
                    ->where('status', 'Izin')
                    ->count();
      
        // [FIX] DEFINISI VARIABEL $alpa YANG HILANG
        $alpa = AttendanceSiswa::where('student_id', $student->id)
                    ->whereYear('attendance_date', $year)
                    ->where('status', 'Alfa') // Pastikan di DB statusnya 'Alfa' (bukan Alpa)
                    ->count();
        
        $attendance_history = AttendanceSiswa::where('student_id', $student->id)
                    ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
                    ->latest('attendance_date')
                    ->limit(7)
                    ->get();

        // --- 2. REKAP KEAGAMAAN ---
        $sholat_dhuha = AttendanceSiswa::where('student_id', $student->id)
                    ->whereYear('attendance_date', $year)
                    ->where('type', 'Keagamaan')
                    ->where('activity', 'Dhuha')
                    ->count();

        $sholat_dhuhur = AttendanceSiswa::where('student_id', $student->id)
                    ->whereYear('attendance_date', $year)
                    ->where('type', 'Keagamaan')
                    ->whereIn('activity', ['Dhuhur', 'Duhur'])
                    ->count();
        
        $religious_history = AttendanceSiswa::where('student_id', $student->id)
                    ->where('type', 'Keagamaan')
                    ->latest('created_at')
                    ->limit(5)
                    ->get();

        // --- 3. REKAP DISIPLIN ---
        // Hitung poin dari relasi yang sudah di-load (agar akurat)
        $poin_pelanggaran = $student->disciplineRecords->filter(function ($record) {
            return $record->disciplineType && $record->disciplineType->type == 'Pelanggaran';
        })->sum(function ($record) {
            return $record->disciplineType->point_value;
        });

        $poin_kebaikan = $student->disciplineRecords->filter(function ($record) {
            return $record->disciplineType && $record->disciplineType->type == 'Kebaikan';
        })->sum(function ($record) {
            return $record->disciplineType->point_value;
        });

        $discipline_history = DisciplineRecord::with('disciplineType')
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        // --- 4. PRESTASI ---
        $achievements = [];
        if (class_exists('App\Models\Achievement')) {
            $achievements = Achievement::where('student_id', $student->id)
                ->orderBy('date', 'desc')
                ->get();
        }

        // --- 5. DATA PERPUSTAKAAN ---
        $library_visits = 0;
        if (class_exists('App\Models\LibraryVisit')) {
            $library_visits = LibraryVisit::where('student_id', $student->id)->count();
        }

        $borrowing_history = [];
        if (class_exists('App\Models\Borrowing')) {
            $borrowing_history = Borrowing::with('book')
                ->where('student_id', $student->id)
                ->orderBy('borrow_date', 'desc')
                ->limit(10)
                ->get();
        }

        // Pastikan semua variabel di sini SUDAH didefinisikan di atas
        return view('portal.show', compact(
            'student', 
            'hadir', 'sakit', 'izin', 'alpa', 'attendance_history',
            'sholat_dhuha', 'sholat_dhuhur', 'religious_history',
            'poin_pelanggaran', 'poin_kebaikan', 'discipline_history',
            'achievements',
            'library_visits', 'borrowing_history'
        ));
    }
}