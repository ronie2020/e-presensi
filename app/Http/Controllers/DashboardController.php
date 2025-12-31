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

        // 2. DATA STATISTIK UTAMA (KPIS)
        // Ambil SEMUA data dalam range tanggal
        $allAttendances = AttendanceSiswa::whereBetween('attendance_date', [$startDate, $endDate])->get();

        // [LOGIKA FILTER] Hanya Absensi Sekolah (Harian/Masuk/Pulang)
        $schoolAttendances = $allAttendances->filter(function ($att) {
            return in_array(strtolower($att->type), ['harian', 'masuk', 'pulang']);
        });

        $totalStudents = Student::count();
        
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

        // Hitung "Belum Hadir" 
        $notYetScannedCount = 0;
        // Kita hitung untuk semua periode agar kartu tidak 0 saat mode Week/Month (opsional, tapi logika asli membatasinya)
        // Jika ingin mengikuti logika asli user:
        if ($period === 'today') {
             $alreadyRecorded = $schoolAttendances->unique('student_id')->count();
             $notYetScannedCount = max(0, $totalStudents - $alreadyRecorded);
        }

        // 3. DATA KARTU (Cards)
        $cards = [
            [
                'title' => 'Total Siswa',
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

        // 4. TOP VIOLATORS (Sering Terlambat)
        $topLateStudents = AttendanceSiswa::with(['student.schoolClass']) 
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang', 'harian', 'masuk', 'pulang']) // Handle case sensitive DB
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
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang', 'harian', 'masuk', 'pulang'])
            ->whereIn('status', ['Hadir', 'Tepat Waktu', 'hadir', 'tepat waktu'])
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->select('student_id', DB::raw('count(*) as total_present'))
            ->groupBy('student_id')
            ->orderByDesc('total_present')
            ->take(5)
            ->get();

        // 6. LOG AKTIVITAS TERBARU (Live Feed)
        // Menggunakan tanggal yang dipilih user ($dateParam), bukan Carbon::today()
        $recentActivities = AttendanceSiswa::with(['student.schoolClass'])
            ->whereDate('attendance_date', $dateParam)
            ->latest('created_at')
            ->take(6)
            ->get();

        // 7. RANKING KEHADIRAN PER KELAS
        $classRanks = DB::table('attendances_siswa') 
            ->join('students', 'attendances_siswa.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->whereBetween('attendances_siswa.attendance_date', [$startDate, $endDate])
            ->whereIn('attendances_siswa.type', ['Harian', 'Masuk', 'Pulang', 'harian', 'masuk', 'pulang'])
            ->whereIn('attendances_siswa.status', ['Hadir', 'Tepat Waktu', 'Terlambat', 'hadir', 'tepat waktu', 'terlambat'])
            ->whereNull('students.deleted_at')
            ->select('classes.name as class_name', DB::raw('count(DISTINCT attendances_siswa.student_id) as present_count'))
            ->groupBy('classes.name')
            ->orderByDesc('present_count')
            ->take(5)
            ->get();

        // 8. DATA GRAFIK (FIX LOGIC)
        $weeklyPresentData = [];
        $weeklyLateData = [];
        $weeklyAbsentData = [];
        $chartLabels = [];

        // Jika 'today', tampilkan trend 7 hari terakhir BERAKHIR di tanggal yang dipilih user
        // Jika 'week'/'month', tampilkan sesuai range periode tersebut
        $graphStart = ($period === 'today') ? $selectedDate->copy()->subDays(6) : $startDate;
        $graphEnd   = ($period === 'today') ? $selectedDate : $endDate;

        // Ambil data untuk grafik
        $graphAttendances = AttendanceSiswa::whereBetween('attendance_date', [$graphStart, $graphEnd])->get();

        $graphAttendancesSchool = $graphAttendances->filter(function ($att) {
            return in_array(strtolower($att->type), ['harian', 'masuk', 'pulang']);
        });

        $periodLoop = $graphStart->copy();
        
        // Loop hari per hari untuk mengisi grafik
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
            'period' => $period, // Kirim variabel ini ke view untuk mencegah error Undefined variable
            'date' => $dateParam, // Kirim tanggal yang dipilih
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