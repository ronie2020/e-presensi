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
use App\Models\AlumniProfile; 

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LandingPageController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // --- DEFINISI STATUS (Case Insensitive handling) ---
        $statusHadir     = ['Hadir', 'hadir', 'Present', 'present', 'Tepat Waktu', 'tepat waktu'];
        $statusTerlambat = ['Terlambat', 'terlambat', 'Late', 'late', 'Telat'];
        $statusSakit     = ['Sakit', 'sakit', 'Sick'];
        $statusIzin      = ['Izin', 'izin', 'Permission'];
        $statusAlpa      = ['Alpa', 'alpa', 'Alpha', 'Absent'];

        // --- 1. STATISTIK HARIAN ---
        $dailyQuery = AttendanceSiswa::whereDate('attendance_date', $today)
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang']);

        $hadirTepat = (clone $dailyQuery)->whereIn('status', $statusHadir)->distinct('student_id')->count('student_id');
        $terlambat  = (clone $dailyQuery)->whereIn('status', $statusTerlambat)->distinct('student_id')->count('student_id');
        $tidakHadir = (clone $dailyQuery)->whereIn('status', array_merge($statusSakit, $statusIzin, $statusAlpa))->distinct('student_id')->count('student_id');

        $stats = [
            'hadir'       => $hadirTepat + $terlambat,
            'tepat_waktu' => $hadirTepat,
            'terlambat'   => $terlambat,
            'tidak_hadir' => $tidakHadir
        ];

        // --- 2. CHART KEHADIRAN MINGGUAN ---
        $startDate = Carbon::today()->subDays(6);
        $endDate = Carbon::today();
        
        $weeklyData = AttendanceSiswa::whereBetween('attendance_date', [$startDate, $endDate])
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->get();

        $chartLabels = [];
        $dataHadir = [];
        $dataTerlambat = [];
        $dataAbsen = [];

        $period = $startDate->copy();
        while ($period <= $endDate) {
            $dateStr = $period->toDateString();
            $chartLabels[] = $period->format('d/m'); 

            $dailyAtt = $weeklyData->filter(function ($att) use ($dateStr) {
                $attDate = $att->attendance_date instanceof \Carbon\Carbon 
                            ? $att->attendance_date->toDateString() 
                            : substr($att->attendance_date, 0, 10);
                return $attDate === $dateStr;
            });

            $countStatus = function($collection, $statuses) {
                return $collection->filter(function ($item) use ($statuses) {
                    return in_array($item->status, $statuses) || in_array(ucfirst($item->status), $statuses);
                })->unique('student_id')->count();
            };

            $dataHadir[] = $countStatus($dailyAtt, $statusHadir);
            $dataTerlambat[] = $countStatus($dailyAtt, $statusTerlambat);
            $dataAbsen[] = $countStatus($dailyAtt, array_merge($statusSakit, $statusIzin, $statusAlpa));

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
        $allGuestbooks = GuestBook::latest()->take(50)->get();

        $extracurriculars = Extracurricular::withCount('members')->with(['attendances' => function($query) { $query->latest('date')->limit(1); }])->get();

        // 6. DATA ALUMNI UNTUK LANDING PAGE
        $alumniStats = [
            'total' => Student::where('status', 'graduated')->count(),
            'sma' => AlumniProfile::where('activity_status', 'SMA')->count(),
            'smk' => AlumniProfile::where('activity_status', 'SMK')->count(),
            'ma' => AlumniProfile::where('activity_status', 'MA')->count(),
            'pesantren' => AlumniProfile::where('activity_status', 'Pesantren')->count(),
            'bekerja' => AlumniProfile::whereIn('activity_status', ['Bekerja', 'Wirausaha', 'Lainnya'])->count(),
        ];

        // Ambil Testimoni (Limit 6 untuk halaman depan)
        $alumniTestimonials = AlumniProfile::whereNotNull('testimony')
            ->where('testimony', '!=', '') 
            ->with('student') 
            ->latest()
            ->take(6) 
            ->get();

        return view('welcome', compact(
            'stats', 'barChartData', 'libraryStats', 'libraryChartData', 
            'announcements', 'achievements', 'activities', 'teachers',
            'guestbooks', 'allGuestbooks', 'extracurriculars', 'agendas', 'schoolStats',
            'alumniStats', 'alumniTestimonials'
        ));
    }

    public function activities()
    {
        $activities = SchoolActivity::latest()->paginate(9);
        return view('activities', compact('activities'));
    }

    public function achievements(Request $request)
    {
        $query = Achievement::with('student')->orderBy('date', 'desc');

        if ($request->has('level') && $request->level != 'Semua') {
            $query->where('level', $request->level);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $achievements = $query->paginate(12);
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

    /**
     * [BARU] Halaman Semua Testimoni
     * Menampilkan semua testimoni dengan pagination
     */
    public function testimonials()
    {
        // Mengambil semua testimoni yang tidak kosong, diurutkan terbaru
        $testimonials = AlumniProfile::with('student')
            ->whereNotNull('testimony')
            ->where('testimony', '!=', '') 
            ->latest('updated_at') 
            ->paginate(12); // Menampilkan 12 per halaman

        // Pastikan Anda sudah membuat file view 'testimonials.blade.php'
        return view('testimonials', compact('testimonials'));
    }
}