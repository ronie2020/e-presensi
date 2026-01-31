<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AttendanceSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. TENTUKAN PERIODE & TANGGAL
        $period = $request->query('period', 'today'); 
        $dateParam = $request->query('date', Carbon::today()->toDateString());
        
        // Parsing tanggal yang dipilih user
        $selectedDate = Carbon::parse($dateParam);

        $startDate = $selectedDate->copy()->startOfDay();
        $endDate = $selectedDate->copy()->endOfDay();

        if ($period === 'week') {
            $startDate = $selectedDate->copy()->startOfWeek();
            $endDate = $selectedDate->copy()->endOfWeek();
        } elseif ($period === 'month') {
            $startDate = $selectedDate->copy()->startOfMonth();
            $endDate = $selectedDate->copy()->endOfMonth();
        }

        // =====================================================================
        // FILTER ALUMNI (CORE FIX)
        // =====================================================================
        $filterActiveStudent = function($query) {
            $query->where('status', '!=', 'graduated'); // Pastikan bukan alumni
        };

        // 2. DATA STATISTIK UTAMA (KPIS)
        
        $totalStudents = Student::where($filterActiveStudent)->count();

        // Ambil data absensi PERIODE INI
        $allAttendances = AttendanceSiswa::with('student')
            ->whereHas('student', $filterActiveStudent) 
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->get();

        $schoolAttendances = $allAttendances->filter(function ($att) {
            return in_array(strtolower($att->type), ['harian', 'masuk', 'pulang']);
        });
        
        $presentCount = $schoolAttendances->filter(fn($att) => in_array(strtolower($att->status), ['hadir', 'terlambat', 'tepat waktu']))->unique('student_id')->count();
        $lateCount = $schoolAttendances->filter(fn($att) => strtolower($att->status) === 'terlambat')->unique('student_id')->count();
        $presentOnTimeCount = max(0, $presentCount - $lateCount);

        $sickCount = $schoolAttendances->filter(fn($att) => strtolower($att->status) === 'sakit')->unique('student_id')->count();
        $permitCount = $schoolAttendances->filter(fn($att) => strtolower($att->status) === 'izin')->unique('student_id')->count();
        $alphaCount = $schoolAttendances->filter(fn($att) => in_array(strtolower($att->status), ['alpa', 'alpha']))->unique('student_id')->count();
        $sickPermitCount = $sickCount + $permitCount;
        
        $earlyLeaveCount = $schoolAttendances->filter(fn($att) => str_contains(strtolower($att->notes ?? ''), 'pulang awal'))->count();

        $notYetScannedCount = 0;
        if ($period === 'today') {
             $alreadyRecorded = $schoolAttendances->unique('student_id')->count();
             $notYetScannedCount = max(0, $totalStudents - $alreadyRecorded);
        }

        // =====================================================================
        // [BARU] LOGIKA KOMPARASI TREN (Hanya jika periode = today)
        // =====================================================================
        $trendHadir = 0;
        $trendTerlambat = 0;
        $isTrendUpHadir = true;
        
        if ($period === 'today') {
            // Ambil Data Kemarin (Hari Sekolah Sebelumnya)
            $yesterday = $selectedDate->copy()->subDay();
            if ($yesterday->isSunday()) $yesterday->subDay(2); // Skip Minggu
            
            $yesterdayAttendances = AttendanceSiswa::whereHas('student', $filterActiveStudent)
                ->whereDate('attendance_date', $yesterday)
                ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
                ->get();
            
            $yesterdayPresent = $yesterdayAttendances->filter(fn($att) => in_array(strtolower($att->status), ['hadir', 'terlambat', 'tepat waktu']))->unique('student_id')->count();
            
            // Hitung Selisih
            $diffHadir = $presentCount - $yesterdayPresent;
            $trendHadir = $diffHadir;
            $isTrendUpHadir = $diffHadir >= 0;
        }

        // 3. DATA KARTU (Cards)
        $cards = [
            [
                'title' => 'Total Siswa Aktif', 
                'value' => $totalStudents,
                'icon' => 'ph-student',
                'filter_status' => 'all'
            ],
            [
                'title' => 'Total Hadir',
                'value' => $presentCount,
                'icon' => 'ph-check-circle',
                'percentage' => $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100, 1) : 0,
                'filter_status' => 'present',
                // Data Tren ditambahkan ke kartu ini
                'trend' => $period === 'today' ? $trendHadir : null, 
                'trend_label' => 'vs Kemarin'
            ],
            [
                'title' => 'Belum Hadir',
                'value' => $notYetScannedCount, 
                'icon' => 'ph-minus-circle',
                'filter_status' => 'absent'
            ],
            [
                'title' => 'Terlambat',
                'value' => $lateCount, 
                'icon' => 'ph-clock', 
                'filter_status' => 'late'
            ],
            [
                'title' => 'Pulang Awal',
                'value' => $earlyLeaveCount,
                'icon' => 'ph-person-simple-run',
                'filter_status' => 'early_leave'
            ],
            [
                'title' => 'Sakit / Izin',
                'value' => $sickPermitCount,
                'icon' => 'ph-first-aid',
                'filter_status' => 'excused'
            ]
        ];

        // 4. TOP VIOLATORS (Sering Terlambat)
        $topLateStudents = AttendanceSiswa::with(['student.schoolClass']) 
            ->whereHas('student', $filterActiveStudent) 
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang', 'harian', 'masuk', 'pulang'])
            ->where(function($query) {
                $query->where('status', 'Terlambat')
                      ->orWhere('status', 'terlambat');
            })
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->select('student_id', DB::raw('count(*) as total_late'))
            ->groupBy('student_id')
            ->orderByDesc('total_late')
            ->take(5)
            ->get();

        // 5. TOP PUNCTUAL (Siswa Terrajin)
        $topPunctualStudents = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', $filterActiveStudent) 
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang', 'harian', 'masuk', 'pulang'])
            ->whereIn('status', ['Hadir', 'Tepat Waktu', 'hadir', 'tepat waktu'])
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->select('student_id', DB::raw('count(*) as total_present'))
            ->groupBy('student_id')
            ->orderByDesc('total_present')
            ->take(5)
            ->get();

        // 6. LOG AKTIVITAS TERBARU
        $recentActivities = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', $filterActiveStudent)
            ->whereDate('attendance_date', $dateParam)
            ->latest('created_at')
            ->take(6)
            ->get();

        // 7. RANKING KEHADIRAN PER KELAS (TERBAIK)
        $classRanks = DB::table('attendances_siswa') 
            ->join('students', 'attendances_siswa.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->where('students.status', '!=', 'graduated') 
            ->whereBetween('attendances_siswa.attendance_date', [$startDate, $endDate])
            ->whereIn('attendances_siswa.type', ['Harian', 'Masuk', 'Pulang'])
            ->whereIn('attendances_siswa.status', ['Hadir', 'Tepat Waktu', 'Terlambat'])
            ->whereNull('students.deleted_at')
            ->select('classes.name as class_name', DB::raw('count(DISTINCT attendances_siswa.student_id) as present_count'))
            ->groupBy('classes.name')
            ->orderByDesc('present_count')
            ->take(5)
            ->get();
            
        // [BARU] 7b. RANKING KEHADIRAN PER KELAS (TERENDAH / PERLU PERHATIAN)
        // Kita cari kelas dengan jumlah Absen/Alfa terbanyak
        $lowestClassRanks = DB::table('attendances_siswa') 
            ->join('students', 'attendances_siswa.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->where('students.status', '!=', 'graduated') 
            ->whereBetween('attendances_siswa.attendance_date', [$startDate, $endDate])
            ->whereIn('attendances_siswa.type', ['Harian', 'Masuk', 'Pulang'])
            ->whereIn('attendances_siswa.status', ['Alfa', 'Alpa', 'Alpha', 'Sakit', 'Izin']) // Hitung ketidakhadiran
            ->whereNull('students.deleted_at')
            ->select('classes.name as class_name', DB::raw('count(DISTINCT attendances_siswa.student_id) as absent_count'))
            ->groupBy('classes.name')
            ->orderByDesc('absent_count') // Urutkan dari yang paling banyak tidak masuk
            ->take(5)
            ->get();

        // 8. DATA GRAFIK
        $weeklyPresentData = [];
        $weeklyLateData = [];
        $weeklyAbsentData = [];
        $chartLabels = [];

        $graphStart = ($period === 'today') ? $selectedDate->copy()->subDays(6) : $startDate;
        $graphEnd   = ($period === 'today') ? $selectedDate : $endDate;

        $graphAttendances = AttendanceSiswa::with('student')
            ->whereHas('student', $filterActiveStudent)
            ->whereBetween('attendance_date', [$graphStart, $graphEnd])
            ->get();

        $graphAttendancesSchool = $graphAttendances->filter(function ($att) {
            return in_array(strtolower($att->type), ['harian', 'masuk', 'pulang']);
        });

        $periodLoop = $graphStart->copy();
        
        while($periodLoop <= $graphEnd) {
            $dateStr = $periodLoop->format('Y-m-d'); 
            $chartLabels[] = $periodLoop->format('d M'); 

            $dailyAtt = $graphAttendancesSchool->filter(function ($att) use ($dateStr) {
                return Carbon::parse($att->attendance_date)->format('Y-m-d') === $dateStr;
            });

            $weeklyPresentData[] = $dailyAtt->filter(fn($att) => in_array(strtolower($att->status), ['hadir', 'tepat waktu']))->unique('student_id')->count();
            $weeklyLateData[] = $dailyAtt->filter(fn($att) => strtolower($att->status) === 'terlambat')->unique('student_id')->count();
            $weeklyAbsentData[] = $dailyAtt->filter(fn($att) => in_array(strtolower($att->status), ['sakit', 'izin', 'alpa', 'alpha']))->unique('student_id')->count();

            $periodLoop->addDay();
        }

        return view('dashboard', [
            'period' => $period, 
            'date' => $dateParam, 
            'totalStudents' => $totalStudents,
            'presentOnTimeCount' => $presentOnTimeCount,
            'lateCount' => $lateCount,
            'absentCount' => $alphaCount, 
            'sickPermitCount' => $sickPermitCount,
            'alphaCount' => $alphaCount,
            'cards' => $cards,
            'topLateStudents' => $topLateStudents,
            'topPunctualStudents' => $topPunctualStudents,
            'recentActivities' => $recentActivities,
            'classRanks' => $classRanks,
            'lowestClassRanks' => $lowestClassRanks, // Data baru dikirim ke view
            'chartLabels' => $chartLabels,
            'weeklyPresentData' => $weeklyPresentData, 
            'weeklyLateData' => $weeklyLateData,       
            'weeklyAbsentData' => $weeklyAbsentData,   
        ]);
    }
}