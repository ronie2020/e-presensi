<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// --- IMPORT MODELS ---
use App\Models\Student;
use App\Models\AttendanceSiswa; 
use App\Models\LmsAssignment;
use App\Models\LmsSubmission;
// use App\Models\LibraryLoan; // Uncomment jika sudah ada model Perpustakaan

class StudentPortalController extends Controller
{
    /**
     * Halaman Dashboard Portal (PUBLIC LANDING)
     * Menampilkan pilihan menu: Portal Data, LMS, atau CBT
     */
    public function index()
    {
        return view('students.portal.index');
    }

    /**
     * Proses Pencarian Siswa berdasarkan NISN (Login tanpa password untuk Portal Publik)
     */
    public function search(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
        ]);

        // Cari siswa berdasarkan student_id (NISN) atau NIS lokal
        $student = Student::where('student_id', $request->student_id)
                    ->orWhere('nis', $request->student_id)
                    ->first();

        if (!$student) {
            return back()->with('error', 'Data siswa tidak ditemukan. Periksa kembali Nomor Induk Anda.');
        }

        // Login otomatis ke guard 'student' untuk sesi ini
        Auth::guard('student')->login($student);

        return redirect()->route('portal.show', $student->id)
                         ->with('success', 'Berhasil masuk ke Portal Informasi.');
    }

    /**
     * Halaman Utama Dashboard Siswa (Show Data)
     * Memuat semua data: Absensi, Nilai, Pelanggaran, dll.
     */
    public function show($id)
    {
        // 1. PROTEKSI HALAMAN
        if (!Auth::guard('student')->check()) {
            return redirect()->route('portal.index')->with('error', 'Silakan masukkan NISN Anda terlebih dahulu.');
        }

        $loggedInId = Auth::guard('student')->id();
        if ($loggedInId != $id) {
            return redirect()->route('portal.show', $loggedInId)->with('error', 'Anda hanya dapat mengakses data Anda sendiri.');
        }

        // 2. LOAD DATA SISWA UTAMA
        // Memuat relasi kelas dan catatan disiplin
        $student = Student::with(['schoolClass', 'disciplineRecords.disciplineType'])->findOrFail($id);
        
        // ------------------------------------------------------------------
        // 3. LOGIKA KEHADIRAN (ATTENDANCE)
        // ------------------------------------------------------------------
        
        // Ambil riwayat absen terakhir (limit 30 hari)
        $attendance_history = AttendanceSiswa::where('student_id', $id)
                                ->orderBy('attendance_date', 'desc')
                                ->limit(30)
                                ->get();
        
        // Hitung Statistik per Status
        $hadir = AttendanceSiswa::where('student_id', $id)->where('status', 'Hadir')->count();
        $sakit = AttendanceSiswa::where('student_id', $id)->where('status', 'Sakit')->count();
        $izin  = AttendanceSiswa::where('student_id', $id)->where('status', 'Izin')->count();
        $alpa  = AttendanceSiswa::where('student_id', $id)->where('status', 'Alpa')->count();

        // Data untuk Chart.js di View
        $attendanceChart = [
            'hadir' => $hadir,
            'sakit' => $sakit,
            'izin'  => $izin,
            'alpa'  => $alpa
        ];

        // ------------------------------------------------------------------
        // 4. LOGIKA LMS (TUGAS & NILAI) - DIPERBAIKI (class_id)
        // ------------------------------------------------------------------
        
        $assignments = collect(); // Default kosong jika siswa belum punya kelas
        
        if ($student->class_id) {
            // [FIX] Menggunakan 'class_id' sesuai struktur tabel database Anda
            // [OPTIMISASI] Memuat relasi 'subject' agar tidak N+1 Query saat grouping
            $assignments = LmsAssignment::where('class_id', $student->class_id)
                            ->with('subject') 
                            ->orderBy('created_at', 'desc')
                            ->get();
        }

        // Kelompokkan tugas berdasarkan Nama Mapel
        $lms_assignments_grouped = $assignments->groupBy(function($item) {
            return $item->subject->name ?? 'Mata Pelajaran Umum';
        });

        // Ambil Nilai yang sudah disubmit siswa ini
        $submissions = LmsSubmission::where('student_id', $id)->get();
        
        // Format: [assignment_id => grade] untuk akses cepat di view
        $lms_grades = $submissions->pluck('grade', 'assignment_id')->toArray();

        // ------------------------------------------------------------------
        // 5. LOGIKA KEDISIPLINAN & PRESTASI
        // ------------------------------------------------------------------
        
        $all_records = $student->disciplineRecords; 
        
        // Filter Pelanggaran
        $violations = $all_records->where('type', 'violation'); 
        $total_violation_points = $violations->sum(fn($r) => $r->disciplineType->point_value ?? 0);

        // Filter Prestasi
        $achievements = $all_records->where('type', 'achievement'); 
        $total_merit_points = $achievements->sum(fn($r) => $r->disciplineType->point_value ?? 0);

        // ------------------------------------------------------------------
        // 6. LOGIKA PERPUSTAKAAN (Placeholder Aman)
        // ------------------------------------------------------------------
        $library_history = collect([]); 
        $library_visits = 0;

        // Contoh implementasi (Uncomment jika model LibraryLoan sudah ada):
        /*
        if (class_exists('App\Models\LibraryLoan')) {
            $library_history = \App\Models\LibraryLoan::where('student_id', $id)->latest()->take(5)->get();
            $library_visits = $library_history->count();
        }
        */

        // ------------------------------------------------------------------
        // 7. LOGIKA AKADEMIK / CHART NILAI (Placeholder Aman)
        // ------------------------------------------------------------------
        $academic_record = null; 
        
        // Data Dummy agar grafik tidak error (default nol)
        $chartData = [
            'labels' => ['MTK', 'IPA', 'IPS', 'B.INDO', 'B.ING', 'PAI'], 
            'scores' => [0, 0, 0, 0, 0, 0] 
        ];
        
        // Logika Real (Opsional - sesuaikan dengan tabel nilai Anda nanti)
        // $scores = \App\Models\AcademicScore::where('student_id', $id)->with('subject')->get();
        // ... logika mapping ...

        // ------------------------------------------------------------------
        // 8. LOGIKA JURNAL & KEAGAMAAN (Placeholder Aman)
        // ------------------------------------------------------------------
        $teaching_journals = []; 
        $sholat_dhuha = 0;
        $sholat_dhuhur = 0;
        $religious_history = [];

        return view('students.portal.show', compact(
            'student', 
            // Kehadiran
            'hadir', 'sakit', 'izin', 'alpa', 'attendance_history', 'attendanceChart',
            // LMS
            'lms_assignments_grouped', 'lms_grades',
            // Disiplin
            'violations', 'total_violation_points', 
            'achievements', 'total_merit_points',
            // Perpustakaan
            'library_visits', 'library_history',
            // Akademik
            'academic_record', 'chartData',
            // Lainnya
            'teaching_journals',
            'sholat_dhuha', 'sholat_dhuhur', 'religious_history'
        ));
    }

    /**
     * Cetak Kartu OSIS / Biodata
     */
    public function printCard($id)
    {
        if (!Auth::guard('student')->check() || Auth::guard('student')->id() != $id) {
             return redirect()->route('portal.index');
        }

        // Memuat relasi kelas agar nama kelas muncul di kartu
        $student = Student::with('schoolClass')->findOrFail($id);
        
        return view('students.osis_card', compact('student'));
    }
}