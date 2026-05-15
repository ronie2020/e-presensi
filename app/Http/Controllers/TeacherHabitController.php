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
     * Fungsi Helper untuk Mengubah Tipe Periode menjadi Rentang Tanggal (Start & End)
     */
    private function getPeriodRange($periodType, $periodValue)
    {
        try {
            if ($periodType === 'weekly' && strpos($periodValue, 'W') !== false) {
                // Format: "YYYY-W##" (contoh: 2026-W18)
                $year = (int) substr($periodValue, 0, 4);
                $week = (int) substr($periodValue, 6);
                $startDate = Carbon::now()->setISODate($year, $week)->startOfWeek();
                $endDate = $startDate->copy()->endOfWeek();
            } elseif ($periodType === 'monthly') {
                // Format: "YYYY-MM" (contoh: 2026-05)
                $year = (int) substr($periodValue, 0, 4);
                $month = (int) substr($periodValue, 5, 2);
                $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
            } else {
                // Default: Harian (Format: YYYY-MM-DD)
                $startDate = Carbon::parse($periodValue)->startOfDay();
                $endDate = Carbon::parse($periodValue)->endOfDay();
            }
        } catch (\Exception $e) {
            // Fallback jika format tanggal tidak valid
            $startDate = Carbon::now()->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        }

        return [$startDate, $endDate];
    }

    /**
     * Halaman Monitoring Utama
     */
    public function index(Request $request)
    {
        // 1. Ambil Filter Periode & Kelas
        $periodType = $request->input('period_type', 'daily');
        // JS mengubah nama input menjadi 'period_value', fallback ke 'date' jika JS tidak berjalan
        $periodValue = $request->input('period_value', $request->input('date', Carbon::now()->format('Y-m-d')));
        $classId = $request->input('class_id');

        // Terjemahkan string periode menjadi range tanggal menggunakan Helper
        [$startDate, $endDate] = $this->getPeriodRange($periodType, $periodValue);
        
        // Simpan nilai inputan asli untuk dikirim balik ke View (agar input form tidak kosong)
        $date = $periodValue; 

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
            
            // [OPTIMASI N+1 QUERY]: Ambil semua habit menggunakan whereBetween untuk mendukung Harian/Mingguan/Bulanan
            $studentIds = $students->pluck('id');
            $habits = StudentHabit::whereIn('student_id', $studentIds)
                        ->whereBetween('report_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                        ->orderBy('report_date', 'asc') // Urutkan asc agar keyBy menimpa data dan menyisakan entri terbaru
                        ->get()
                        ->keyBy('student_id'); 

            // Gabungkan data habit ke masing-masing siswa
            $students->map(function ($student) use ($habits) {
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

            // Hitung berapa siswa unik yang sudah lapor di rentang waktu tersebut
            $submittedAll = StudentHabit::whereBetween('report_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                                ->whereHas('student.schoolClass') 
                                ->distinct('student_id') // Gunakan distinct agar tidak menghitung dobel di mode mingguan/bulanan
                                ->count('student_id');

            $stats['submitted'] = $submittedAll;
            $stats['missing'] = max(0, $totalStudentsAll - $submittedAll);
            $stats['percentage'] = $totalStudentsAll > 0 ? round(($submittedAll / $totalStudentsAll) * 100) : 0;

            // Hitung jurnal yang belum dinilai (pending feedback)
            $stats['pending_feedback'] = StudentHabit::whereHas('student.schoolClass')
                                            ->whereBetween('report_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                                            ->whereNull('teacher_feedback')
                                            ->count();

            // 5. Ambil Feed Aktivitas Terbaru
            $statusFilter = $request->input('status');
            
            $query = StudentHabit::with(['student', 'student.schoolClass'])
                ->whereHas('student.schoolClass') 
                ->whereBetween('report_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

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
        $periodType = $request->input('period_type', 'daily');
        $periodValue = $request->input('period_value', $request->input('date', Carbon::now()->format('Y-m-d')));
        $classId = $request->input('class_id');

        // Terapkan helper yang sama untuk halaman PDF
        [$startDate, $endDate] = $this->getPeriodRange($periodType, $periodValue);
        $date = $periodValue; // Teruskan ke view sebagai nilai asli

        // LOGIKA BARU: Jika kelas dipilih tampilkan kelas tersebut, jika tidak tampilkan SEMUA KELAS
        if ($classId) {
            $class = SchoolClass::findOrFail($classId);
            $students = Student::where('class_id', $classId)->orderBy('name', 'asc')->get();
        } else {
            $class = null; // Menandakan mode Cetak Global
            
            // PERBAIKAN DI SINI: Tambahkan whereHas agar hanya menarik siswa yang punya kelas aktif
            $students = Student::with('schoolClass')
                        ->whereHas('schoolClass') 
                        ->orderBy('class_id')
                        ->orderBy('name', 'asc')
                        ->get();
        }
        
        // [OPTIMASI N+1 QUERY UNTUK HALAMAN CETAK]
        $studentIds = $students->pluck('id');
        $habits = StudentHabit::whereIn('student_id', $studentIds)
                    ->whereBetween('report_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->orderBy('report_date', 'asc')
                    ->get()
                    ->keyBy('student_id');

        // Gabungkan data
        $students->each(function($student) use ($habits) {
            $student->habit_data = $habits->get($student->id);
            $student->habit_status = $student->habit_data ? 'submitted' : 'missing';
        });

        return view('habits.print', compact('students', 'date', 'class', 'periodType'));
    }

    /**
     * Papan Peringkat Siswa Terajin
     */
    public function leaderboard(Request $request)
    {
        // Ambil input bulan (default: bulan ini)
        // Format input bertipe 'month' adalah "YYYY-MM" (contoh: "2026-05")
        $filterMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $year = (int) substr($filterMonth, 0, 4);
        $month = (int) substr($filterMonth, 5, 2);

        // [PERBAIKAN MVC]: Memindahkan Query dari file Blade ke Controller
        $leaderboard = StudentHabit::with(['student', 'student.schoolClass'])
            ->selectRaw('student_id, count(*) as total_days')
            ->whereMonth('report_date', $month)
            ->whereYear('report_date', $year)
            ->groupBy('student_id')
            ->orderByDesc('total_days')
            ->take(50) 
            ->get();

        return view('habits.teacher_leaderboard', compact('leaderboard', 'filterMonth')); 
    }
}