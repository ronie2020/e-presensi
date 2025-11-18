<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AttendanceSiswa; // <-- Pastikan ini di-import
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan semua statistik.
     */
    public function index(Request $request)
    {
        // === 1. PENGATURAN FILTER ===
        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $selectedClassId = $request->input('class_id');
        $periode = $request->input('periode', 'Harian'); // Default Harian

        // === 1B. (PERBAIKAN) Ambil nama tabel absensi yang benar secara dinamis ===
        $attendanceTableName = (new AttendanceSiswa)->getTable();


        // === 2. AMBIL DATA DASAR ===
        $classes = SchoolClass::orderBy('name', 'asc')->get();

        // Query dasar untuk siswa, difilter berdasarkan kelas jika dipilih
        $studentQuery = Student::query();
        if ($selectedClassId) {
            $studentQuery->where('class_id', $selectedClassId);
        }
        $totalSiswa = $studentQuery->count();

        // Query dasar untuk absensi, difilter berdasarkan tanggal DAN kelas
        $attendanceQuery = AttendanceSiswa::query()->where('type', 'Harian');
        
        if ($selectedClassId) {
            // Filter berdasarkan ID siswa yang ada di kelas yang dipilih
            $studentIdsInClass = $studentQuery->pluck('id');
            $attendanceQuery->whereIn('student_id', $studentIdsInClass);
        }

        // Terapkan filter periode
        $start_date = $selectedDate->copy();
        $end_date = $selectedDate->copy();

        switch ($periode) {
            case 'Mingguan':
                $start_date = $selectedDate->copy()->startOfWeek(Carbon::MONDAY);
                $end_date = $selectedDate->copy()->endOfWeek(Carbon::SUNDAY);
                $attendanceQuery->whereBetween('attendance_date', [$start_date, $end_date]);
                break;
            case 'Bulanan':
                $attendanceQuery->whereMonth('attendance_date', $selectedDate->month)->whereYear('attendance_date', $selectedDate->year);
                break;
            case 'Tahunan':
                $attendanceQuery->whereYear('attendance_date', $selectedDate->year);
                break;
            case 'Harian':
            default:
                $attendanceQuery->whereDate('attendance_date', $selectedDate);
                break;
        }

        // Ambil data absensi yang sudah difilter
        $attendances = $attendanceQuery->get();

        // === 3. HITUNG STATISTIK KARTU ===
        $totalHadir = $attendances->where('status', 'Hadir')->count();
        $totalSakitIzinAlpa = $attendances->whereIn('status', ['Sakit', 'Izin', 'Alfa'])->count();
        
        // Asumsi 'notes' berisi 'Terlambat' atau 'Pulang Awal'
        $totalTerlambat = $attendances->where('notes', 'like', 'Terlambat%')->count(); 
        $totalPulangAwal = $attendances->where('notes', 'like', 'Pulang Awal%')->count(); // KARTU BARU

        // Hitung Tepat Waktu (Hadir - Terlambat)
        $totalTepatWaktu = $totalHadir - $totalTerlambat;
        
        // === PERBAIKAN LOGIKA BELUM HADIR ===
        $totalBelumHadir = 0;
        if ($periode == 'Harian') {
            // Dapatkan ID siswa yang sudah memiliki catatan absensi (Hadir, Sakit, Izin, Alfa) pada hari ini
            $siswaSudahAbsenIds = $attendances->pluck('student_id')->unique();
            
            // Hitung siswa yang belum ada di daftar absensi sama sekali (Belum Hadir)
            $totalBelumHadir = $studentQuery->whereNotIn('id', $siswaSudahAbsenIds)->count();
            
            // Pastikan tidak negatif (meskipun seharusnya tidak terjadi dengan logika whereNotIn)
            $totalBelumHadir = max(0, $totalBelumHadir);
        }


        // === 4. DATA UNTUK GRAFIK ===
        
        // Data Donut Chart (Presentasi Kehadiran) - Menggunakan data statistik kartu
        $donutChartData = [
            'labels' => ['Tepat Waktu', 'Terlambat', 'Belum Hadir', 'Pulang Awal', 'Sakit/Izin/Alpa'],
            'data' => [
                $totalTepatWaktu,
                $totalTerlambat,
                $totalBelumHadir, 
                $totalPulangAwal,
                $totalSakitIzinAlpa
            ],
        ];

        // Data Bar Chart (Progres Mingguan) - DIHITUNG DINAMIS
        // Tentukan rentang minggu berdasarkan $selectedDate (Senin-Minggu)
        $startOfWeek = $selectedDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $selectedDate->copy()->endOfWeek(Carbon::SUNDAY);
        
        $daysOfWeek = [];
        $currentDay = $startOfWeek->copy();
        while ($currentDay->lte($endOfWeek)) {
            $dateKey = $currentDay->format('Y-m-d');
            $dayName = $currentDay->dayName;
            
            // Mapping hari ke Bahasa Indonesia (singkatan)
            $mapping = [
                'Monday' => 'Sen', 'Tuesday' => 'Sel', 'Wednesday' => 'Rab', 
                'Thursday' => 'Kam', 'Friday' => 'Jum', 'Saturday' => 'Sab', 'Sunday' => 'Min'
            ];

            $daysOfWeek[$dateKey] = [
                'label' => $mapping[$dayName] ?? $dayName,
                'Hadir' => 0, // Tepat Waktu
                'Terlambat' => 0,
                'SIA' => 0, // Sakit, Izin, Alfa
            ];
            $currentDay->addDay();
        }

        // Query absensi untuk rentang minggu ini, difilter juga berdasarkan kelas
        $weeklyAttendanceQuery = AttendanceSiswa::query()
            ->where('type', 'Harian')
            ->whereBetween('attendance_date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')]);

        if ($selectedClassId) {
            $studentIdsInClass = Student::where('class_id', $selectedClassId)->pluck('id');
            $weeklyAttendanceQuery->whereIn('student_id', $studentIdsInClass);
        }

        $weeklyAttendance = $weeklyAttendanceQuery->get();
        
        // Agregasi data
        foreach ($weeklyAttendance as $att) {
            $dateKey = Carbon::parse($att->attendance_date)->format('Y-m-d');
            
            if (isset($daysOfWeek[$dateKey])) {
                if ($att->status == 'Hadir') {
                    if (str_contains($att->notes, 'Terlambat')) {
                        $daysOfWeek[$dateKey]['Terlambat']++;
                    } else {
                        // Tepat Waktu (Hadir yang tidak Terlambat)
                        $daysOfWeek[$dateKey]['Hadir']++; 
                    }
                } elseif (in_array($att->status, ['Sakit', 'Izin', 'Alfa'])) {
                    $daysOfWeek[$dateKey]['SIA']++;
                }
            }
        }
        
        // Siapkan Bar Chart Data Final
        $labels = array_column($daysOfWeek, 'label');
        $tepatWaktuData = array_column($daysOfWeek, 'Hadir');
        $terlambatData = array_column($daysOfWeek, 'Terlambat');
        $siaData = array_column($daysOfWeek, 'SIA');

        $barChartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Tepat Waktu',
                    'data' => $tepatWaktuData,
                    'backgroundColor' => 'rgba(34, 197, 94, 1)', // green-500
                ],
                [
                    'label' => 'Terlambat',
                    'data' => $terlambatData,
                    'backgroundColor' => 'rgba(234, 179, 8, 1)', // yellow-500
                ],
                 [
                    'label' => 'Tdk Hadir (S/I/A)',
                    'data' => $siaData,
                    'backgroundColor' => 'rgba(239, 68, 68, 1)', // red-500
                ]
            ]
        ];


        // === 5. DATA TABEL "SISWA PERLU PERHATIAN" ===
        // (PERBAIKAN): Mengganti 'attendance_siswas' dengan $attendanceTableName
        $studentsPerluPerhatian = Student::with('schoolClass')
            ->select('students.*',
                DB::raw("(SELECT COUNT(*) FROM {$attendanceTableName} WHERE {$attendanceTableName}.student_id = students.id AND {$attendanceTableName}.status = 'Alfa' AND YEAR({$attendanceTableName}.attendance_date) = YEAR(CURDATE())) as total_alfa"),
                DB::raw("(SELECT COUNT(*) FROM {$attendanceTableName} WHERE {$attendanceTableName}.student_id = students.id AND {$attendanceTableName}.notes LIKE 'Terlambat%' AND YEAR({$attendanceTableName}.attendance_date) = YEAR(CURDATE())) as total_terlambat"),
                DB::raw("(SELECT COUNT(*) FROM {$attendanceTableName} WHERE {$attendanceTableName}.student_id = students.id AND {$attendanceTableName}.notes LIKE 'Pulang Awal%' AND YEAR({$attendanceTableName}.attendance_date) = YEAR(CURDATE())) as total_pulang_awal")
            )
            ->where(function($query) use ($attendanceTableName) { // <-- Menambahkan 'use ($attendanceTableName)'
                $query->whereRaw("(SELECT COUNT(*) FROM {$attendanceTableName} WHERE {$attendanceTableName}.student_id = students.id AND {$attendanceTableName}.status = 'Alfa' AND YEAR({$attendanceTableName}.attendance_date) = YEAR(CURDATE())) > 0")
                      ->orWhereRaw("(SELECT COUNT(*) FROM {$attendanceTableName} WHERE {$attendanceTableName}.student_id = students.id AND {$attendanceTableName}.notes LIKE 'Terlambat%' AND YEAR({$attendanceTableName}.attendance_date) = YEAR(CURDATE())) > 0");
            })
            ->orderBy('total_terlambat', 'desc')
            ->orderBy('total_alfa', 'desc')
            ->take(10)
            ->get();


        // === 6. KIRIM SEMUA DATA KE VIEW ===
        return view('dashboard', [
            // Data Kartu
            'totalSiswa' => $totalSiswa,
            'totalHadir' => $totalHadir,
            'totalBelumHadir' => $totalBelumHadir,
            'totalTerlambat' => $totalTerlambat,
            'totalPulangAwal' => $totalPulangAwal,
            'totalSakitIzinAlpa' => $totalSakitIzinAlpa,
            
            // Data Filter
            'classes' => $classes,
            'selectedClassId' => $selectedClassId,
            'selectedDate' => $selectedDate,
            'periode' => $periode,
            
            // Data Grafik
            'donutChartData' => $donutChartData,
            'barChartData' => $barChartData,

            // Data Tabel
            'studentsPerluPerhatian' => $studentsPerluPerhatian,
        ]);
    }
}