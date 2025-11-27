<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AttendanceSiswa;
use App\Models\DisciplineRecord;
use App\Models\LibraryVisit;
use App\Models\Borrowing;
use App\Models\Achievement; // Pastikan model ini ada
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
        $student = Student::with('schoolClass')->findOrFail($id);
        
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
      
        $alpa = AttendanceSiswa::where('student_id', $student->id)
                    ->whereYear('attendance_date', $year)
                    ->where('status', 'Alpa')
                    ->count();
        
        // (BARU) Riwayat Kehadiran 10 Terakhir
        $attendance_history = AttendanceSiswa::where('student_id', $student->id)
                    ->where('type', 'Harian')
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
        
        // (BARU) Riwayat Ibadah Terakhir
        $religious_history = AttendanceSiswa::where('student_id', $student->id)
                    ->where('type', 'Keagamaan')
                    ->latest('created_at')
                    ->limit(5)
                    ->get();

        // --- 3. REKAP DISIPLIN ---
        $poin_pelanggaran = DisciplineRecord::where('student_id', $student->id)
            ->whereHas('disciplineType', function($q) { $q->where('type', 'Pelanggaran'); })
            ->with('disciplineType')->get()->sum(fn($r) => $r->disciplineType->point_value);

        $poin_kebaikan = DisciplineRecord::where('student_id', $student->id)
            ->whereHas('disciplineType', function($q) { $q->where('type', 'Kebaikan'); })
            ->with('disciplineType')->get()->sum(fn($r) => $r->disciplineType->point_value);

        $discipline_history = DisciplineRecord::with(['disciplineType', 'recorder'])
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        // --- 4. PRESTASI ---
        // Cek jika model Achievement ada, jika tidak kosongkan array agar tidak error
        $achievements = [];
        if (class_exists('App\Models\Achievement')) {
            $achievements = Achievement::where('student_id', $student->id)
                ->orderBy('date', 'desc')
                ->get();
        }

        // --- 5. DATA PERPUSTAKAAN ---
        $library_visits = LibraryVisit::where('student_id', $student->id)->count();

        $borrowing_history = Borrowing::with('book')
            ->where('student_id', $student->id)
            ->orderBy('borrow_date', 'desc')
            ->limit(10)
            ->get();

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