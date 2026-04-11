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
            
            // Mengambil data siswa
            $students = Student::where('class_id', $classId)->orderBy('name')->get();
            
            // [OPTIMASI N+1 QUERY]: Ambil semua habit untuk siswa di kelas ini dalam 1 kali query
            $studentIds = $students->pluck('id');
            $habits = StudentHabit::whereIn('student_id', $studentIds)
                        ->whereDate('report_date', $date)
                        ->get()
                        ->keyBy('student_id'); // Jadikan student_id sebagai key array agar mudah dicari

            // Gabungkan data habit ke masing-masing siswa
            $students->map(function ($student) use ($habits) {
                // Cek apakah ada data habit berdasarkan ID siswa
                $habit = $habits->get($student->id); 
                
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
            
            $totalStudentsAll = Student::whereHas('schoolClass')->count();

            $submittedAll = StudentHabit::whereDate('report_date', $date)
                                ->whereHas('student.schoolClass') 
                                ->count();

            $stats['submitted'] = $submittedAll;
            $stats['missing'] = max(0, $totalStudentsAll - $submittedAll);
            $stats['percentage'] = $totalStudentsAll > 0 ? round(($submittedAll / $totalStudentsAll) * 100) : 0;

            $stats['pending_feedback'] = StudentHabit::whereHas('student.schoolClass')
                                            ->whereDate('report_date', $date)
                                            ->whereNull('teacher_feedback')
                                            ->count();

            // 2. Ambil Feed Aktivitas Terbaru (DENGAN FILTER & PAGINATION)
            $statusFilter = $request->input('status');
            
            $query = StudentHabit::with(['student', 'student.schoolClass'])
                ->whereHas('student.schoolClass') 
                ->whereDate('report_date', $date);

            if ($statusFilter === 'pending') {
                $query->whereNull('teacher_feedback');
            } elseif ($statusFilter === 'graded') {
                $query->whereNotNull('teacher_feedback');
            }

            $latestSubmissions = $query->orderBy('updated_at', 'desc')->paginate(12)->withQueryString();
        }
        
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

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Feedback berhasil disimpan!'
            ], 200);
        }

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
        
        // Mengambil data siswa
        $students = Student::where('class_id', $classId)->orderBy('name', 'asc')->get();
        
        // [OPTIMASI N+1 QUERY UNTUK HALAMAN CETAK]
        $studentIds = $students->pluck('id');
        $habits = StudentHabit::whereIn('student_id', $studentIds)
                    ->whereDate('report_date', $date)
                    ->get()
                    ->keyBy('student_id');

        // Gabungkan data
        $students->each(function($student) use ($habits) {
            $student->habit_data = $habits->get($student->id);
        });

        return view('habits.print', compact('students', 'date', 'class'));
    }

    /**
     * Papan Peringkat Siswa Terajin
     */
    public function leaderboard()
    {
        // [PERBAIKAN MVC]: Memindahkan Query dari file Blade ke Controller
        $leaderboard = StudentHabit::with(['student', 'student.schoolClass'])
            ->selectRaw('student_id, count(*) as total_days')
            ->whereMonth('report_date', Carbon::now()->month)
            ->whereYear('report_date', Carbon::now()->year)
            ->groupBy('student_id')
            ->orderByDesc('total_days')
            ->take(50) 
            ->get();

        return view('habits.teacher_leaderboard', compact('leaderboard')); 
    }
}