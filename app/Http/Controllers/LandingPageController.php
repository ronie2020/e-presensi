<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceSiswa; 
use App\Models\LibraryVisit; // Pastikan model ini ada atau gunakan Borrowing
use App\Models\Borrowing;
use App\Models\Announcement;
use App\Models\Achievement;
use App\Models\SchoolActivity;
use App\Models\User;
use App\Models\GuestBook;
use App\Models\Extracurricular; 
use App\Models\Agenda; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        // --- 1. STATISTIK HARIAN (KARTU ATAS) ---
        $hadir = AttendanceSiswa::whereDate('attendance_date', $today)->whereIn('status', $statusHadir)->distinct('student_id')->count('student_id');
        $terlambat = AttendanceSiswa::whereDate('attendance_date', $today)->whereIn('status', $statusTerlambat)->distinct('student_id')->count('student_id');
        
        // Hitung total tidak hadir (Sakit + Izin + Alfa)
        $tidakHadir = AttendanceSiswa::whereDate('attendance_date', $today)
                        ->whereIn('status', array_merge($statusSakit, $statusIzin, $statusAlpa))
                        ->distinct('student_id')->count('student_id');

        $stats = [
            'hadir'       => $hadir + $terlambat, // Total yang masuk fisik
            'tepat_waktu' => $hadir,
            'terlambat'   => $terlambat,
            'tidak_hadir' => $tidakHadir
        ];

        // --- 2. CHART KEHADIRAN MINGGUAN (Fix Logika Kosong) ---
        // Kita ambil data 7 hari terakhir
        $startDate = Carbon::today()->subDays(6);
        $endDate = Carbon::today();
        
        $chartLabels = [];
        $dataHadir = [];
        $dataTerlambat = [];
        $dataAbsen = [];

        $period = $startDate->copy();
        while ($period <= $endDate) {
            $dateStr = $period->toDateString();
            $chartLabels[] = $period->format('d/m'); // Label Tanggal (misal: 01/12)

            // Query Harian
            $dailyAtt = AttendanceSiswa::whereDate('attendance_date', $dateStr)->get();

            $dataHadir[] = $dailyAtt->whereIn('status', $statusHadir)->unique('student_id')->count();
            $dataTerlambat[] = $dailyAtt->whereIn('status', $statusTerlambat)->unique('student_id')->count();
            $dataAbsen[] = $dailyAtt->whereIn('status', array_merge($statusSakit, $statusIzin, $statusAlpa))->unique('student_id')->count();

            $period->addDay();
        }

        $barChartData = [
            'labels' => $chartLabels,
            'datasets' => [
                [
                    'label' => 'Hadir Tepat',
                    'data' => $dataHadir,
                    'backgroundColor' => '#10b981', // Emerald
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Terlambat',
                    'data' => $dataTerlambat,
                    'backgroundColor' => '#f59e0b', // Amber
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Tidak Hadir',
                    'data' => $dataAbsen,
                    'backgroundColor' => '#f43f5e', // Rose
                    'borderRadius' => 4,
                ]
            ]
        ];

        // --- 3. CHART PERPUSTAKAAN (Fix Logika Kosong) ---
        // Menggunakan tabel Borrowing sebagai indikator aktivitas
        $libLabels = [];
        $libData = [];
        $periodLib = $startDate->copy();

        while ($periodLib <= $endDate) {
            $libLabels[] = $periodLib->format('d/m');
            // Hitung jumlah peminjaman pada tanggal tersebut
            $count = Borrowing::whereDate('created_at', $periodLib->toDateString())->count();
            $libData[] = $count;
            $periodLib->addDay();
        }

        $libraryChartData = [
            'labels' => $libLabels,
            'data' => $libData
        ];

        // Statistik Ringkas Perpustakaan
        $libraryStats = [
            'visitors_today' => Borrowing::whereDate('created_at', $today)->count(), // Sementara pakai data peminjaman harian
            'books_borrowed' => Borrowing::where('status', 'borrowed')->count()
        ];


        // --- 4. DATA CMS (Tetap) ---
        $announcements = Announcement::orderBy('created_at', 'desc')->limit(3)->get();
        $achievements = Achievement::with('student')->orderBy('date', 'desc')->limit(6)->get();
        $activities = SchoolActivity::latest()->take(3)->get();
        $agendas = Agenda::where('event_date', '>=', now()->subDays(1))
                        ->orderBy('event_date', 'asc')
                        ->limit(4)
                        ->get();
        $teachers = User::whereIn('role', ['Guru', 'Wali Kelas', 'Kepala Sekolah', 'Guru Piket'])->latest()->take(8)->get();
        $guestbooks = GuestBook::latest()->take(3)->get();
        $extracurriculars = Extracurricular::withCount('members')
            ->with(['attendances' => function($query) {
                $query->latest('date')->limit(1); 
            }])
            ->get();

        return view('welcome', compact(
            'stats', 'barChartData', 'libraryStats', 'libraryChartData', 
            'announcements', 'achievements', 'activities', 'teachers',
            'guestbooks', 'extracurriculars', 'agendas' 
        ));
    }

    // --- METHOD TEACHERS (Tetap) ---
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