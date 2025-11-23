<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AttendanceSiswa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LandingPageController extends Controller
{
    public function index()
    {
        // 1. Ambil Pengumuman Terbaru (Tetap Sama)
        try {
            $announcements = Announcement::where('is_active', true)
                ->with('author')
                ->latest()
                ->take(3)
                ->get();
        } catch (\Exception $e) {
            $announcements = [];
        }

        // 2. STATISTIK HARIAN (LOGIKA BARU: GROUPING)
        $today = Carbon::today();
        
        // Ambil semua data hari ini dulu
        $todaysAttendance = AttendanceSiswa::whereDate('attendance_date', $today)
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang']) // Ambil semua tipe
            ->get()
            ->groupBy('student_id'); // KUNCI: Disatukan per siswa

        // Variabel penghitung
        $hadirCount = 0;
        $sakitCount = 0;
        $izinCount = 0;
        $terlambatCount = 0;

        // Loop setiap siswa (bukan setiap baris)
        foreach ($todaysAttendance as $studentId => $logs) {
            // Ambil semua status & catatan siswa ini
            $statuses = $logs->pluck('status')->toArray();
            $allNotes = $logs->pluck('notes')->implode(' ');

            // Prioritas Status
            if (in_array('Hadir', $statuses)) {
                $hadirCount++;
                
                // Cek Terlambat khusus yang Hadir
                if (stripos($allNotes, 'Terlambat') !== false) {
                    $terlambatCount++;
                }
            } elseif (in_array('Sakit', $statuses)) {
                $sakitCount++;
            } elseif (in_array('Izin', $statuses)) {
                $izinCount++;
            }
        }
        
        $stats = [
            'hadir' => $hadirCount,
            'sakit' => $sakitCount,
            'izin' => $izinCount,
            'terlambat' => $terlambatCount,
        ];

        // 3. GRAFIK MINGGUAN (LOGIKA BARU: GROUPING)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        // Ambil data seminggu sekaligus
        $weeklyAttendance = AttendanceSiswa::whereBetween('attendance_date', [$startOfWeek, $endOfWeek])
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->get();

        $weeklyData = [
            'labels' => [],
            'tepat' => [],
            'telat' => [],
            'absen' => []
        ];

        // Loop 6 hari (Senin - Sabtu)
        for ($i = 0; $i < 6; $i++) {
            $dateCheck = $startOfWeek->copy()->addDays($i);
            $dateString = $dateCheck->format('Y-m-d');
            
            $weeklyData['labels'][] = $dateCheck->translatedFormat('D'); // Sen, Sel...

            // Filter data mentah untuk hari yang sedang di-loop
            $dailyLogs = $weeklyAttendance->filter(function($item) use ($dateString) {
                return Carbon::parse($item->attendance_date)->format('Y-m-d') == $dateString;
            });

            // Grouping per siswa
            $dailyGrouped = $dailyLogs->groupBy('student_id');

            $countHadir = 0;
            $countTelat = 0;
            $countAbsen = 0; // S/I/A

            foreach ($dailyGrouped as $logs) {
                $statuses = $logs->pluck('status')->toArray();
                $notes = $logs->pluck('notes')->implode(' ');

                if (in_array('Hadir', $statuses)) {
                    $countHadir++;
                    if (stripos($notes, 'Terlambat') !== false) {
                        $countTelat++;
                    }
                } elseif (in_array('Sakit', $statuses) || in_array('Izin', $statuses) || in_array('Alfa', $statuses)) {
                    $countAbsen++;
                }
            }

            // Masukkan ke array data
            $weeklyData['tepat'][] = $countHadir - $countTelat; // Tepat waktu = Total Hadir - Yang Telat
            $weeklyData['telat'][] = $countTelat;
            $weeklyData['absen'][] = $countAbsen;
        }

        $barChartData = [
            'labels' => $weeklyData['labels'],
            'datasets' => [
                ['label' => 'Tepat Waktu', 'data' => $weeklyData['tepat'], 'backgroundColor' => '#10B981'], // Green
                ['label' => 'Terlambat', 'data' => $weeklyData['telat'], 'backgroundColor' => '#F59E0B'], // Yellow
                ['label' => 'Tdk Hadir', 'data' => $weeklyData['absen'], 'backgroundColor' => '#EF4444']  // Red
            ]
        ];

        return view('welcome', compact('announcements', 'stats', 'barChartData'));
    }
}