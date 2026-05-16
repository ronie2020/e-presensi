<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\AttendanceSiswa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeroomController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $isAdminOrKepsek = $user->hasRole(['Admin', 'Kepala Sekolah']);

        if ($isAdminOrKepsek) {
            if ($request->has('class_id')) {
                $class = SchoolClass::find($request->class_id);
            } else {
                $class = SchoolClass::first();
            }

            if (!$class) {
                return redirect()->route('dashboard')->with('error', 'Tabel kelas masih kosong. Buat data kelas terlebih dahulu.');
            }
            
            $allClasses = SchoolClass::orderBy('name', 'asc')->get();
            
        } else {
            $class = SchoolClass::where('homeroom_teacher_id', $user->id)->first();
            
            if (!$class) {
                return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar sebagai Wali Kelas untuk kelas mana pun.');
            }
            
            $allClasses = null; 
        }

        // --- MENGAMBIL DATA SISWA & EAGER LOADING RELASI BARU ---
        $students = Student::with(['disciplineRecords.disciplineType', 'attendances', 'habits', 'literacyJournals'])
            ->where('class_id', $class->id)
            ->where('status', 'active')
            ->get();

        $totalStudents = $students->count();
        
        $stats = [
            'total_students' => $totalStudents,
            'total_violations' => 0,
            'total_merits' => 0,
            'alfa_count' => 0,
            'total_literacy' => 0, // Statistik Literasi
            'total_habits' => 0,   // Statistik Pembiasaan
        ];

        $warningStudents = collect();
        $topStudents = collect();
        $topLiteracy = collect(); // Leaderboard Literasi
        $topHabits = collect();   // Leaderboard Habit

        $startOfMonth = Carbon::now()->startOfMonth();

        foreach ($students as $student) {
            // 1. Poin Kedisiplinan
            $violationPoints = $student->disciplineRecords->where('disciplineType.type', 'Pelanggaran')->sum('disciplineType.point_value');
            $meritPoints = $student->disciplineRecords->where('disciplineType.type', 'Kebaikan')->sum('disciplineType.point_value');
            
            $stats['total_violations'] += $violationPoints;
            $stats['total_merits'] += $meritPoints;

            // 2. Absensi
            $alfaThisMonth = $student->attendances
                ->where('attendance_date', '>=', $startOfMonth)
                ->whereIn('status', ['Alfa', 'Alpa', 'Alpha', 'Tanpa Keterangan'])
                ->count();
            
            $stats['alfa_count'] += $alfaThisMonth;

            // 3. Literasi (Jumlah buku direview bulan ini)
            $literacyCount = $student->literacyJournals
                ->where('created_at', '>=', $startOfMonth)
                ->count();
            $stats['total_literacy'] += $literacyCount;

            // 4. Pembiasaan/Habit (Jumlah hari lapor bulan ini)
            $habitCount = $student->habits
                ->where('report_date', '>=', $startOfMonth->format('Y-m-d'))
                ->count();
            $stats['total_habits'] += $habitCount;

            // --- PENGELOMPOKAN DATA ---
            
            // Deteksi Dini
            if ($violationPoints >= 50 || $alfaThisMonth >= 3) {
                $warningStudents->push((object)[
                    'id' => $student->id,
                    'name' => $student->name,
                    'photo' => $student->photo_path,
                    'violation_points' => $violationPoints,
                    'alfa_count' => $alfaThisMonth,
                    'issue' => $alfaThisMonth >= 3 ? 'Sering Alpa' : 'Pelanggaran Tinggi'
                ]);
            }

            // Top Karakter
            if ($meritPoints > 0) {
                $topStudents->push((object)[
                    'name' => $student->name,
                    'photo' => $student->photo_path,
                    'merit_points' => $meritPoints,
                ]);
            }

            // Top Literasi
            if ($literacyCount > 0) {
                $topLiteracy->push((object)[
                    'name' => $student->name,
                    'photo' => $student->photo_path,
                    'count' => $literacyCount,
                ]);
            }

            // Top Pembiasaan (Paling Rajin Lapor)
            if ($habitCount > 0) {
                $topHabits->push((object)[
                    'name' => $student->name,
                    'photo' => $student->photo_path,
                    'count' => $habitCount,
                ]);
            }
        }

        // Urutkan dan ambil top 5/10
        $warningStudents = $warningStudents->sortByDesc('violation_points')->take(10);
        $topStudents = $topStudents->sortByDesc('merit_points')->take(5);
        $topLiteracy = $topLiteracy->sortByDesc('count')->take(5);
        $topHabits = $topHabits->sortByDesc('count')->take(5);

        return view('homeroom.dashboard', compact('class', 'stats', 'warningStudents', 'topStudents', 'topLiteracy', 'topHabits', 'isAdminOrKepsek', 'allClasses'));
    }
}