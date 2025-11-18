<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AttendanceSiswa;
// use App\Models\Discipline; // Ganti ini
use App\Models\DisciplineRecord; // Menjadi ini
use Carbon\Carbon;

class StudentPortalController extends Controller
{
    /**
     * Menampilkan halaman pencarian siswa.
     */
    public function index()
    {
        // Menggunakan layout publik
        return view('portal.index');
    }

    /**
     * Memproses pencarian siswa berdasarkan NIS/NISN.
     */
    public function search(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string|max:100'
        ], [
            'student_id.required' => 'NIS/NISN wajib diisi.'
        ]);

        // Cari siswa berdasarkan student_id (NISN)
        $student = Student::where('student_id', $request->student_id)->first();

        if (!$student) {
            // Jika tidak ketemu, kembalikan ke halaman pencarian dengan error
            return redirect()->route('portal.index')
                ->with('error', 'Siswa dengan NIS/NISN ' . $request->student_id . ' tidak ditemukan.');
        }

        // Jika ketemu, alihkan ke halaman hasil
        return redirect()->route('portal.show', ['student_id' => $student->student_id]);
    }

    /**
     * Menampilkan dashboard rekap siswa.
     */
    public function show($student_id)
    {
        // Cari siswa, jika tidak ada akan error 404
        $student = Student::with('schoolClass') // Eager load relasi kelas
            ->where('student_id', $student_id)
            ->firstOrFail();

        $year = now()->year; // Kita ambil rekap "Tahun Ini"

        // 1. Rekap Kehadiran (Tahun Ini)
        // Asumsi: 'Masuk' dihitung sebagai Hadir. Sakit/Izin/Alpa dicatat manual.
        $hadir = AttendanceSiswa::where('student_id', $student->id)
                    ->whereYear('attendance_date', $year)
                    ->where('type', 'Masuk') // Hanya hitung 'Masuk' sebagai 'Hadir'
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

        // 2. REKAP POIN DISIPLIN (LOGIKA DIPERBARUI)
        // Kita akan join dengan tabel 'discipline_types'
        // Asumsi: tabel 'discipline_types' punya kolom 'points' dan 'type' ('Pelanggaran'/'Kebaikan')
        
        $poin_pelanggaran = DisciplineRecord::where('student_id', $student->id)
                            ->join('discipline_types', 'discipline_records.discipline_type_id', '=', 'discipline_types.id')
                            ->where('discipline_types.type', 'Pelanggaran') // Asumsi kolom type
                            ->sum('discipline_types.point_value'); // PERBAIKAN: points -> point_value
                            
        $poin_kebaikan = DisciplineRecord::where('student_id', $student->id)
                            ->join('discipline_types', 'discipline_records.discipline_type_id', '=', 'discipline_types.id')
                            ->where('discipline_types.type', 'Kebaikan') // Asumsi kolom type
                            ->sum('discipline_types.point_value'); // PERBAIKAN: points -> point_value

        // 3. RIWAYAT CATATAN DISIPLIN (LOGIKA DIPERBARUI)
        // Kita eager load relasi 'disciplineType' (dari DisciplineRecord) dan 'recorder'
        $discipline_history = DisciplineRecord::where('student_id', $student->id)
                                ->with(['disciplineType', 'recorder']) // Memuat relasi
                                ->orderBy('date', 'desc')
                                ->get();
                                
        // Kirim semua data ke view
        return view('portal.show', [
            'student' => $student,
            'hadir' => $hadir,
            'sakit' => $sakit,
            'izin' => $izin,
            'alpa' => $alpa,
            'poin_pelanggaran' => $poin_pelanggaran,
            'poin_kebaikan' => $poin_kebaikan,
            'discipline_history' => $discipline_history,
        ]);
    }
}