<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSiswa;
use App\Models\CbtStudentExam;
use App\Models\LmsMaterial;
use App\Models\SchoolClass;
use App\Models\SystemAuditLog;
use App\Models\TeachingSession;
use App\Models\User;
use App\Models\UserLoginLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemAnalyticsController extends Controller
{
    /**
     * Dashboard Rekap Penggunaan Aplikasi
     */
    public function index(Request $request)
    {
        // 1. Tentukan Rentang Periode
        $period = $request->input('period', 'today');
        $now = Carbon::now();

        if ($period === 'today') {
            $startDate = $now->copy()->startOfDay();
            $endDate = $now->copy()->endOfDay();
            $periodLabel = 'Hari Ini (' . $now->translatedFormat('d F Y') . ')';
        } elseif ($period === 'this_week') {
            $startDate = $now->copy()->startOfWeek();
            $endDate = $now->copy()->endOfWeek();
            $periodLabel = 'Minggu Ini (' . $startDate->translatedFormat('d M') . ' - ' . $endDate->translatedFormat('d M Y') . ')';
        } elseif ($period === 'this_month') {
            $startDate = $now->copy()->startOfMonth();
            $endDate = $now->copy()->endOfMonth();
            $periodLabel = 'Bulan ' . $now->translatedFormat('F Y');
        } elseif ($period === 'custom') {
            $startDate = $request->filled('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : $now->copy()->subDays(7)->startOfDay();
            $endDate = $request->filled('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : $now->copy()->endOfDay();
            $periodLabel = $startDate->translatedFormat('d M Y') . ' s/d ' . $endDate->translatedFormat('d M Y');
        } else {
            $startDate = $now->copy()->startOfDay();
            $endDate = $now->copy()->endOfDay();
            $periodLabel = 'Hari Ini';
        }

        // 2. Metrik Utama (KPI Cards)
        $totalLogins = UserLoginLog::whereBetween('login_at', [$startDate, $endDate])->count();
        $teacherLogins = UserLoginLog::whereBetween('login_at', [$startDate, $endDate])
            ->where('role', '!=', 'Siswa')
            ->count();
        $studentLogins = UserLoginLog::whereBetween('login_at', [$startDate, $endDate])
            ->where('role', 'Siswa')
            ->count();

        // Pengguna Unik Aktif
        $uniqueTeachers = UserLoginLog::whereBetween('login_at', [$startDate, $endDate])
            ->where('role', '!=', 'Siswa')
            ->distinct('user_id')
            ->count('user_id');

        $uniqueStudents = UserLoginLog::whereBetween('login_at', [$startDate, $endDate])
            ->where('role', 'Siswa')
            ->distinct('user_id')
            ->count('user_id');

        // Total Guru Terdaftar
        $totalRegisteredTeachers = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Guru', 'Wali Kelas', 'Guru Mata Pelajaran', 'Guru Piket']);
        })->orWhere('role', 'LIKE', '%Guru%')->count();

        $teacherAdoptionRate = $totalRegisteredTeachers > 0 ? round(($uniqueTeachers / $totalRegisteredTeachers) * 100) : 0;

        // 3. Utilisasi Modul Operasional
        $kbmSessionsCount = TeachingSession::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])->count();
        $attendanceScansCount = AttendanceSiswa::whereBetween('attendance_date', [$startDate->toDateString(), $endDate->toDateString()])->count();
        $lmsMaterialsCount = LmsMaterial::whereBetween('created_at', [$startDate, $endDate])->count();
        $cbtExamsFinished = CbtStudentExam::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalAuditActions = SystemAuditLog::whereBetween('created_at', [$startDate, $endDate])->count();

        // 4. Data Grafik Tren Login Harian (7 Hari Terakhir / Rentang Terpilih)
        $trendDates = [];
        $trendTeachers = [];
        $trendStudents = [];

        $diffDays = $startDate->diffInDays($endDate);
        if ($diffDays > 31) {
            $chartStart = $endDate->copy()->subDays(30)->startOfDay();
        } else {
            $chartStart = $startDate->copy();
        }

        $cursor = $chartStart->copy();
        while ($cursor->lte($endDate)) {
            $dStr = $cursor->toDateString();
            $dLabel = $cursor->format('d M');

            $trendDates[] = $dLabel;
            $trendTeachers[] = UserLoginLog::whereDate('login_at', $dStr)
                ->where('role', '!=', 'Siswa')
                ->count();
            $trendStudents[] = UserLoginLog::whereDate('login_at', $dStr)
                ->where('role', 'Siswa')
                ->count();

            $cursor->addDay();
        }

        // 5. Distribusi Perangkat (Device Breakdown)
        $devices = UserLoginLog::whereBetween('login_at', [$startDate, $endDate])
            ->select('device', DB::raw('count(*) as total'))
            ->groupBy('device')
            ->pluck('total', 'device')
            ->toArray();

        $deviceDesktop = $devices['Desktop'] ?? 0;
        $deviceMobile = $devices['Mobile'] ?? 0;
        $deviceTablet = $devices['Tablet'] ?? 0;

        // 6. Distribusi Aktivitas per Modul (Audit Logs)
        $moduleStats = SystemAuditLog::whereBetween('created_at', [$startDate, $endDate])
            ->select('module', DB::raw('count(*) as total'))
            ->groupBy('module')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        // 7. Radar Keaktifan Guru (Supervisi Kepala Sekolah)
        // Menampilkan daftar guru, kapan login terakhir, dan berapa jurnal yang dibuat
        $teachers = User::where(function ($q) {
            $q->whereHas('roles', function ($rq) {
                $rq->whereIn('name', ['Guru', 'Wali Kelas', 'Guru Mata Pelajaran', 'Guru Piket']);
            })->orWhere('role', 'LIKE', '%Guru%');
        })
        ->withCount(['teachingLoads'])
        ->get()
        ->map(function ($teacher) use ($startDate, $endDate) {
            $lastLogin = UserLoginLog::where('user_id', $teacher->id)
                ->where('user_type', 'user')
                ->latest('login_at')
                ->first();

            $sessionsCount = TeachingSession::where('teacher_id', $teacher->id)
                ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                ->count();

            $teacher->last_login = $lastLogin ? $lastLogin->login_at : null;
            $teacher->last_login_human = $lastLogin ? $lastLogin->login_at->diffForHumans() : 'Belum pernah';
            $teacher->sessions_count = $sessionsCount;
            $teacher->is_active_in_period = $sessionsCount > 0 || ($lastLogin && $lastLogin->login_at->between($startDate, $endDate));

            return $teacher;
        })
        ->sortBy([
            ['is_active_in_period', 'desc'],
            ['sessions_count', 'desc'],
        ]);

        // 8. Live Feed Log Audit Terakhir
        $auditQuery = SystemAuditLog::latest('created_at');

        if ($request->filled('filter_module')) {
            $auditQuery->where('module', $request->input('filter_module'));
        }
        if ($request->filled('filter_action')) {
            $auditQuery->where('action', $request->input('filter_action'));
        }
        if ($request->filled('search')) {
            $kw = $request->input('search');
            $auditQuery->where(function ($q) use ($kw) {
                $q->where('description', 'LIKE', "%{$kw}%")
                  ->orWhere('user_name', 'LIKE', "%{$kw}%")
                  ->orWhere('ip_address', 'LIKE', "%{$kw}%");
            });
        }

        $auditLogs = $auditQuery->paginate(15)->withQueryString();

        return view('admin.system_usage.index', compact(
            'period',
            'periodLabel',
            'startDate',
            'endDate',
            'totalLogins',
            'teacherLogins',
            'studentLogins',
            'uniqueTeachers',
            'uniqueStudents',
            'totalRegisteredTeachers',
            'teacherAdoptionRate',
            'kbmSessionsCount',
            'attendanceScansCount',
            'lmsMaterialsCount',
            'cbtExamsFinished',
            'totalAuditActions',
            'trendDates',
            'trendTeachers',
            'trendStudents',
            'deviceDesktop',
            'deviceMobile',
            'deviceTablet',
            'moduleStats',
            'teachers',
            'auditLogs'
        ));
    }

    /**
     * Cetak Laporan Supervisi Penggunaan Aplikasi (Format Resmi)
     */
    public function print(Request $request)
    {
        $period = $request->input('period', 'this_month');
        $now = Carbon::now();

        if ($period === 'today') {
            $startDate = $now->copy()->startOfDay();
            $endDate = $now->copy()->endOfDay();
            $periodLabel = 'Hari Ini (' . $now->translatedFormat('d F Y') . ')';
        } elseif ($period === 'this_week') {
            $startDate = $now->copy()->startOfWeek();
            $endDate = $now->copy()->endOfWeek();
            $periodLabel = 'Minggu Ini (' . $startDate->translatedFormat('d M') . ' - ' . $endDate->translatedFormat('d M Y') . ')';
        } else {
            $startDate = $now->copy()->startOfMonth();
            $endDate = $now->copy()->endOfMonth();
            $periodLabel = 'Bulan ' . $now->translatedFormat('F Y');
        }

        $totalLogins = UserLoginLog::whereBetween('login_at', [$startDate, $endDate])->count();
        $uniqueTeachers = UserLoginLog::whereBetween('login_at', [$startDate, $endDate])
            ->where('role', '!=', 'Siswa')
            ->distinct('user_id')
            ->count('user_id');

        $kbmSessionsCount = TeachingSession::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])->count();
        $attendanceScansCount = AttendanceSiswa::whereBetween('attendance_date', [$startDate->toDateString(), $endDate->toDateString()])->count();

        $teachers = User::where(function ($q) {
            $q->whereHas('roles', function ($rq) {
                $rq->whereIn('name', ['Guru', 'Wali Kelas', 'Guru Mata Pelajaran', 'Guru Piket']);
            })->orWhere('role', 'LIKE', '%Guru%');
        })->get()->map(function ($teacher) use ($startDate, $endDate) {
            $lastLogin = UserLoginLog::where('user_id', $teacher->id)
                ->where('user_type', 'user')
                ->latest('login_at')
                ->first();

            $sessionsCount = TeachingSession::where('teacher_id', $teacher->id)
                ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                ->count();

            $teacher->last_login = $lastLogin ? $lastLogin->login_at : null;
            $teacher->sessions_count = $sessionsCount;
            return $teacher;
        });

        return view('admin.system_usage.print', compact(
            'periodLabel',
            'startDate',
            'endDate',
            'totalLogins',
            'uniqueTeachers',
            'kbmSessionsCount',
            'attendanceScansCount',
            'teachers'
        ));
    }
}
