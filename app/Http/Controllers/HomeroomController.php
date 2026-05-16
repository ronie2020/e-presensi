<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\AttendanceSiswa;
use App\Models\GradeRecord; 
use App\Models\GradeItem;   
use App\Models\User;
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

        // --- OPTIMASI KINERJA (N+1 QUERY FIX) ---
        // Mengambil semua nilai akademik siswa sekaligus untuk menghindari query di dalam loop
        $studentIds = $students->pluck('id');
        $latestGradeRecords = GradeRecord::whereIn('student_id', $studentIds)
            ->orderBy('id', 'desc')
            ->get()
            ->unique('student_id')
            ->keyBy('student_id');

        $gradeRecordIds = $latestGradeRecords->pluck('id');
        $averageGrades = GradeItem::whereIn('grade_record_id', $gradeRecordIds)
            ->selectRaw('grade_record_id, avg(score) as avg_score')
            ->groupBy('grade_record_id')
            ->pluck('avg_score', 'grade_record_id');
        // ----------------------------------------

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

            // --- NOMINASI (Menggunakan data dari luar loop untuk performa) ---
            if ($alfaThisSemester <= 3 && $violationPoints <= 30) {
                
                $latestGradeRecord = $latestGradeRecords->get($student->id);
                $academicScore = 0;
                
                if ($latestGradeRecord) {
                    $academicScore = round($averageGrades->get($latestGradeRecord->id, 0));
                }

                $attendanceScore = max(0, 100 - ($alfaThisSemester * 15));
                $netDisciplineScore = ($meritPoints - $violationPoints) * 2;
                $taskScore = ($literacyCount * 3) + ($habitCount * 1);
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

        // PERBAIKAN BUG RANKING: Tambahkan ->values() untuk me-reset index array menjadi urutan baru
        $warningStudents = $warningStudents->sortByDesc('violation_points')->take(10)->values();
        $topStudents = $topStudents->sortByDesc('merit_points')->take(5)->values();
        $topLiteracy = $topLiteracy->sortByDesc('count')->take(5)->values();
        $topHabits = $topHabits->sortByDesc('count')->take(5)->values();
        $awardNominees = $awardNominees->sortByDesc('total_score')->take(10)->values();

        return view('homeroom.dashboard', compact(
            'class', 'stats', 'warningStudents', 'topStudents', 
            'topLiteracy', 'topHabits', 'awardNominees', 
            'isAdminOrKepsek', 'allClasses'
        ));
    }

    /**
     * FUNGSI: Mencetak Laporan Evaluasi Kelas
     */
    public function print(Request $request)
    {
        $user = Auth::user();
        
        // Cek target kelas
        if ($request->has('class_id')) {
            // Admin mem-bypass dengan parameter URL
            $class = SchoolClass::findOrFail($request->class_id);
        } else {
            // Cek apakah dia wali kelas
            $class = SchoolClass::where('homeroom_teacher_id', $user->id)->first();
            // Jika dia bukan wali kelas (Admin) dan tidak kirim class_id, ambil kelas pertama
            if (!$class && $user->hasRole(['Admin', 'Kepala Sekolah'])) {
                $class = SchoolClass::first();
            }
        }

        if (!$class) {
            return back()->with('error', 'Data kelas tidak ditemukan.');
        }

        // --- MENGAMBIL NAMA WALI KELAS ---
        $teacherName = '_______________________';
        $teacherNip = '_______________________';

        if ($class->homeroom_teacher_id) {
            $teacher = \App\Models\User::find($class->homeroom_teacher_id);
            if ($teacher) {
                $teacherName = $teacher->name;
                $teacherNip = $teacher->nip ?? '_______________________';
            }
        }

        $students = Student::with(['disciplineRecords.disciplineType', 'attendances', 'habits', 'literacyJournals'])
            ->where('class_id', $class->id)
            ->where('status', 'active')
            ->get();

        $stats = [
            'total_students' => $students->count(),
            'total_violations' => 0,
            'total_merits' => 0,
            'alfa_count' => 0,
        ];

        $warningStudents = collect();
        $awardNominees = collect(); 

        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfSemester = Carbon::now()->subMonths(6); 

        // --- OPTIMASI KINERJA (N+1 QUERY FIX) ---
        $studentIds = $students->pluck('id');
        $latestGradeRecords = GradeRecord::whereIn('student_id', $studentIds)
            ->orderBy('id', 'desc')
            ->get()
            ->unique('student_id')
            ->keyBy('student_id');

        $gradeRecordIds = $latestGradeRecords->pluck('id');
        $averageGrades = GradeItem::whereIn('grade_record_id', $gradeRecordIds)
            ->selectRaw('grade_record_id, avg(score) as avg_score')
            ->groupBy('grade_record_id')
            ->pluck('avg_score', 'grade_record_id');
        // ----------------------------------------

        foreach ($students as $student) {
            $violationPoints = $student->disciplineRecords->where('disciplineType.type', 'Pelanggaran')->sum('disciplineType.point_value');
            $meritPoints = $student->disciplineRecords->where('disciplineType.type', 'Kebaikan')->sum('disciplineType.point_value');
            
            $stats['total_violations'] += $violationPoints;
            $stats['total_merits'] += $meritPoints;

            $alfaThisSemester = $student->attendances
                ->where('attendance_date', '>=', $startOfSemester)
                ->whereIn('status', ['Alfa', 'Alpa', 'Alpha', 'Tanpa Keterangan'])
                ->count();
            
            $stats['alfa_count'] += $alfaThisSemester;

            $literacyCount = $student->literacyJournals->count();
            $habitCount = $student->habits->count();

            // Peringatan Khusus
            if ($violationPoints >= 50 || $alfaThisSemester >= 5) {
                $warningStudents->push((object)[
                    'name' => $student->name,
                    'nisn' => $student->student_id,
                    'violation_points' => $violationPoints,
                    'alfa_count' => $alfaThisSemester,
                    'issue' => $alfaThisSemester >= 5 ? 'Kehadiran Sangat Kurang' : 'Sering Melanggar Aturan'
                ]);
            }

            // Nominasi
            if ($alfaThisSemester <= 3 && $violationPoints <= 30) {
                
                $latestGradeRecord = $latestGradeRecords->get($student->id);
                $academicScore = 0;
                if ($latestGradeRecord) {
                    $academicScore = round($averageGrades->get($latestGradeRecord->id, 0));
                }

                $attendanceScore = max(0, 100 - ($alfaThisSemester * 15));
                $netDisciplineScore = ($meritPoints - $violationPoints) * 2;
                $taskScore = ($literacyCount * 3) + ($habitCount * 1);
                $recommendationScore = $academicScore + $attendanceScore + $netDisciplineScore + $taskScore;

                if ($recommendationScore > 0) {
                    $awardNominees->push((object)[
                        'name' => $student->name,
                        'nisn' => $student->student_id,
                        'academic_score' => $academicScore,
                        'alfa_count' => $alfaThisSemester,
                        'violation_points' => $violationPoints,
                        'total_score' => $recommendationScore
                    ]);
                }
            }
        }

        // PERBAIKAN BUG RANKING: Tambahkan ->values()
        $warningStudents = $warningStudents->sortByDesc('violation_points')->values();
        $awardNominees = $awardNominees->sortByDesc('total_score')->take(10)->values();

        return view('homeroom.print', compact(
            'class', 'stats', 'warningStudents', 'awardNominees', 'teacherName', 'teacherNip'
        ));
    }
}