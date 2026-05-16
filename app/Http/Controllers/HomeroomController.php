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

        // --- MENGAMBIL DATA SISWA & EAGER LOADING ---
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
            'total_literacy' => 0, 
            'total_habits' => 0,   
        ];

        $warningStudents = collect();
        $topStudents = collect();
        $topLiteracy = collect();
        $topHabits = collect();
        $awardNominees = collect(); 

        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfSemester = Carbon::now()->subMonths(6); 

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
                
            $alfaThisSemester = $student->attendances
                ->where('attendance_date', '>=', $startOfSemester)
                ->whereIn('status', ['Alfa', 'Alpa', 'Alpha', 'Tanpa Keterangan'])
                ->count();
            
            $stats['alfa_count'] += $alfaThisMonth;

            // 3. Literasi & Habit (Tugas)
            $literacyCount = $student->literacyJournals->count();
            $habitCount = $student->habits->count();
            
            $stats['total_literacy'] += $student->literacyJournals->where('created_at', '>=', $startOfMonth)->count();
            $stats['total_habits'] += $student->habits->where('report_date', '>=', $startOfMonth->format('Y-m-d'))->count();

            // --- PENGELOMPOKAN DATA DASHBOARD ATAS ---
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

            if ($meritPoints > 0) {
                $topStudents->push((object)[
                    'name' => $student->name,
                    'photo' => $student->photo_path,
                    'merit_points' => $meritPoints,
                ]);
            }

            $litCountMonth = $student->literacyJournals->where('created_at', '>=', $startOfMonth)->count();
            if ($litCountMonth > 0) {
                $topLiteracy->push((object)[
                    'name' => $student->name,
                    'photo' => $student->photo_path,
                    'count' => $litCountMonth,
                ]);
            }

            $habCountMonth = $student->habits->where('report_date', '>=', $startOfMonth->format('Y-m-d'))->count();
            if ($habCountMonth > 0) {
                $topHabits->push((object)[
                    'name' => $student->name,
                    'photo' => $student->photo_path,
                    'count' => $habCountMonth,
                ]);
            }

            // ====================================================================
            // ALGORITMA BARU: NOMINASI PENGHARGAAN SISWA TELADAN (LONGGAR TAPI FAIR)
            // Toleransi: Maksimal 3 Alpa dan 30 Poin Pelanggaran dalam 1 semester.
            // ====================================================================
            if ($alfaThisSemester <= 3 && $violationPoints <= 30) {
                
                $academicScore = $student->score ?? 0; // Skala 0-100

                // 1. Skor Kehadiran (Sempurna = 100, tiap 1 Alpa dikurangi 15 poin)
                $attendanceScore = 100 - ($alfaThisSemester * 15);

                // 2. Skor Sikap Bersih (Kebaikan dikurangi Pelanggaran, dikali bobot 2)
                $netDisciplineScore = ($meritPoints - $violationPoints) * 2;

                // 3. Skor Keaktifan Tugas (Literasi x 3, Habit x 1)
                $taskScore = ($literacyCount * 3) + ($habitCount * 1);

                // TOTAL SKOR REKOMENDASI
                $recommendationScore = $academicScore + $attendanceScore + $netDisciplineScore + $taskScore;

                if ($recommendationScore > 0) {
                    $awardNominees->push((object)[
                        'id' => $student->id,
                        'name' => $student->name,
                        'photo' => $student->photo_path,
                        'academic_score' => $academicScore,
                        'attendance_score' => $attendanceScore,
                        'net_discipline' => $netDisciplineScore,
                        'task_score' => $taskScore,
                        'total_score' => $recommendationScore,
                        'alfa_count' => $alfaThisSemester,
                        'violation_points' => $violationPoints
                    ]);
                }
            }
        }

        // Urutkan dan ambil top
        $warningStudents = $warningStudents->sortByDesc('violation_points')->take(10);
        $topStudents = $topStudents->sortByDesc('merit_points')->take(5);
        $topLiteracy = $topLiteracy->sortByDesc('count')->take(5);
        $topHabits = $topHabits->sortByDesc('count')->take(5);
        
        // Ambil Top 10 Nominasi
        $awardNominees = $awardNominees->sortByDesc('total_score')->take(10);

        return view('homeroom.dashboard', compact(
            'class', 'stats', 'warningStudents', 'topStudents', 
            'topLiteracy', 'topHabits', 'awardNominees', 
            'isAdminOrKepsek', 'allClasses'
        ));
    }
}