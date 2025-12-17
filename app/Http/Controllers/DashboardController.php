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
        
        $startDate = Carbon::parse($dateParam)->startOfDay();
        $endDate = Carbon::parse($dateParam)->endOfDay();

        if ($period === 'week') {
            $startDate = Carbon::parse($dateParam)->startOfWeek();
            $endDate = Carbon::parse($dateParam)->endOfWeek();
        } elseif ($period === 'month') {
            $startDate = Carbon::parse($dateParam)->startOfMonth();
            $endDate = Carbon::parse($dateParam)->endOfMonth();
        }

        // 2. DATA STATISTIK UTAMA (KPIS)
        $totalStudents = Student::count();
        $attendances = AttendanceSiswa::whereBetween('attendance_date', [$startDate, $endDate])->get();
        
        $presentCount = $attendances->whereIn('status', ['Hadir', 'Terlambat', 'Tepat Waktu'])->unique('student_id')->count();
        $lateCount = $attendances->whereIn('status', ['Terlambat'])->unique('student_id')->count();
        $presentOnTimeCount = max(0, $presentCount - $lateCount);

        $sickCount = $attendances->whereIn('status', ['Sakit'])->unique('student_id')->count();
        $permitCount = $attendances->whereIn('status', ['Izin'])->unique('student_id')->count();
        $alphaCount = $attendances->whereIn('status', ['Alpa', 'Alpha'])->unique('student_id')->count();
        $sickPermitCount = $sickCount + $permitCount;
        
        $earlyLeaveCount = $attendances->filter(function ($att) {
            return str_contains(strtolower($att->notes ?? ''), 'pulang awal');
        })->count();

        $absentCount = 0;
        if ($period === 'today') {
             $alreadyRecorded = $attendances->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
                                          ->unique('student_id')
                                          ->count();
             $absentCount = max(0, $totalStudents - $alreadyRecorded);
        }

        // 3. DATA KARTU (Cards)
        $cards = [
            [
                'title' => 'Total Siswa',
                'value' => $totalStudents,
                'border' => 'border-indigo-500',
                'text_color' => 'text-gray-800',
                'icon_color' => 'text-indigo-500',
                'icon' => 'ph-student',
                'filter_status' => 'all'
            ],
            [
                'title' => 'Total Hadir',
                'value' => $presentCount,
                'border' => 'border-emerald-500',
                'text_color' => 'text-gray-800',
                'icon_color' => 'text-emerald-500',
                'icon' => 'ph-check-circle',
                'filter_status' => 'present'
            ],
            [
                'title' => 'Belum Hadir',
                'value' => $absentCount,
                'border' => 'border-slate-500',
                'text_color' => 'text-gray-800',
                'icon_color' => 'text-slate-500',
                'icon' => 'ph-minus-circle',
                'filter_status' => 'absent'
            ],
            [
                'title' => 'Terlambat',
                'value' => $lateCount,
                'border' => 'border-orange-500',
                'text_color' => 'text-gray-800',
                'icon_color' => 'text-orange-500',
                'icon' => 'ph-clock-warning',
                'filter_status' => 'late'
            ],
            [
                'title' => 'Pulang Awal',
                'value' => $earlyLeaveCount,
                'border' => 'border-yellow-500',
                'text_color' => 'text-gray-800',
                'icon_color' => 'text-yellow-500',
                'icon' => 'ph-person-simple-run',
                'filter_status' => 'early_leave'
            ],
            [
                'title' => 'Sakit / Izin',
                'value' => $sickPermitCount,
                'border' => 'border-red-500',
                'text_color' => 'text-gray-800',
                'icon_color' => 'text-red-500',
                'icon' => 'ph-first-aid',
                'filter_status' => 'excused'
            ]
        ];

        // 4. TOP VIOLATORS (Sering Terlambat)
        $topLateStudents = AttendanceSiswa::with(['student.schoolClass']) 
            ->where('status', 'Terlambat')
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->select('student_id', DB::raw('count(*) as total_late'))
            ->groupBy('student_id')
            ->orderByDesc('total_late')
            ->take(5)
            ->get();

        // 5. [BARU] TOP PUNCTUAL (Siswa Terrajin)
        // Kriteria: Status 'Hadir'/'Tepat Waktu' terbanyak
        $topPunctualStudents = AttendanceSiswa::with(['student.schoolClass'])
            ->whereIn('status', ['Hadir', 'Tepat Waktu'])
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->select('student_id', DB::raw('count(*) as total_present'))
            ->groupBy('student_id')
            ->orderByDesc('total_present')
            // Jika jumlah hadir sama, urutkan berdasarkan waktu masuk rata-rata (opsional, perlu query lebih kompleks)
            ->take(5)
            ->get();

        // 6. LOG AKTIVITAS TERBARU (Live Feed)
        $recentActivities = AttendanceSiswa::with(['student.schoolClass'])
            ->whereDate('attendance_date', Carbon::parse($dateParam)->toDateString())
            ->latest('created_at')
            ->take(6)
            ->get();

        // 7. RANKING KEHADIRAN PER KELAS
        $classRanks = DB::table('attendances_siswa') 
            ->join('students', 'attendances_siswa.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->whereBetween('attendances_siswa.attendance_date', [$startDate, $endDate])
            ->whereIn('attendances_siswa.status', ['Hadir', 'Tepat Waktu', 'Terlambat'])
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

        $graphStart = ($period === 'today') ? Carbon::today()->subDays(6) : $startDate;
        $graphEnd   = ($period === 'today') ? Carbon::today() : $endDate;

        $periodLoop = $graphStart->copy();
        while($periodLoop <= $graphEnd) {
            $dateStr = $periodLoop->toDateString();
            $chartLabels[] = $periodLoop->format('d M'); 

            $dailyAtt = AttendanceSiswa::whereDate('attendance_date', $dateStr)->get();

            $weeklyPresentData[] = $dailyAtt->whereIn('status', ['Hadir', 'Tepat Waktu'])->unique('student_id')->count();
            $weeklyLateData[] = $dailyAtt->whereIn('status', ['Terlambat'])->unique('student_id')->count();
            $weeklyAbsentData[] = $dailyAtt->whereIn('status', ['Sakit', 'Izin', 'Alpa', 'Alpha'])->unique('student_id')->count();

            $periodLoop->addDay();
        }

        return view('dashboard', [
            'totalStudents' => $totalStudents,
            'presentOnTimeCount' => $presentOnTimeCount,
            'lateCount' => $lateCount,
            'absentCount' => $absentCount, 
            'sickPermitCount' => $sickPermitCount,
            'alphaCount' => $alphaCount,
            'cards' => $cards,
            'topLateStudents' => $topLateStudents,
            'topPunctualStudents' => $topPunctualStudents, // Variable Baru
            'recentActivities' => $recentActivities,
            'classRanks' => $classRanks,
            'chartLabels' => $chartLabels,
            'weeklyPresentData' => $weeklyPresentData, 
            'weeklyLateData' => $weeklyLateData,       
            'weeklyAbsentData' => $weeklyAbsentData,   
        ]);
    }
}