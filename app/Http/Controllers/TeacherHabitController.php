<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentHabit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class TeacherHabitController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Filter (Tanggal & Kelas)
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $classId = $request->input('class_id');

        // 2. Ambil Daftar Kelas untuk Dropdown
        $classes = SchoolClass::orderBy('name')->get();

        // 3. Ambil Data Siswa & Status Jurnalnya
        $students = collect();
        $stats = [
            'submitted' => 0,
            'missing' => 0,
            'percentage' => 0
        ];

        if ($classId) {
            // DETEKSI NAMA KOLOM KELAS (Supaya tidak error column not found)
            $classColumn = $this->detectClassColumn();

            // Ambil siswa di kelas tersebut menggunakan nama kolom yang benar
            $students = Student::where($classColumn, $classId)
                ->orderBy('name')
                ->get()
                ->map(function ($student) use ($date) {
                    // Cek apakah siswa ini punya jurnal di tanggal tersebut
                    $habit = StudentHabit::where('student_id', $student->id)
                                ->where('report_date', $date)
                                ->first();
                    
                    $student->habit_status = $habit ? 'submitted' : 'missing';
                    $student->habit_data = $habit; // Simpan data detailnya untuk modal
                    
                    return $student;
                });

            // Hitung Statistik
            $totalStudents = $students->count();
            $submitted = $students->where('habit_status', 'submitted')->count();
            
            $stats['submitted'] = $submitted;
            $stats['missing'] = $totalStudents - $submitted;
            $stats['percentage'] = $totalStudents > 0 ? round(($submitted / $totalStudents) * 100) : 0;
        }

        return view('habits.teacher_index', compact('classes', 'students', 'date', 'classId', 'stats'));
    }

    // Fungsi untuk melihat detail jurnal (AJAX)
    public function show($id)
    {
        $habit = StudentHabit::with('student.schoolClass')->findOrFail($id);
        
        // Render view partial (untuk isi modal)
        return view('habits.partials.detail_modal', compact('habit'))->render();
    }
    
    // Fungsi Validasi/Feedback Guru
    public function feedback(Request $request, $id)
    {
        $habit = StudentHabit::findOrFail($id);
        
        $habit->update([
            'teacher_feedback' => $request->feedback,
            'teacher_id' => auth()->id(),
            'validated_at' => now()
        ]);

        return back()->with('success', 'Feedback berhasil dikirim.');
    }

    /**
     * Helper: Mendeteksi nama kolom Foreign Key kelas di tabel students.
     */
    private function detectClassColumn()
    {
        // Daftar prioritas nama kolom yang mungkin dipakai
        $candidates = [
            'school_class_id', // Standar Laravel
            'class_id',        // Umum
            'classroom_id',    // Variasi
            'rombel_id',       // Istilah Dapodik/Indonesia
            'grade_id',        // Variasi lain
            'group_id',
            'kelas_id'
        ];

        // Cek langsung ke struktur tabel database
        foreach ($candidates as $col) {
            if (Schema::hasColumn('students', $col)) {
                return $col;
            }
        }
        
        return 'school_class_id'; // Default Fallback jika semua gagal
    }
public function print(Request $request)
{
    // 1. Ambil filter dari request, default ke hari ini jika kosong
    $date = $request->date ?? now()->toDateString();
    $classId = $request->class_id;

    // 2. Validasi sederhana
    if (!$classId) {
        return redirect()->back()->with('error', 'Silakan pilih kelas terlebih dahulu.');
    }

    // 3. Ambil Data Kelas
    $class = SchoolClass::findOrFail($classId);

    // 4. Ambil Siswa & Data Kebiasaan (Habits)
    $students = Student::where('class_id', $classId)
        ->orderBy('name', 'asc')
        ->get()
        ->each(function($student) use ($date) {
            // Attach habit data manual untuk tanggal tersebut
            $student->habit_data = StudentHabit::where('student_id', $student->id)
                ->whereDate('report_date', $date)
                ->first();
        });

    // 5. Tampilkan View Cetak
    // Pastikan file view 'teacher.habits.print' sudah dibuat sesuai kode sebelumnya
    return view('habits.print', compact('students', 'date', 'class'));
}
    
}