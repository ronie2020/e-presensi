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
        // Ambil SEMUA data dalam range tanggal
        $allAttendances = AttendanceSiswa::whereBetween('attendance_date', [$startDate, $endDate])->get();

        // [LOGIKA FILTER] Hanya Absensi Sekolah (Harian/Masuk/Pulang)
        // Menggunakan strtolower agar tidak sensitif huruf besar/kecil
        $schoolAttendances = $allAttendances->filter(function ($att) {
            return in_array(strtolower($att->type), ['harian', 'masuk', 'pulang']);
        });

        $totalStudents = Student::count();
        
        // [LOGIKA HADIR & TERLAMBAT]
        // 1. Hitung Total Hadir (Gabungan Tepat Waktu + Terlambat)
        $presentCount = $schoolAttendances->filter(function ($att) {
            return in_array(strtolower($att->status), ['hadir', 'terlambat', 'tepat waktu']);
        })->unique('student_id')->count();

        // 2. Hitung Khusus Terlambat
        $lateCount = $schoolAttendances->filter(function ($att) {
            return strtolower($att->status) === 'terlambat';
        })->unique('student_id')->count();
        
        // 3. Hitung Hadir Tepat Waktu (Total Hadir - Terlambat)
        $presentOnTimeCount = max(0, $presentCount - $lateCount);

        // Hitung Sakit/Izin/Alpha
        $sickCount = $schoolAttendances->filter(fn($att) => strtolower($att->status) === 'sakit')->unique('student_id')->count();
        $permitCount = $schoolAttendances->filter(fn($att) => strtolower($att->status) === 'izin')->unique('student_id')->count();
        $alphaCount = $schoolAttendances->filter(fn($att) => in_array(strtolower($att->status), ['alpa', 'alpha']))->unique('student_id')->count();
        
        $sickPermitCount = $sickCount + $permitCount;
        
        $earlyLeaveCount = $schoolAttendances->filter(function ($att) {
            return str_contains(strtolower($att->notes ?? ''), 'pulang awal');
        })->count();

        // Hitung "Belum Hadir" (Siswa yang belum scan Masuk hari ini sama sekali)
        $notYetScannedCount = 0;
        if ($period === 'today') {
             // Siswa yang sudah punya record Harian/Masuk/Pulang (Status apapun)
             $alreadyRecorded = $schoolAttendances->unique('student_id')->count();
             $notYetScannedCount = max(0, $totalStudents - $alreadyRecorded);
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
                'value' => $presentCount, // Termasuk yang terlambat
                'border' => 'border-emerald-500',
                'text_color' => 'text-gray-800',
                'icon_color' => 'text-emerald-500',
                'icon' => 'ph-check-circle',
                'filter_status' => 'present'
            ],
            [
                'title' => 'Belum Hadir',
                'value' => $notYetScannedCount, 
                'border' => 'border-slate-500',
                'text_color' => 'text-gray-800',
                'icon_color' => 'text-slate-500',
                'icon' => 'ph-minus-circle',
                'filter_status' => 'absent'
            ],
            [
                'title' => 'Terlambat',
                'value' => $lateCount, // Khusus Terlambat
                'border' => 'border-orange-500',
                'text_color' => 'text-gray-800',
                'icon_color' => 'text-orange-500',
                'icon' => 'ph-clock', 
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
        // Kita gunakan Query Database langsung agar efisien menghitung frekuensi
        $topLateStudents = AttendanceSiswa::with(['student.schoolClass']) 
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang']) 
            ->where(function($query) {
                // Logika Terlambat yang aman (Case Insensitive di DB level)
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
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->whereIn('status', ['Hadir', 'Tepat Waktu'])
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->select('student_id', DB::raw('count(*) as total_present'))
            ->groupBy('student_id')
            ->orderByDesc('total_present')
            ->take(5)
            ->get();

        // 6. LOG AKTIVITAS TERBARU (Live Feed - Termasuk Ekskul & Ibadah)
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
            ->whereIn('attendances_siswa.type', ['Harian', 'Masuk', 'Pulang'])
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

        // Ambil data untuk grafik
        $graphAttendances = AttendanceSiswa::whereBetween('attendance_date', [$graphStart, $graphEnd])->get();

        // Filter Sekolah saja (Harian/Masuk/Pulang)
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

            // LOGIKA GRAFIK:
            // 1. Bar Hijau: Hadir Tepat Waktu
            $weeklyPresentData[] = $dailyAtt->filter(fn($att) => in_array(strtolower($att->status), ['hadir', 'tepat waktu']))->unique('student_id')->count();
            
            // 2. Bar Oranye: Khusus Terlambat
            $weeklyLateData[] = $dailyAtt->filter(fn($att) => strtolower($att->status) === 'terlambat')->unique('student_id')->count();
            
            // 3. Bar Merah: Absen (Sakit/Izin/Alpha)
            $weeklyAbsentData[] = $dailyAtt->filter(fn($att) => in_array(strtolower($att->status), ['sakit', 'izin', 'alpa', 'alpha']))->unique('student_id')->count();

            $periodLoop->addDay();
        }

        return view('dashboard', [
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