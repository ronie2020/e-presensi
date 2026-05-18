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

        // --- FILTER PERIODE WAKTU ---
        $period = $request->input('period', 'this_month');
        $currentStart = Carbon::now()->startOfMonth();
        $currentEnd = Carbon::now()->endOfMonth();
        $prevStart = Carbon::now()->subMonth()->startOfMonth();
        $prevEnd = Carbon::now()->subMonth()->endOfMonth();

        if ($period == 'last_month') {
            $currentStart = Carbon::now()->subMonth()->startOfMonth();
            $currentEnd = Carbon::now()->subMonth()->endOfMonth();
            $prevStart = Carbon::now()->subMonths(2)->startOfMonth();
            $prevEnd = Carbon::now()->subMonths(2)->endOfMonth();
        } elseif ($period == 'semester_1') {
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;
            $year = $currentMonth < 7 ? $currentYear - 1 : $currentYear;
            $currentStart = Carbon::create($year, 7, 1)->startOfDay();
            $currentEnd = Carbon::create($year, 12, 31)->endOfDay();
            $prevStart = Carbon::create($year, 1, 1)->startOfDay();
            $prevEnd = Carbon::create($year, 6, 30)->endOfDay();
        } elseif ($period == 'semester_2') {
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;
            $year = $currentMonth < 7 ? $currentYear : $currentYear + 1;
            $currentStart = Carbon::create($year, 1, 1)->startOfDay();
            $currentEnd = Carbon::create($year, 6, 30)->endOfDay();
            $prevStart = Carbon::create($year - 1, 7, 1)->startOfDay();
            $prevEnd = Carbon::create($year - 1, 12, 31)->endOfDay();
        }

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

        // Untuk menghitung Tren
        $prev_stats = [
            'total_violations' => 0, 'total_merits' => 0, 'alfa_count' => 0, 'total_literacy' => 0, 'total_habits' => 0
        ];

        foreach ($students as $student) {
            // 1. Poin Kedisiplinan Berdasarkan Periode
            $violationPoints = $student->disciplineRecords->where('disciplineType.type', 'Pelanggaran')->whereBetween('created_at', [$currentStart, $currentEnd])->sum('disciplineType.point_value');
            $meritPoints = $student->disciplineRecords->where('disciplineType.type', 'Kebaikan')->whereBetween('created_at', [$currentStart, $currentEnd])->sum('disciplineType.point_value');
            
            $prev_stats['total_violations'] += $student->disciplineRecords->where('disciplineType.type', 'Pelanggaran')->whereBetween('created_at', [$prevStart, $prevEnd])->sum('disciplineType.point_value');
            $prev_stats['total_merits'] += $student->disciplineRecords->where('disciplineType.type', 'Kebaikan')->whereBetween('created_at', [$prevStart, $prevEnd])->sum('disciplineType.point_value');

            $stats['total_violations'] += $violationPoints;
            $stats['total_merits'] += $meritPoints;

            // 2. Absensi Berdasarkan Periode
            $alfaThisPeriod = $student->attendances
                ->whereBetween('attendance_date', [$currentStart->format('Y-m-d'), $currentEnd->format('Y-m-d')])
                ->whereIn('status', ['Alfa', 'Alpa', 'Alpha', 'Tanpa Keterangan'])
                ->count();
            
            $prev_stats['alfa_count'] += $student->attendances
                ->whereBetween('attendance_date', [$prevStart->format('Y-m-d'), $prevEnd->format('Y-m-d')])
                ->whereIn('status', ['Alfa', 'Alpa', 'Alpha', 'Tanpa Keterangan'])
                ->count();
                
            $alfaThisSemester = $student->attendances
                ->where('attendance_date', '>=', $startOfSemester)
                ->whereIn('status', ['Alfa', 'Alpa', 'Alpha', 'Tanpa Keterangan'])
                ->count();
            
            $stats['alfa_count'] += $alfaThisPeriod;

            // 3. Literasi & Habit Berdasarkan Periode
            $literacyCount = $student->literacyJournals->count(); // Untuk nominasi all-time/semester
            $habitCount = $student->habits->count(); // Untuk nominasi all-time/semester
            
            $litCountPeriod = $student->literacyJournals->whereBetween('created_at', [$currentStart, $currentEnd])->count();
            $habCountPeriod = $student->habits->whereBetween('report_date', [$currentStart->format('Y-m-d'), $currentEnd->format('Y-m-d')])->count();
            
            $stats['total_literacy'] += $litCountPeriod;
            $stats['total_habits'] += $habCountPeriod;

            $prev_stats['total_literacy'] += $student->literacyJournals->whereBetween('created_at', [$prevStart, $prevEnd])->count();
            $prev_stats['total_habits'] += $student->habits->whereBetween('report_date', [$prevStart->format('Y-m-d'), $prevEnd->format('Y-m-d')])->count();

            // --- PENGELOMPOKAN DATA DASHBOARD ATAS ---
            if ($violationPoints >= 50 || $alfaThisPeriod >= 3) {
                $warningStudents->push((object)[
                    'id' => $student->id,
                    'name' => $student->name,
                    'photo' => $student->photo_path,
                    'violation_points' => $violationPoints,
                    'alfa_count' => $alfaThisPeriod,
                    'issue' => $alfaThisPeriod >= 3 ? 'Sering Alpa' : 'Pelanggaran Tinggi'
                ]);
            }

            if ($meritPoints > 0) {
                $topStudents->push((object)[
                    'name' => $student->name,
                    'photo' => $student->photo_path,
                    'merit_points' => $meritPoints,
                ]);
            }

            if ($litCountPeriod > 0) {
                $topLiteracy->push((object)[
                    'name' => $student->name,
                    'photo' => $student->photo_path,
                    'count' => $litCountPeriod,
                ]);
            }

            if ($habCountPeriod > 0) {
                $topHabits->push((object)[
                    'name' => $student->name,
                    'photo' => $student->photo_path,
                    'count' => $habCountPeriod,
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

        // HITUNG TREN (Persentase perubahan dari periode sebelumnya)
        $trends = [
            'merits' => $prev_stats['total_merits'] == 0 ? ($stats['total_merits'] > 0 ? 100 : 0) : round((($stats['total_merits'] - $prev_stats['total_merits']) / $prev_stats['total_merits']) * 100),
            'violations' => $prev_stats['total_violations'] == 0 ? ($stats['total_violations'] > 0 ? 100 : 0) : round((($stats['total_violations'] - $prev_stats['total_violations']) / $prev_stats['total_violations']) * 100),
            'literacy' => $prev_stats['total_literacy'] == 0 ? ($stats['total_literacy'] > 0 ? 100 : 0) : round((($stats['total_literacy'] - $prev_stats['total_literacy']) / $prev_stats['total_literacy']) * 100),
            'habits' => $prev_stats['total_habits'] == 0 ? ($stats['total_habits'] > 0 ? 100 : 0) : round((($stats['total_habits'] - $prev_stats['total_habits']) / $prev_stats['total_habits']) * 100),
            'alfa' => $prev_stats['alfa_count'] == 0 ? ($stats['alfa_count'] > 0 ? 100 : 0) : round((($stats['alfa_count'] - $prev_stats['alfa_count']) / $prev_stats['alfa_count']) * 100),
        ];

        return view('homeroom.dashboard', compact(
            'class', 'stats', 'warningStudents', 'topStudents', 
            'topLiteracy', 'topHabits', 'awardNominees', 
            'isAdminOrKepsek', 'allClasses', 'trends'
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