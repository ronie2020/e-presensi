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
use App\Models\Agenda; // [PENTING] Import Model Agenda
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
        $barChartData = []; 
        $libraryStats = [];
        $libraryChartData = [];

        // --- 3. DATA CMS ---
        $announcements = Announcement::orderBy('created_at', 'desc')->limit(3)->get();
        $achievements = Achievement::with('student')->orderBy('date', 'desc')->limit(6)->get();
        $activities = SchoolActivity::latest()->take(3)->get();
        
        // [PERBAIKAN] Mengambil Data Agenda untuk Halaman Depan
        $agendas = Agenda::where('event_date', '>=', now()->subDays(1)) // Ambil agenda mulai dari kemarin (agar agenda hari ini ttp muncul)
                        ->orderBy('event_date', 'asc')
                        ->limit(4) // Batasi 4 agenda saja di halaman depan
                        ->get();
        
        // --- 4. DATA GURU ---
        $teachers = User::whereIn('role', ['Guru', 'Wali Kelas', 'Kepala Sekolah', 'Guru Piket'])->latest()->take(8)->get();

        // --- 5. DATA BUKU TAMU ---
        $guestbooks = GuestBook::latest()->take(3)->get();

        // --- 6. DATA EKSTRAKURIKULER ---
        $extracurriculars = Extracurricular::withCount('members')
            ->with(['attendances' => function($query) {
                $query->latest('date')->limit(1); 
            }])
            ->get();

        // [PENTING] Tambahkan 'agendas' ke dalam compact
        return view('welcome', compact(
            'stats', 'barChartData', 'libraryStats', 'libraryChartData', 
            'announcements', 'achievements', 'activities', 'teachers',
            'guestbooks', 
            'extracurriculars',
            'agendas' 
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