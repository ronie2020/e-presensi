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
        // Kita buat kondisi reuseable agar kode lebih rapi
        // =====================================================================
        $filterActiveStudent = function($query) {
            $query->where('status', '!=', 'graduated'); // Pastikan bukan alumni
        };

        // 2. DATA STATISTIK UTAMA (KPIS)
        
        // [FIX 1] Hitung Total Siswa (Hanya yang Aktif)
        $totalStudents = Student::where($filterActiveStudent)->count();

        // [FIX 2] Ambil data absensi hanya milik siswa AKTIF
        $allAttendances = AttendanceSiswa::with('student')
            ->whereHas('student', $filterActiveStudent) // Filter relasi
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->get();

        // [LOGIKA FILTER] Hanya Absensi Sekolah (Harian/Masuk/Pulang)
        $schoolAttendances = $allAttendances->filter(function ($att) {
            return in_array(strtolower($att->type), ['harian', 'masuk', 'pulang']);
        });
        
        // [LOGIKA HADIR & TERLAMBAT]
        $presentCount = $schoolAttendances->filter(function ($att) {
            return in_array(strtolower($att->status), ['hadir', 'terlambat', 'tepat waktu']);
        })->unique('student_id')->count();

        $lateCount = $schoolAttendances->filter(function ($att) {
            return strtolower($att->status) === 'terlambat';
        })->unique('student_id')->count();
        
        $presentOnTimeCount = max(0, $presentCount - $lateCount);

        // Hitung Sakit/Izin/Alpha
        $sickCount = $schoolAttendances->filter(fn($att) => strtolower($att->status) === 'sakit')->unique('student_id')->count();
        $permitCount = $schoolAttendances->filter(fn($att) => strtolower($att->status) === 'izin')->unique('student_id')->count();
        $alphaCount = $schoolAttendances->filter(fn($att) => in_array(strtolower($att->status), ['alpa', 'alpha']))->unique('student_id')->count();
        
        $sickPermitCount = $sickCount + $permitCount;
        
        $earlyLeaveCount = $schoolAttendances->filter(function ($att) {
            return str_contains(strtolower($att->notes ?? ''), 'pulang awal');
        })->count();

        // Hitung "Belum Hadir" (Sisa siswa aktif yang belum scan)
        $notYetScannedCount = 0;
        if ($period === 'today') {
             // Hitung siswa yang sudah ada record absensinya hari ini
             $alreadyRecorded = $schoolAttendances->unique('student_id')->count();
             // Sisanya adalah yang belum hadir
             $notYetScannedCount = max(0, $totalStudents - $alreadyRecorded);
        }

        // 3. DATA KARTU (Cards)
        $cards = [
            [
                'title' => 'Total Siswa Aktif', // Ubah label agar jelas
                'value' => $totalStudents,
                'icon' => 'ph-student',
                'filter_status' => 'all'
            ],
            [
                'title' => 'Total Hadir',
                'value' => $presentCount,
                'icon' => 'ph-check-circle',
                'percentage' => $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100, 1) : 0,
                'filter_status' => 'present'
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

        // 4. TOP VIOLATORS (Sering Terlambat) - [FIX 3] Tambah whereHas
        $topLateStudents = AttendanceSiswa::with(['student.schoolClass']) 
            ->whereHas('student', $filterActiveStudent) // Hanya siswa aktif
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

        // 5. TOP PUNCTUAL (Siswa Terrajin) - [FIX 4] Tambah whereHas
        $topPunctualStudents = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', $filterActiveStudent) // Hanya siswa aktif
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang', 'harian', 'masuk', 'pulang'])
            ->whereIn('status', ['Hadir', 'Tepat Waktu', 'hadir', 'tepat waktu'])
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->select('student_id', DB::raw('count(*) as total_present'))
            ->groupBy('student_id')
            ->orderByDesc('total_present')
            ->take(5)
            ->get();

        // 6. LOG AKTIVITAS TERBARU (Live Feed) - [FIX 5] Tambah whereHas
        $recentActivities = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', $filterActiveStudent) // Hanya siswa aktif
            ->whereDate('attendance_date', $dateParam)
            ->latest('created_at')
            ->take(6)
            ->get();

        // 7. RANKING KEHADIRAN PER KELAS - [FIX 6] Tambah where status != graduated
        $classRanks = DB::table('attendances_siswa') 
            ->join('students', 'attendances_siswa.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->where('students.status', '!=', 'graduated') // Filter Alumni di Query Builder
            ->whereBetween('attendances_siswa.attendance_date', [$startDate, $endDate])
            ->whereIn('attendances_siswa.type', ['Harian', 'Masuk', 'Pulang', 'harian', 'masuk', 'pulang'])
            ->whereIn('attendances_siswa.status', ['Hadir', 'Tepat Waktu', 'Terlambat', 'hadir', 'tepat waktu', 'terlambat'])
            ->whereNull('students.deleted_at')
            ->select('classes.name as class_name', DB::raw('count(DISTINCT attendances_siswa.student_id) as present_count'))
            ->groupBy('classes.name')
            ->orderByDesc('present_count')
            ->take(5)
            ->get();

        // 8. DATA GRAFIK
        $weeklyPresentData = [];
        $weeklyLateData = [];
        $weeklyAbsentData = [];
        $chartLabels = [];

        // Jika 'today', tampilkan trend 7 hari terakhir
        $graphStart = ($period === 'today') ? $selectedDate->copy()->subDays(6) : $startDate;
        $graphEnd   = ($period === 'today') ? $selectedDate : $endDate;

        // Ambil data untuk grafik - [FIX 7] Tambah whereHas
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

            // Filter data per hari
            $dailyAtt = $graphAttendancesSchool->filter(function ($att) use ($dateStr) {
                return Carbon::parse($att->attendance_date)->format('Y-m-d') === $dateStr;
            });

            // 1. Hadir Tepat Waktu
            $weeklyPresentData[] = $dailyAtt->filter(fn($att) => in_array(strtolower($att->status), ['hadir', 'tepat waktu']))->unique('student_id')->count();
            
            // 2. Khusus Terlambat
            $weeklyLateData[] = $dailyAtt->filter(fn($att) => strtolower($att->status) === 'terlambat')->unique('student_id')->count();
            
            // 3. Absen (Sakit/Izin/Alpha)
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
            'chartLabels' => $chartLabels,
            'weeklyPresentData' => $weeklyPresentData, 
            'weeklyLateData' => $weeklyLateData,       
            'weeklyAbsentData' => $weeklyAbsentData,   
        ]);
    }
}