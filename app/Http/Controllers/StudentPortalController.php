<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AttendanceSiswa;
use App\Models\DisciplineRecord;
use App\Models\LibraryVisit;
use App\Models\Borrowing;
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

        // 1. REKAP ABSENSI (PERBAIKAN: Menggunakan 'attendance_date')
        
        // Hadir: Berdasarkan type 'Masuk'
        $hadir = AttendanceSiswa::where('student_id', $student->id)
                    ->whereYear('attendance_date', $year) // FIX: Pakai attendance_date
                    ->where('type', 'Masuk')
                    ->count();
        
        // Sakit, Izin, Alpa: Berdasarkan status
        $sakit = AttendanceSiswa::where('student_id', $student->id)
                    ->whereYear('attendance_date', $year) // FIX: Pakai attendance_date
                    ->where('status', 'Sakit')
                    ->count();

        $izin = AttendanceSiswa::where('student_id', $student->id)
                    ->whereYear('attendance_date', $year) // FIX: Pakai attendance_date
                    ->where('status', 'Izin')
                    ->count();
      
        $alpa = AttendanceSiswa::where('student_id', $student->id)
                    ->whereYear('attendance_date', $year) // FIX: Pakai attendance_date
                    ->where('status', 'Alpa')
                    ->count();

        // 2. Rekap Disiplin
        $poin_pelanggaran = DisciplineRecord::where('student_id', $student->id)
            ->whereHas('disciplineType', function($q) { $q->where('type', 'Pelanggaran'); })
            ->with('disciplineType')->get()->sum(fn($r) => $r->disciplineType->point_value);

        $poin_kebaikan = DisciplineRecord::where('student_id', $student->id)
            ->whereHas('disciplineType', function($q) { $q->where('type', 'Kebaikan'); })
            ->with('disciplineType')->get()->sum(fn($r) => $r->disciplineType->point_value);

        $discipline_history = DisciplineRecord::with(['disciplineType', 'recorder'])
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // 3. Data Perpustakaan
        $library_visits = LibraryVisit::where('student_id', $student->id)->count();

        $borrowing_history = Borrowing::with('book')
            ->where('student_id', $student->id)
            ->orderBy('borrow_date', 'desc')
            ->limit(10)
            ->get();

        return view('portal.show', compact(
            'student', 'hadir', 'sakit', 'izin', 'alpa',
            'poin_pelanggaran', 'poin_kebaikan', 'discipline_history',
            'library_visits', 'borrowing_history'
        ));
    }
}