<?php

namespace App\Http\Controllers;

use App\Models\StudentPermit;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StudentPermitController extends Controller
{
    /**
     * Halaman Utama (Dashboard Monitoring)
     */
    public function index()
    {
        // Mengambil data siswa yang sedang diluar (Status OUT)
        $activePermits = StudentPermit::with(['student.schoolClass']) 
            ->where('status', 'OUT')
            ->orderBy('time_out', 'asc') 
            ->get();

        // Mengambil 10 riwayat terakhir hari ini (Status RETURNED)
        $todayHistory = StudentPermit::with(['student.schoolClass'])
            ->where('status', 'RETURNED')
            ->whereDate('created_at', Carbon::today())
            ->latest('time_in')
            ->limit(10)
            ->get();

        return view('permit.index', compact('activePermits', 'todayHistory'));
    }

    /**
     * Helper: Filter Query (Agar tidak menulis ulang logika yang sama)
     */
    private function getFilteredQuery(Request $request)
    {
        // Eager load relasi untuk mencegah N+1 Query
        $query = StudentPermit::with(['student.schoolClass'])
            ->latest('time_out');

        // 1. Filter Pencarian (Nama, NIS, NISN)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // 2. Filter Tanggal (Jika ada input tanggal)
        if ($request->filled('date')) {
            $query->whereDate('time_out', $request->date);
        }

        // 3. Filter Status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('status', 'OUT');
            } elseif ($request->status == 'returned') {
                $query->where('status', 'RETURNED');
            } elseif ($request->status == 'overdue') {
                // Logika Telat: Masih OUT dan sudah lebih dari 15 menit
                $query->where('status', 'OUT')
                      ->where('time_out', '<=', Carbon::now()->subMinutes(15));
            }
        }

        return $query;
    }

    /**
     * Halaman Riwayat Lengkap & Laporan
     */
    public function history(Request $request)
    {
        // [PERBAIKAN] Logika navigasi tanggal dipindah ke sini (Skinny View)
        $currentDate = $request->date ?? date('Y-m-d'); // Default hari ini jika null
        
        // Hitung Prev & Next untuk tombol navigasi
        $prevDate = Carbon::parse($currentDate)->subDay()->format('Y-m-d');
        $nextDate = Carbon::parse($currentDate)->addDay()->format('Y-m-d');

        // Pastikan request date terisi untuk filter query
        $request->merge(['date' => $currentDate]);

        $query = $this->getFilteredQuery($request);
        $permits = $query->paginate(10)->withQueryString();

        return view('permit.history', compact('permits', 'currentDate', 'prevDate', 'nextDate'));
    }

    /**
     * Fitur Print Laporan (PDF View)
     */
    public function print(Request $request)
    {
        // Ambil semua data sesuai filter (tanpa pagination)
        $permits = $this->getFilteredQuery($request)->get();
        
        // Judul Laporan dinamis
        $title = 'Laporan Izin Siswa';
        if($request->filled('date')) {
            $title .= ' - Tanggal ' . Carbon::parse($request->date)->translatedFormat('d F Y');
        }

        return view('permit.print', compact('permits', 'title'));
    }

    /**
     * Fitur Export Excel
     */
    public function export(Request $request)
    {
        $permits = $this->getFilteredQuery($request)->get();
        $filename = 'Laporan_Izin_' . date('Y-m-d_H-i') . '.xls';

        // Mengembalikan response stream agar lebih aman dan efisien
        return response()->streamDownload(function() use ($permits) {
            echo view('permit.excel', compact('permits'));
        }, $filename, [
            "Content-Type" => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=\"$filename\""
        ]);
    }

    /**
     * Handle Scan QR / Input Manual (Cek Data Siswa)
     */
     public function scan(Request $request)
    {
        $request->validate(['identifier' => 'required']);

        // Cari siswa berdasarkan ID, NISN, atau RFID
        $student = Student::with('schoolClass')->where('student_id', $request->identifier)
            ->orWhere('nisn', $request->identifier)
            ->orWhere('rfid_id', $request->identifier)
            ->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan!',
            ], 404);
        }

        // Hitung jumlah izin hari ini untuk fitur "Red Zone"
        $todayPermitCount = StudentPermit::where('student_id', $student->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // Cek apakah siswa sedang di luar (Status OUT)
        $existingPermit = StudentPermit::where('student_id', $student->id)
            ->where('status', 'OUT')
            ->first();

        // SKENARIO 1: SISWA KEMBALI (CHECK-IN)
        if ($existingPermit) {
            $checkInTime = Carbon::now();
            
            // Hitung durasi
            // Pastikan time_out dicast datetime di Model agar diffInMinutes berjalan
            $duration = (int) $existingPermit->time_out->diffInMinutes($checkInTime);

            $existingPermit->update([
                'time_in' => $checkInTime,
                'status' => 'RETURNED',
                'duration_minutes' => $duration
            ]);

            return response()->json([
                'status' => 'success',
                'mode' => 'CHECK_IN',
                'message' => "Selamat Datang Kembali, {$student->name}.",
                'data' => [
                    'student' => $student,
                    'duration' => $duration
                ]
            ]);
        }

       // SKENARIO 2: SISWA AKAN KELUAR (PRE CHECK-OUT)
        return response()->json([
            'status' => 'success',
            'mode' => 'PRE_CHECK_OUT',
            'message' => "Silakan pilih alasan izin.",
            'data' => [
                'student' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'school_class' => $student->schoolClass, // Dibutuhkan oleh frontend modal UI
                    'today_permit_count' => $todayPermitCount // <--- Disisipkan untuk Red Zone
                ]
            ]
        ]);
    }

    /**
     * Simpan Data Izin Baru (Check-Out)
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'reason_category' => 'required',
            'notes' => 'nullable|string'
        ]);

        // Double check race condition
        if (StudentPermit::where('student_id', $request->student_id)->where('status', 'OUT')->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Siswa sudah tercatat di luar!'], 422);
        }

        $permit = StudentPermit::create([
            'student_id' => $request->student_id,
            'pic_teacher_id' => Auth::id(),
            'reason_category' => $request->reason_category,
            'notes' => $request->notes,
            'time_out' => Carbon::now(),
            'status' => 'OUT'
        ]);

        // Load relasi siswa untuk dikirim balik ke frontend
        $permit->load('student.schoolClass');

        return response()->json([
            'status' => 'success',
            'message' => 'Izin berhasil dicatat.',
            'data' => [
                'student' => $permit->student,
                'reason' => $permit->reason_category
            ]
        ]);
    }

 /**
     * Halaman Dashboard Analytics
     */
    public function analytics(Request $request)
    {
        // Tentukan bulan dan tahun dari request filter (default: bulan ini)
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $parsedDate = Carbon::parse($selectedMonth . '-01');
        $month = $parsedDate->month;
        $year = $parsedDate->year;

        // ==========================================
        // 1. DATA UNTUK CHART / GRAFIK
        // ==========================================
        
        // Data Jam Sibuk (Agregasi Bulan Terpilih)
        $timeStats = StudentPermit::select(DB::raw('HOUR(time_out) as hour'), DB::raw('count(*) as total'))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('total', 'hour');

        $timeLabels = $timeStats->keys()->map(fn($h) => sprintf('%02d:00', $h))->toArray();
        $timeData = $timeStats->values()->toArray();

        // Data Alasan (Bulan Terpilih)
        $reasonStats = StudentPermit::select('reason_category', DB::raw('count(*) as total'))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('reason_category')
            ->pluck('total', 'reason_category');
            
        $reasonLabels = $reasonStats->keys()->toArray();
        $reasonData = $reasonStats->values()->toArray();

        // Data Kelas Terbanyak Izin (Bulan Terpilih)
        $classStats = StudentPermit::join('students', 'student_permits.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id') 
            ->select('classes.name', DB::raw('count(student_permits.id) as total'))
            ->whereMonth('student_permits.created_at', $month)
            ->whereYear('student_permits.created_at', $year)
            ->groupBy('classes.name')
            ->orderByDesc('total')
            ->limit(10) 
            ->pluck('total', 'name');

        $classLabels = $classStats->keys()->toArray();
        $classData = $classStats->values()->toArray();


        // ==========================================
        // 2. DATA UNTUK KPI CARDS (KARTU STATISTIK)
        // ==========================================

        // KPI 1: Hitung Total Izin (Bulan Terpilih)
        $kpiTotalMonth = StudentPermit::whereMonth('created_at', $month)->whereYear('created_at', $year)->count();

        // KPI 2: Hitung Rata-rata Durasi (Bulan Terpilih)
        $kpiAvgDuration = StudentPermit::whereMonth('created_at', $month)->whereYear('created_at', $year)
                                    ->whereNotNull('duration_minutes')
                                    ->avg('duration_minutes');
        $kpiAvgDuration = round($kpiAvgDuration ?? 0); 

        // KPI 3: Hitung Siswa Telat (Bulan Terpilih)
        $kpiOverdue = StudentPermit::whereMonth('created_at', $month)->whereYear('created_at', $year)
            ->where(function ($query) {
                $query->where('duration_minutes', '>', 15)
                      ->orWhere(function ($q) {
                          $q->whereNull('time_in')
                            ->where('time_out', '<=', Carbon::now()->subMinutes(15));
                      });
            })->count();

        // KPI 4: Rate Penyelesaian (Berapa % siswa yang sudah kembali kelas)
        $totalReturnedMonth = StudentPermit::whereMonth('created_at', $month)->whereYear('created_at', $year)
            ->whereNotNull('time_in')
            ->count();
        $kpiCompletionRate = $kpiTotalMonth > 0 ? round(($totalReturnedMonth / $kpiTotalMonth) * 100) : 100;


        // ==========================================
        // 3. DATA UNTUK RED ZONE LEADERBOARD
        // ==========================================

        // Hitung Top 5 Siswa Paling Sering Izin (Bulan Terpilih)
        $topStudents = DB::table('student_permits')
            ->join('students', 'student_permits.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id') 
            ->select('students.name', 'classes.name as class_name', DB::raw('count(student_permits.id) as total_izin'))
            ->whereMonth('student_permits.created_at', $month)
            ->whereYear('student_permits.created_at', $year)
            ->groupBy('students.id', 'students.name', 'classes.name')
            ->orderByDesc('total_izin')
            ->limit(5)
            ->get();


        return view('permit.analytics', compact(
            'timeLabels', 'timeData', 
            'reasonLabels', 'reasonData', 
            'classLabels', 'classData',
            'kpiTotalMonth', 'kpiAvgDuration', 'kpiOverdue', 'kpiCompletionRate',
            'topStudents', 'selectedMonth', 'parsedDate'
        ));
    }
}