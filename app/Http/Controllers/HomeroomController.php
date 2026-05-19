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

        // TAMBAHAN: Variabel menampung jumlah keterlambatan
        $stats = [
            'total_students' => $totalStudents,
            'total_violations' => 0,
            'total_merits' => 0,
            'alfa_count' => 0,
            'late_count' => 0, 
            'total_literacy' => 0, 
            'total_habits' => 0,   
        ];

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

        $warningStudents = collect();
        $topStudents = collect();
        $topLiteracy = collect();
        $topHabits = collect();
        $awardNominees = collect(); 

        $mappedStudents = []; // Penampung Data JS Modal

        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfSemester = Carbon::now()->subMonths(6); 

        // Untuk menghitung Tren
        $prev_stats = [
            'total_violations' => 0, 'total_merits' => 0, 'alfa_count' => 0, 'late_count' => 0, 'total_literacy' => 0, 'total_habits' => 0
        ];

        // String Konversi Tanggal untuk Filter yang Akurat
        $currStartStr = $currentStart->format('Y-m-d');
        $currEndStr = $currentEnd->format('Y-m-d');
        $prevStartStr = $prevStart->format('Y-m-d');
        $prevEndStr = $prevEnd->format('Y-m-d');

        foreach ($students as $student) {
            // 1. Poin Kedisiplinan Berdasarkan Periode
            $violationPoints = $student->disciplineRecords->where('disciplineType.type', 'Pelanggaran')->whereBetween('created_at', [$currentStart, $currentEnd])->sum('disciplineType.point_value');
            $meritPoints = $student->disciplineRecords->where('disciplineType.type', 'Kebaikan')->whereBetween('created_at', [$currentStart, $currentEnd])->sum('disciplineType.point_value');
            
            $prev_stats['total_violations'] += $student->disciplineRecords->where('disciplineType.type', 'Pelanggaran')->whereBetween('created_at', [$prevStart, $prevEnd])->sum('disciplineType.point_value');
            $prev_stats['total_merits'] += $student->disciplineRecords->where('disciplineType.type', 'Kebaikan')->whereBetween('created_at', [$prevStart, $prevEnd])->sum('disciplineType.point_value');

            $stats['total_violations'] += $violationPoints;
            $stats['total_merits'] += $meritPoints;

            // 2. Absensi Berdasarkan Periode (DIPERKETAT agar menangkap data Terlambat)
            $attendancesThisPeriod = $student->attendances->filter(function($att) use ($currStartStr, $currEndStr) {
                $d = substr($att->attendance_date, 0, 10);
                return $d >= $currStartStr && $d <= $currEndStr;
            });

            $attendancesPrevPeriod = $student->attendances->filter(function($att) use ($prevStartStr, $prevEndStr) {
                $d = substr($att->attendance_date, 0, 10);
                return $d >= $prevStartStr && $d <= $prevEndStr;
            });

            $alfaThisPeriod = $attendancesThisPeriod->whereIn('status', ['Alfa', 'Alpa', 'Alpha', 'Tanpa Keterangan'])->count();
            
            // LOGIKA MENANGKAP STATUS TERLAMBAT
            $lateThisPeriod = $attendancesThisPeriod->whereIn('type', ['Harian', 'Masuk'])->where('status', 'Terlambat')->count();
            
            $prev_stats['alfa_count'] += $attendancesPrevPeriod->whereIn('status', ['Alfa', 'Alpa', 'Alpha', 'Tanpa Keterangan'])->count();
            $prev_stats['late_count'] += $attendancesPrevPeriod->whereIn('type', ['Harian', 'Masuk'])->where('status', 'Terlambat')->count();
                
            $alfaThisSemester = $student->attendances
                ->filter(function($att) use ($startOfSemester) { return substr($att->attendance_date, 0, 10) >= $startOfSemester->format('Y-m-d'); })
                ->whereIn('status', ['Alfa', 'Alpa', 'Alpha', 'Tanpa Keterangan'])
                ->count();
            
            $stats['alfa_count'] += $alfaThisPeriod;
            $stats['late_count'] += $lateThisPeriod;

            // 3. Literasi & Habit Berdasarkan Periode
            $literacyCount = $student->literacyJournals->count(); // Untuk nominasi all-time/semester
            $habitCount = $student->habits->count(); // Untuk nominasi all-time/semester
            
            $litCountPeriod = $student->literacyJournals->whereBetween('created_at', [$currentStart, $currentEnd])->count();
            $habCountPeriod = $student->habits->filter(function($h) use ($currStartStr, $currEndStr) {
                return $h->report_date >= $currStartStr && $h->report_date <= $currEndStr;
            })->count();
            
            $stats['total_literacy'] += $litCountPeriod;
            $stats['total_habits'] += $habCountPeriod;

            $prev_stats['total_literacy'] += $student->literacyJournals->whereBetween('created_at', [$prevStart, $prevEnd])->count();
            $prev_stats['total_habits'] += $student->habits->filter(function($h) use ($prevStartStr, $prevEndStr) {
                return $h->report_date >= $prevStartStr && $h->report_date <= $prevEndStr;
            })->count();

            // --- INJEKSI KE DATA JS BROWSER UNTUK MODAL ---
            $mappedStudents[] = [
                'id' => $student->id,
                'name' => $student->name,
                'nisn' => $student->nisn ?? $student->student_id ?? '-',
                'photo' => $student->photo_path ? asset('storage/' . $student->photo_path) : null,
                'alfa_count' => $alfaThisPeriod,
                'late_count' => $lateThisPeriod, // DISISIPKAN DI SINI
                'violation_points' => $violationPoints,
                'merit_points' => $meritPoints,
                'literacy_count' => $litCountPeriod,
                'habits_count' => $habCountPeriod,
                'parent_phone' => $student->parent_phone ?? $student->phone ?? null,
            ];

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
            'late' => $prev_stats['late_count'] == 0 ? ($stats['late_count'] > 0 ? 100 : 0) : round((($stats['late_count'] - $prev_stats['late_count']) / $prev_stats['late_count']) * 100),
        ];

        return view('homeroom.dashboard', compact(
            'class', 'stats', 'warningStudents', 'topStudents', 
            'topLiteracy', 'topHabits', 'awardNominees', 
            'isAdminOrKepsek', 'allClasses', 'trends', 'mappedStudents'
        ));
    }

    /**
     * FUNGSI: Mencetak Laporan Evaluasi Kelas
     */
    public function print(Request $request)
    {
        $user = Auth::user();
        
        if ($request->has('class_id')) {
            $class = SchoolClass::findOrFail($request->class_id);
        } else {
            $class = SchoolClass::where('homeroom_teacher_id', $user->id)->first();
            if (!$class && $user->hasRole(['Admin', 'Kepala Sekolah'])) {
                $class = SchoolClass::first();
            }
        }

        if (!$class) {
            return back()->with('error', 'Data kelas tidak ditemukan.');
        }

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
            'late_count' => 0, 
        ];

        $warningStudents = collect();
        $awardNominees = collect(); 

        $startOfSemester = Carbon::now()->subMonths(6); 
        $semesterStr = $startOfSemester->format('Y-m-d');

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

        foreach ($students as $student) {
            $violationPoints = $student->disciplineRecords->where('disciplineType.type', 'Pelanggaran')->sum('disciplineType.point_value');
            $meritPoints = $student->disciplineRecords->where('disciplineType.type', 'Kebaikan')->sum('disciplineType.point_value');
            
            $stats['total_violations'] += $violationPoints;
            $stats['total_merits'] += $meritPoints;

            $alfaThisSemester = $student->attendances
                ->filter(function($att) use ($semesterStr) { return substr($att->attendance_date, 0, 10) >= $semesterStr; })
                ->whereIn('status', ['Alfa', 'Alpa', 'Alpha', 'Tanpa Keterangan'])
                ->count();
                
            $lateThisSemester = $student->attendances
                ->filter(function($att) use ($semesterStr) { return substr($att->attendance_date, 0, 10) >= $semesterStr; })
                ->whereIn('type', ['Harian', 'Masuk'])
                ->where('status', 'Terlambat')
                ->count();
            
            $stats['alfa_count'] += $alfaThisSemester;
            $stats['late_count'] += $lateThisSemester;

            $literacyCount = $student->literacyJournals->count();
            $habitCount = $student->habits->count();

            if ($violationPoints >= 50 || $alfaThisSemester >= 5) {
                $warningStudents->push((object)[
                    'name' => $student->name,
                    'nisn' => $student->student_id,
                    'violation_points' => $violationPoints,
                    'alfa_count' => $alfaThisSemester,
                    'issue' => $alfaThisSemester >= 5 ? 'Kehadiran Sangat Kurang' : 'Sering Melanggar Aturan'
                ]);
            }

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

        $warningStudents = $warningStudents->sortByDesc('violation_points')->values();
        $awardNominees = $awardNominees->sortByDesc('total_score')->take(10)->values();

        return view('homeroom.print', compact(
            'class', 'stats', 'warningStudents', 'awardNominees', 'teacherName', 'teacherNip'
        ));
    }
}