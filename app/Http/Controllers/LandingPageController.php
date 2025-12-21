<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceSiswa; 
use App\Models\LibraryVisit; 
use App\Models\Borrowing;
use App\Models\Announcement;
use App\Models\Achievement;
use App\Models\SchoolActivity;
use App\Models\User;
use App\Models\GuestBook;
use App\Models\Extracurricular; 
use App\Models\Agenda;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\LmsMaterial;
use App\Models\LmsAssignment;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LandingPageController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // --- DEFINISI STATUS (Case Insensitive handling) ---
        $statusHadir     = ['Hadir', 'hadir', 'Present', 'present', 'Tepat Waktu'];
        $statusTerlambat = ['Terlambat', 'terlambat', 'Late', 'late', 'Telat'];
        $statusSakit     = ['Sakit', 'sakit', 'Sick'];
        $statusIzin      = ['Izin', 'izin', 'Permission'];
        $statusAlpa      = ['Alpa', 'alpa', 'Alpha', 'Absent'];

        // --- 1. STATISTIK HARIAN ---
        $hadir = AttendanceSiswa::whereDate('attendance_date', $today)->whereIn('status', $statusHadir)->distinct('student_id')->count('student_id');
        $terlambat = AttendanceSiswa::whereDate('attendance_date', $today)->whereIn('status', $statusTerlambat)->distinct('student_id')->count('student_id');
        
        $tidakHadir = AttendanceSiswa::whereDate('attendance_date', $today)
                        ->whereIn('status', array_merge($statusSakit, $statusIzin, $statusAlpa))
                        ->distinct('student_id')->count('student_id');

        $stats = [
            'hadir'       => $hadir + $terlambat, 
            'tepat_waktu' => $hadir,
            'terlambat'   => $terlambat,
            'tidak_hadir' => $tidakHadir
        ];

        // --- 2. CHART KEHADIRAN MINGGUAN ---
        $startDate = Carbon::today()->subDays(6);
        $endDate = Carbon::today();
        
        $chartLabels = [];
        $dataHadir = [];
        $dataTerlambat = [];
        $dataAbsen = [];

        $period = $startDate->copy();
        while ($period <= $endDate) {
            $dateStr = $period->toDateString();
            $chartLabels[] = $period->format('d/m'); 

            $dailyAtt = AttendanceSiswa::whereDate('attendance_date', $dateStr)->get();

            $dataHadir[] = $dailyAtt->whereIn('status', $statusHadir)->unique('student_id')->count();
            $dataTerlambat[] = $dailyAtt->whereIn('status', $statusTerlambat)->unique('student_id')->count();
            $dataAbsen[] = $dailyAtt->whereIn('status', array_merge($statusSakit, $statusIzin, $statusAlpa))->unique('student_id')->count();

            $period->addDay();
        }

        $barChartData = [
            'labels' => $chartLabels,
            'datasets' => [
                ['label' => 'Hadir Tepat', 'data' => $dataHadir, 'backgroundColor' => '#10b981', 'borderRadius' => 4],
                ['label' => 'Terlambat', 'data' => $dataTerlambat, 'backgroundColor' => '#f59e0b', 'borderRadius' => 4],
                ['label' => 'Tidak Hadir', 'data' => $dataAbsen, 'backgroundColor' => '#f43f5e', 'borderRadius' => 4]
            ]
        ];

        // --- 3. CHART PERPUSTAKAAN ---
        $libLabels = [];
        $libData = [];
        $periodLib = $startDate->copy();

        while ($periodLib <= $endDate) {
            $libLabels[] = $periodLib->format('d/m');
            $count = LibraryVisit::whereDate('date', $periodLib->toDateString())->count();
            $libData[] = $count;
            $periodLib->addDay();
        }

        $libraryChartData = ['labels' => $libLabels, 'data' => $libData];

        $libraryStats = [
            'visitors_today' => LibraryVisit::whereDate('date', $today)->count(), 
            'books_borrowed' => Borrowing::where('status', 'borrowed')->count()
        ];

        // --- 4. CACHE STATISTIK ---
        $schoolStats = Cache::remember('school_profile_stats', 60 * 60, function () {
            $materiCount = class_exists('App\Models\LmsMaterial') ? \App\Models\LmsMaterial::count() : 0;
            $tugasCount = class_exists('App\Models\LmsAssignment') ? \App\Models\LmsAssignment::count() : 0;
            return [
                'siswa' => Student::count(),
                'guru'  => User::where('role', 'guru')->count(),
                'rombel'=> SchoolClass::count(),
                'materi'=> $materiCount,
                'tugas' => $tugasCount,
            ];
        });

        // --- 5. DATA LAINNYA ---
        $announcements = Announcement::orderBy('created_at', 'desc')->limit(3)->get();
        $achievements = Achievement::with('student')->orderBy('date', 'desc')->limit(6)->get();
        $activities = SchoolActivity::latest()->take(3)->get();
        $agendas = Agenda::where('event_date', '>=', now()->subDays(1))->orderBy('event_date', 'asc')->limit(4)->get();
        $teachers = User::whereIn('role', ['Guru', 'Wali Kelas', 'Kepala Sekolah', 'Guru Piket'])->latest()->take(8)->get();
        $guestbooks = GuestBook::latest()->take(3)->get();
        $extracurriculars = Extracurricular::withCount('members')->with(['attendances' => function($query) { $query->latest('date')->limit(1); }])->get();

        return view('welcome', compact(
            'stats', 'barChartData', 'libraryStats', 'libraryChartData', 
            'announcements', 'achievements', 'activities', 'teachers',
            'guestbooks', 'extracurriculars', 'agendas', 'schoolStats'
        ));
    }

    // --- [BARU] HALAMAN GALERI KEGIATAN ---
    public function activities()
    {
        // Ambil semua kegiatan, urutkan dari terbaru, paginate 9 per halaman
        $activities = SchoolActivity::latest()->paginate(9);
        return view('activities', compact('activities'));
    }

    // --- [BARU] HALAMAN ARSIP PRESTASI ---
    public function achievements(Request $request)
    {
        $query = Achievement::with('student')->orderBy('date', 'desc');

        // Fitur Filter Level (Nasional, Provinsi, dll)
        if ($request->has('level') && $request->level != 'Semua') {
            $query->where('level', $request->level);
        }

        // Fitur Pencarian Judul
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $achievements = $query->paginate(12);
        
        // Ambil daftar level unik untuk dropdown filter
        $levels = Achievement::select('level')->distinct()->pluck('level');

        return view('achievements', compact('achievements', 'levels'));
    }

    public function teachers(Request $request)
    {
        $search = $request->input('q');
        $query = User::whereIn('role', ['Guru', 'Wali Kelas', 'Kepala Sekolah', 'Guru Piket']);
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
        }
        $teachers = $query->orderBy('name', 'asc')->paginate(12);
        return view('teachers', compact('teachers'));
    }
}