<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AttendanceSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\StudentPermit;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // =====================================================================
        // 1. TENTUKAN PERIODE & TANGGAL
        // =====================================================================
        $period = $request->query('period', 'today'); 
        $dateParam = $request->query('date', Carbon::today()->toDateString());

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

        // FILTER SISWA AKTIF (Bukan Alumni)
        $filterActiveStudent = function($query) {
            $query->where('status', '!=', 'graduated'); 
        };

        // Total Siswa Aktif
        $totalStudents = Student::where($filterActiveStudent)->count();

        // =====================================================================
        // 2. DATA STATISTIK UTAMA (OPTIMASI: QUERY TINGKAT DATABASE)
        // =====================================================================
        // Base query untuk periode berjalan
        $baseQuery = AttendanceSiswa::whereHas('student', $filterActiveStudent)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang', 'harian', 'masuk', 'pulang']);

        // Hitung metrik menggunakan clone agar tidak perlu get() semua data ke RAM
        $presentCount = (clone $baseQuery)
            ->whereIn('status', ['Hadir', 'Tepat Waktu', 'Terlambat', 'hadir', 'tepat waktu', 'terlambat'])
            ->distinct('student_id')->count('student_id');

        $lateCount = (clone $baseQuery)
            ->whereIn('status', ['Terlambat', 'terlambat'])
            ->distinct('student_id')->count('student_id');
            
        $presentOnTimeCount = max(0, $presentCount - $lateCount);

        $sickPermitCount = (clone $baseQuery)
            ->whereIn('status', ['Sakit', 'sakit', 'Izin', 'izin'])
            ->distinct('student_id')->count('student_id');

        $alphaCount = (clone $baseQuery)
            ->whereIn('status', ['Alpa', 'Alpha', 'alpa', 'alpha'])
            ->distinct('student_id')->count('student_id');
        
        $earlyLeaveCount = (clone $baseQuery)
            ->where('notes', 'LIKE', '%pulang awal%')
            ->distinct('student_id')->count('student_id');

        // Hitung yang belum absen (khusus hari ini)
        $notYetScannedCount = 0;
        if ($period === 'today') {
             $alreadyRecorded = (clone $baseQuery)->distinct('student_id')->count('student_id');
             $notYetScannedCount = max(0, $totalStudents - $alreadyRecorded);
        }

        // =====================================================================
        // 3. KOMPARASI TREN (vs Kemarin)
        // =====================================================================
        $trendHadir = 0;
        if ($period === 'today') {
            $yesterday = $selectedDate->copy()->subDay();
            if ($yesterday->isSunday()) $yesterday->subDay(2); // Lewati hari Minggu
            
            $yesterdayPresent = AttendanceSiswa::whereHas('student', $filterActiveStudent)
                ->whereDate('attendance_date', $yesterday->toDateString())
                ->whereIn('type', ['Harian', 'Masuk', 'Pulang', 'harian', 'masuk', 'pulang'])
                ->whereIn('status', ['Hadir', 'Tepat Waktu', 'Terlambat', 'hadir', 'tepat waktu', 'terlambat'])
                ->distinct('student_id')
                ->count('student_id');
            
            $trendHadir = $presentCount - $yesterdayPresent;
        }

        // =====================================================================
        // 4. DATA KARTU (CARDS)
        // =====================================================================
        $cards = [
            [
                'title' => 'Total Siswa Aktif', 
                'value' => $totalStudents, 
                'icon' => 'ph-student', 
                'filter_status' => 'all',
                'action_url' => route('students.index'),
                'action_label' => 'Buka Data Siswa'
            ],
            [
                'title' => 'Total Hadir', 
                'value' => $presentCount, 
                'icon' => 'ph-check-circle', 
                'percentage' => $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100, 1) : 0, 
                'filter_status' => 'present', 
                'trend' => $period === 'today' ? $trendHadir : null,
                'action_url' => route('reports.daily', ['date' => $dateParam, 'activeTab' => 'hadir']),
                'action_label' => 'Lihat Rekap Hadir'
            ],
            [
                'title' => 'Belum Hadir', 
                'value' => $notYetScannedCount, 
                'icon' => 'ph-minus-circle', 
                'filter_status' => 'absent',
                'action_url' => route('reports.daily', ['date' => $dateParam, 'activeTab' => 'belum']),
                'action_label' => 'Cek Siswa Belum Hadir'
            ],
            [
                'title' => 'Terlambat', 
                'value' => $lateCount, 
                'icon' => 'ph-clock', 
                'filter_status' => 'late',
                'action_url' => route('reports.daily', ['date' => $dateParam, 'activeTab' => 'hadir']),
                'action_label' => 'Lihat Siswa Terlambat'
            ],
            [
                'title' => 'Pulang Awal / Izin', 
                'value' => $earlyLeaveCount, 
                'icon' => 'ph-person-simple-run', 
                'filter_status' => 'early_leave',
                'action_url' => route('permit.index'),
                'action_label' => 'Kelola Perizinan Keluar'
            ],
            [
                'title' => 'Sakit / Izin', 
                'value' => $sickPermitCount, 
                'icon' => 'ph-first-aid', 
                'filter_status' => 'excused',
                'action_url' => route('reports.daily', ['date' => $dateParam, 'activeTab' => 'lain']),
                'action_label' => 'Lihat Data Sakit / Izin'
            ]
        ];

        // =====================================================================
        // 5. TOP SISWA (TERLAMBAT & TERAJIN)
        // =====================================================================
        $topLateStudents = (clone $baseQuery)->with(['student.schoolClass'])
            ->whereIn('status', ['Terlambat', 'terlambat'])
            ->select('student_id', DB::raw('count(*) as total_late'))
            ->groupBy('student_id')
            ->orderByDesc('total_late')
            ->take(5)->get();

        $topPunctualStudents = (clone $baseQuery)->with(['student.schoolClass'])
            ->whereIn('status', ['Hadir', 'Tepat Waktu', 'hadir', 'tepat waktu'])
            ->select('student_id', DB::raw('count(*) as total_present'))
            ->groupBy('student_id')
            ->orderByDesc('total_present')
            ->take(5)->get();

        // =====================================================================
        // 6. AKTIVITAS TERBARU
        // =====================================================================
        $recentActivities = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', $filterActiveStudent)
            ->whereDate('attendance_date', $dateParam)
            ->latest('created_at')
            ->take(6)->get();

        // =====================================================================
        // 7. PERINGKAT KELAS (TERBAIK & TERENDAH) - PERHITUNGAN PERSENTASE AKURAT
        // =====================================================================
        // Hitung total siswa aktif per kelas
        $classStudentCounts = DB::table('students')
            ->where('status', '!=', 'graduated')
            ->whereNull('deleted_at')
            ->whereNotNull('class_id')
            ->select('class_id', DB::raw('count(*) as total'))
            ->groupBy('class_id')
            ->pluck('total', 'class_id');

        $classQueryBase = DB::table('attendances_siswa') 
            ->join('students', 'attendances_siswa.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->where('students.status', '!=', 'graduated') 
            ->whereBetween('attendances_siswa.attendance_date', [$startDate, $endDate])
            ->whereIn('attendances_siswa.type', ['Harian', 'Masuk', 'Pulang', 'harian', 'masuk', 'pulang'])
            ->whereNull('students.deleted_at');

        $classRanks = (clone $classQueryBase)
            ->whereIn('attendances_siswa.status', ['Hadir', 'Tepat Waktu', 'Terlambat', 'hadir', 'tepat waktu', 'terlambat'])
            ->select('classes.id as class_id', 'classes.name as class_name', DB::raw('count(DISTINCT attendances_siswa.student_id) as present_count'))
            ->groupBy('classes.id', 'classes.name')
            ->get()
            ->map(function($rank) use ($classStudentCounts) {
                $total = $classStudentCounts[$rank->class_id] ?? 0;
                $rank->total_students = $total;
                $rank->percentage = $total > 0 ? min(100, round(($rank->present_count / $total) * 100, 1)) : 0;
                return $rank;
            })
            ->sortByDesc('percentage')
            ->take(5)
            ->values();
            
        $lowestClassRanks = (clone $classQueryBase)
            ->whereIn('attendances_siswa.status', ['Alfa', 'Alpa', 'Alpha', 'Sakit', 'Izin', 'alfa', 'alpa', 'alpha', 'sakit', 'izin'])
            ->select('classes.id as class_id', 'classes.name as class_name', DB::raw('count(DISTINCT attendances_siswa.student_id) as absent_count'))
            ->groupBy('classes.id', 'classes.name')
            ->get()
            ->map(function($rank) use ($classStudentCounts) {
                $total = $classStudentCounts[$rank->class_id] ?? 0;
                $rank->total_students = $total;
                $rank->percentage = $total > 0 ? min(100, round(($rank->absent_count / $total) * 100, 1)) : 0;
                return $rank;
            })
            ->sortByDesc('absent_count')
            ->take(5)
            ->values();

        // =====================================================================
        // 8. DATA GRAFIK (CHART.JS) - SUPER OPTIMIZED
        // =====================================================================
        $graphStart = ($period === 'today') ? $selectedDate->copy()->subDays(6) : $startDate;
        $graphEnd   = ($period === 'today') ? $selectedDate : $endDate;

        // Ambil data rekapan harian (bukan ambil semua data mentah)
        $chartDataRaw = AttendanceSiswa::whereHas('student', $filterActiveStudent)
            ->whereBetween('attendance_date', [$graphStart, $graphEnd])
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang', 'harian', 'masuk', 'pulang'])
            ->select(DB::raw('DATE(attendance_date) as date'), 'status', DB::raw('count(DISTINCT student_id) as total'))
            ->groupBy('date', 'status')
            ->get();

        $weeklyPresentData = []; 
        $weeklyLateData = []; 
        $weeklyAbsentData = []; 
        $chartLabels = [];

        $periodLoop = $graphStart->copy();
        while($periodLoop <= $graphEnd) {
            $dateStr = $periodLoop->format('Y-m-d'); 
            $chartLabels[] = $periodLoop->format('d M'); 

            $dailyStats = $chartDataRaw->where('date', $dateStr);
            
            $weeklyPresentData[] = $dailyStats->whereIn('status', ['Hadir', 'Tepat Waktu', 'hadir', 'tepat waktu'])->sum('total');
            $weeklyLateData[] = $dailyStats->whereIn('status', ['Terlambat', 'terlambat'])->sum('total');
            $weeklyAbsentData[] = $dailyStats->whereIn('status', ['Sakit', 'Izin', 'Alpa', 'Alpha', 'sakit', 'izin', 'alpa', 'alpha'])->sum('total');

            $periodLoop->addDay();
        }

        // =====================================================================
        // 8.5 MONITORING SISWA KELUAR (PRIORITAS SISWA TERLAMA DI LUAR)
        // =====================================================================
        $studentsOut = StudentPermit::with('student.schoolClass')
            ->where('status', 'OUT')
            ->orderBy('time_out', 'asc')
            ->get();
        $countOut = $studentsOut->count();

        // =====================================================================
        // 8.6 JADWAL MENGAJAR GURU (TIMETABLE BARU)
        // =====================================================================
        $hariIni = Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd');
        $groupedSchedules = [];

        if (auth()->check() && auth()->user()->hasRole(['Guru', 'Guru Mata Pelajaran', 'Wali Kelas'])) {
            if (class_exists(\App\Models\Timetable::class)) {
                // Tambahkan 'todaySession' agar widget bisa baca status kelas
                $rawSchedules = \App\Models\Timetable::with(['timeslot', 'studentClass', 'subject', 'todaySession'])
                    ->where('teacher_id', auth()->id())
                    ->where('day_of_week', $hariIni)
                    ->get()
                    ->sortBy(function($jadwal) {
                        return $jadwal->timeslot->order_sequence ?? 0;
                    })->values(); // Reset array keys

                $currentGroup = null;

                foreach ($rawSchedules as $schedule) {
                    if (!$currentGroup) {
                        $currentGroup = collect([$schedule]);
                    } else {
                        $lastSchedule = $currentGroup->last();
                        // Jika Kelas dan Mapel sama, gabungkan ke blok yang sama
                        if ($lastSchedule->class_id == $schedule->class_id && $lastSchedule->subject_id == $schedule->subject_id) {
                            $currentGroup->push($schedule);
                        } else {
                            $groupedSchedules[] = $currentGroup;
                            $currentGroup = collect([$schedule]);
                        }
                    }
                }
                
                if ($currentGroup) {
                    $groupedSchedules[] = $currentGroup;
                }
            }
        }

        // =====================================================================
        // 9. RENDER VIEW
        // =====================================================================
        return view('dashboard', [
            'period' => $period, 
            'date' => $dateParam, 
            'totalStudents' => $totalStudents,
            'presentOnTimeCount' => $presentOnTimeCount,
            'lateCount' => $lateCount,
            'absentCount' => $alphaCount, 
            'sickPermitCount' => $sickPermitCount,
            'notYetScannedCount' => $notYetScannedCount,
            'cards' => $cards,
            'topLateStudents' => $topLateStudents,
            'topPunctualStudents' => $topPunctualStudents,
            'recentActivities' => $recentActivities,
            'classRanks' => $classRanks,
            'lowestClassRanks' => $lowestClassRanks, 
            'chartLabels' => $chartLabels,
             'weeklyPresentData' => $weeklyPresentData, 
            'weeklyLateData' => $weeklyLateData,       
            'weeklyAbsentData' => $weeklyAbsentData,   
            'studentsOut' => $studentsOut, 
            'countOut' => $countOut, 
            'groupedSchedules' => collect($groupedSchedules), // <-- Variabel baru yang digrupkan untuk widget     
        ]);
    }
}