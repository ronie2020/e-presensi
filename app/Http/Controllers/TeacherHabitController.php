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
    /**
     * Halaman Monitoring Utama
     */
    public function index(Request $request)
    {
        // 1. Ambil Filter (Default hari ini)
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $classId = $request->input('class_id');

        // 2. Ambil Daftar Kelas untuk Dropdown
        $classes = SchoolClass::orderBy('name')->get();

        // 3. Inisialisasi Variabel
        $students = collect();
        $latestSubmissions = collect(); 
        $stats = [
            'submitted' => 0,
            'missing' => 0,
            'percentage' => 0
        ];

        // 4. Logika Utama
        if ($classId) {
            // === KONDISI A: JIKA KELAS DIPILIH (Statistik Per Kelas) ===
            
            $students = Student::where('class_id', $classId)
                ->orderBy('name')
                ->get()
                ->map(function ($student) use ($date) {
                    $habit = StudentHabit::where('student_id', $student->id)
                                ->whereDate('report_date', $date) 
                                ->first();
                    
                    $student->habit_status = $habit ? 'submitted' : 'missing';
                    $student->habit_data = $habit; 
                    return $student;
                });

            // Hitung Statistik Kelas
            $totalStudents = $students->count();
            $submitted = $students->where('habit_status', 'submitted')->count();
            
            $stats['submitted'] = $submitted;
            $stats['missing'] = $totalStudents - $submitted;
            $stats['percentage'] = $totalStudents > 0 ? round(($submitted / $totalStudents) * 100) : 0;

        } else {
            // === KONDISI B: JIKA BELUM PILIH KELAS (Statistik Global) ===
            
            // 1. Hitung Statistik Global (Satu Sekolah)
            // FIX: Hanya hitung siswa yang punya kelas (Siswa Aktif)
            // Sebelumnya: Student::count(); -> Ini menghitung alumni juga
            $totalStudentsAll = Student::whereHas('schoolClass')->count();

            // Hitung jumlah laporan unik hari ini (Hanya dari siswa aktif)
            $submittedAll = StudentHabit::whereDate('report_date', $date)
                                ->whereHas('student.schoolClass') // Safety: Pastikan siswa masih aktif
                                ->count();

            $stats['submitted'] = $submittedAll;
            $stats['missing'] = max(0, $totalStudentsAll - $submittedAll);
            $stats['percentage'] = $totalStudentsAll > 0 ? round(($submittedAll / $totalStudentsAll) * 100) : 0;

            // 2. Ambil Feed Aktivitas Terbaru
            $latestSubmissions = StudentHabit::with(['student', 'student.schoolClass'])
                ->whereHas('student.schoolClass') // FIX: Filter agar alumni tidak muncul di feed
                ->whereDate('report_date', $date)
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();
        }

        // Kirim semua variabel ke view
        return view('habits.teacher_index', compact('classes', 'students', 'date', 'classId', 'stats', 'latestSubmissions'));
    }

    /**
     * Modal Detail Siswa (AJAX)
     */
    public function show($id)
    {
        $habit = StudentHabit::with('student.schoolClass')->findOrFail($id);
        return view('habits.partials.detail_modal', compact('habit'))->render();
    }
    
    /**
     * Simpan Feedback Guru
     */
    public function feedback(Request $request, $id)
    {
        $habit = StudentHabit::findOrFail($id);
        
        $habit->update([
            'teacher_feedback' => $request->feedback,
            'teacher_id' => auth()->id(),
            'validated_at' => now()
        ]);

        // === TAMBAHAN FIX UNTUK AJAX ===
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Feedback berhasil disimpan!'
            ], 200);
        }

        // Fallback jika tidak menggunakan AJAX
        return back()->with('success', 'Feedback berhasil dikirim.');
    }
    /**
     * Cetak Laporan PDF/Print
     */
    public function print(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $classId = $request->class_id;

        if (!$classId) {
            return redirect()->back()->with('error', 'Silakan pilih kelas terlebih dahulu.');
        }

        $class = SchoolClass::findOrFail($classId);
        
        $students = Student::where('class_id', $classId)
            ->orderBy('name', 'asc')
            ->get()
            ->each(function($student) use ($date) {
                $student->habit_data = StudentHabit::where('student_id', $student->id)
                    ->whereDate('report_date', $date)
                    ->first();
            });

        return view('habits.print', compact('students', 'date', 'class'));
    }
}