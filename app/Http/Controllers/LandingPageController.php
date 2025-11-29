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
use App\Models\GuestBook; // <--- [BARU] Tambahkan Model Ini
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // --- DEFINISI STATUS ---
        $statusHadir     = ['Hadir', 'hadir', 'Present', 'present', 'Tepat Waktu'];
        $statusTerlambat = ['Terlambat', 'terlambat', 'Late', 'late', 'Telat', 'telat'];
        $statusSakit     = ['Sakit', 'sakit', 'Sick', 'sick'];
        $statusIzin      = ['Izin', 'izin', 'Permission', 'permission'];
        $statusAlpa      = ['Alpa', 'alpa', 'Alpha', 'alpha', 'Absent', 'absent'];

        // --- 1. STATISTIK HARIAN ---
        $hadir = AttendanceSiswa::whereDate('attendance_date', $today)->whereIn('status', $statusHadir)->distinct('student_id')->count('student_id');
        $terlambat = AttendanceSiswa::whereDate('attendance_date', $today)->whereIn('status', $statusTerlambat)->distinct('student_id')->count('student_id');
        $sakit = AttendanceSiswa::whereDate('attendance_date', $today)->whereIn('status', $statusSakit)->distinct('student_id')->count('student_id');
        $izin = AttendanceSiswa::whereDate('attendance_date', $today)->whereIn('status', $statusIzin)->distinct('student_id')->count('student_id');
        $alpa = AttendanceSiswa::whereDate('attendance_date', $today)->whereIn('status', $statusAlpa)->distinct('student_id')->count('student_id');

        $stats = [
            'hadir'       => $hadir + $terlambat,
            'tepat_waktu' => $hadir,
            'terlambat'   => $terlambat,
            'tidak_hadir' => $sakit + $izin + $alpa
        ];

        // --- 2. CHART MINGGUAN ---
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $getQuery = function($statusList) use ($startOfWeek, $endOfWeek) {
            return AttendanceSiswa::select(DB::raw('DATE(attendance_date) as date'), DB::raw('count(distinct student_id) as total'))
                ->whereBetween('attendance_date', [$startOfWeek, $endOfWeek])
                ->whereIn('status', $statusList)
                ->groupBy('date')
                ->pluck('total', 'date');
        };

        $dataTepatWaktu = $getQuery($statusHadir);
        $dataTerlambat  = $getQuery($statusTerlambat);
        $allTidakHadir = array_merge($statusSakit, $statusIzin, $statusAlpa);
        $dataTidakHadir = $getQuery($allTidakHadir);

        $labels = [];
        $datasetHadir = [];
        $datasetTelat = [];
        $datasetAbsen = [];

        for ($i = 0; $i < 7; $i++) {
            $currentDay = $startOfWeek->copy()->addDays($i);
            $dateKey = $currentDay->format('Y-m-d');
            $labels[] = $currentDay->locale('id')->isoFormat('dddd'); 
            $datasetHadir[] = $dataTepatWaktu[$dateKey] ?? 0;
            $datasetTelat[] = $dataTerlambat[$dateKey] ?? 0;
            $datasetAbsen[] = $dataTidakHadir[$dateKey] ?? 0;
        }

        $barChartData = [
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Hadir Tepat Waktu', 'data' => $datasetHadir, 'backgroundColor' => '#10b981', 'borderRadius' => 4],
                ['label' => 'Terlambat', 'data' => $datasetTelat, 'backgroundColor' => '#f59e0b', 'borderRadius' => 4],
                ['label' => 'Tidak Hadir', 'data' => $datasetAbsen, 'backgroundColor' => '#ef4444', 'borderRadius' => 4]
            ]
        ];

        // --- 3. DATA PERPUSTAKAAN ---
        try {
            $libraryStats = [
                'visitors_today' => LibraryVisit::whereDate('date', $today)->count(),
                'books_borrowed' => Borrowing::where('status', 'borrowed')->count(),
            ];
            
            $libWeeklyData = LibraryVisit::select(DB::raw('DATE(date) as visit_date'), DB::raw('count(*) as total'))
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->groupBy('visit_date')->pluck('total', 'visit_date');

            $libData = [];
            foreach ($labels as $index => $dayName) {
                $date = $startOfWeek->copy()->addDays($index)->format('Y-m-d');
                $libData[] = $libWeeklyData[$date] ?? 0;
            }
            $libraryChartData = ['labels' => $labels, 'data' => $libData];
        } catch (\Exception $e) {
            $libraryStats = ['visitors_today' => 0, 'books_borrowed' => 0];
            $libraryChartData = ['labels' => $labels, 'data' => array_fill(0, 7, 0)];
        }

        // --- 4. DATA CMS ---
        $announcements = Announcement::orderBy('created_at', 'desc')->limit(3)->get();
        $achievements = Achievement::with('student')->orderBy('date', 'desc')->limit(6)->get();
        $activities = SchoolActivity::latest()->take(3)->get();

        // --- 5. DATA GURU ---
       $teachers = User::whereIn('role', ['Guru', 'Wali Kelas', 'Kepala Sekolah', 'Guru Piket'])
                        ->latest()
                        ->take(8) 
                        ->get();

        // --- 6. DATA BUKU TAMU (TESTIMONI PENGUNJUNG) --- <--- [BARU] BAGIAN INI
        // Mengambil 3 inputan terbaru dari tabel guest_books
        $guestbooks = GuestBook::latest()->take(3)->get();

        return view('welcome', compact(
            'stats', 'barChartData', 'libraryStats', 'libraryChartData', 
            'announcements', 'achievements', 'activities', 'teachers',
            'guestbooks' // <--- [PENTING] Jangan lupa kirim variabel ini ke view
        ));
    }

    // --- METHOD UNTUK HALAMAN SEMUA GURU ---
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