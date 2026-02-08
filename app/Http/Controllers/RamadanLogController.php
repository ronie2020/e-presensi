<?php

namespace App\Http\Controllers;

use App\Models\RamadanLog;
use App\Models\Student;
use App\Models\StudentHabit; 
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RamadanLogController extends Controller
{
    // KONFIGURASI SENTRAL
    // Ubah tanggal ini setiap tahun tanpa perlu menyentuh View
    const RAMADAN_START_DATE = '2026-02-18'; 
    
    // Konfigurasi Jam Pengisian (Opsional: Cegah isi sebelum waktunya)
    const FILL_START_TIME = '12:00'; // Mulai bisa isi jam 12 siang
    const FILL_END_TIME = '23:59';   // Sampai tengah malam

    /**
     * Helper: Hitung Hari ke-n Ramadhan
     */
    private function getRamadanDay($date)
    {
        $start = Carbon::parse(self::RAMADAN_START_DATE);
        $current = Carbon::parse($date);
        
        if ($current->lt($start)) return 0; // Belum mulai
        
        // Return selisih hari + 1
        return $current->diffInDays($start) + 1;
    }

    /**
     * Tampilan Tracker untuk Siswa
     */
    public function studentIndex()
    {
        $studentId = Auth::guard('student')->id();
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $now = Carbon::now('Asia/Jakarta');
        
        // Logika Waktu Pengisian
        $canFill = true;
        // Contoh Logika: Jika ingin membatasi jam pengisian
        // $canFill = $now->between(
        //     Carbon::parse($today . ' ' . self::FILL_START_TIME),
        //     Carbon::parse($today . ' ' . self::FILL_END_TIME)
        // );

        $todayRamadanLog = RamadanLog::where('student_id', $studentId)->whereDate('date', $today)->first();

        $lastVerifiedLog = RamadanLog::where('student_id', $studentId)
                            ->whereNotNull('teacher_verified_at')
                            ->orderBy('date', 'desc')
                            ->first();
        
        // Data Tambahan untuk View Dashboard/Tab
        $ramadanDay = $this->getRamadanDay($today);
        $isBeforeRamadan = Carbon::parse($today)->lt(Carbon::parse(self::RAMADAN_START_DATE));

        return view('ramadan.student_index', compact(
            'todayRamadanLog', 
            'today', 
            'lastVerifiedLog',
            'canFill',
            'ramadanDay',
            'isBeforeRamadan'
        ));
    }

    /**
     * Tampilan Dashboard Widget (Tab Jurnal)
     * Method ini mungkin dipanggil via view composer atau langsung di controller dashboard utama
     */
    public function getDashboardData() {
        // ... logika pengambilan data untuk dashboard utama
        // Pastikan variabel $ramadanDay dan $isBeforeRamadan dikirim juga ke view yang me-load tab-ramadan-jurnal.blade.php
    }

    /**
     * Tampilan Leaderboard Kebaikan (Gamifikasi - UPDATED: WEIGHTED SYSTEM)
     */
    public function leaderboard()
    {
        // Ambil data siswa beserta Logs nya
        $students = Student::with(['ramadanLogs', 'schoolClass'])->get();

        // Hitung Poin di Memory (Collection) untuk fleksibilitas logika
        $topStudents = $students->map(function($student) {
            $totalPoints = 0;

            foreach($student->ramadanLogs as $log) {
                // 1. Poin Puasa (Bobot Terbesar: 50 Poin)
                if ($log->is_fasting) $totalPoints += 50;

                // 2. Poin Shalat Wajib (10 Poin per waktu = Max 50)
                $prayers = array_filter($log->prayers ?? []);
                $totalPoints += (count($prayers) * 10);

                // 3. Poin Sunnah (5 Poin per sunnah = Max 25)
                // Tarawih kita beri bonus lebih, misal 15 poin
                if ($log->sunnah_deeds['tarawih'] ?? false) $totalPoints += 15;
                if ($log->sunnah_deeds['witir'] ?? false) $totalPoints += 5;
                if ($log->sunnah_deeds['dhuha'] ?? false) $totalPoints += 5;
                if ($log->sunnah_deeds['rawatib'] ?? false) $totalPoints += 5;
                if ($log->sunnah_deeds['sedekah'] ?? false) $totalPoints += 5;

                // 4. Poin Tilawah (20 Poin)
                if (!empty($log->tadarus_surah)) $totalPoints += 20;

                // 5. Poin Jumat (20 Poin)
                if (!empty($log->friday_khotib)) $totalPoints += 20;
            }

            $student->ramadan_points = $totalPoints;
            return $student;
        })
        ->sortByDesc('ramadan_points')
        ->take(10)
        ->values();

        return view('ramadan.leaderboard', compact('topStudents'));
    }

    /**
     * Simpan Jurnal dengan Sinkronisasi Otomatis
     */
    public function store(Request $request)
    {
        $studentId = Auth::guard('student')->id();
        $date = $request->input('date', Carbon::now('Asia/Jakarta')->toDateString());

        // Validasi Tanggal (Opsional: Cegah isi tanggal besok)
        if (Carbon::parse($date)->isFuture()) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengisi jurnal untuk hari esok.');
        }

        try {
            DB::beginTransaction();

            $logData = [
                'is_fasting' => $request->has('is_fasting'),
                'prayers' => [
                    'subuh'   => $request->has('prayer_subuh'),
                    'dzuhur'  => $request->has('prayer_dzuhur'),
                    'ashar'   => $request->has('prayer_ashar'),
                    'maghrib' => $request->has('prayer_maghrib'),
                    'isya'    => $request->has('prayer_isya'),
                ],
                'sunnah_deeds' => [
                    'tarawih' => $request->has('sunnah_tarawih'),
                    'witir'   => $request->has('sunnah_witir'),
                    'dhuha'   => $request->has('sunnah_dhuha'),
                    'rawatib' => $request->has('sunnah_rawatib'),
                    'sedekah' => $request->has('sunnah_sedekah'),
                ],
                'tadarus_surah' => $request->tadarus_surah,
                'tadarus_ayah' => $request->tadarus_ayah,
                'murojaah_surah' => $request->murojaah_surah,
            ];

            if ($request->has('friday_khotib')) {
                $logData['friday_khotib'] = $request->friday_khotib;
            }
            if ($request->has('friday_summary')) {
                $logData['friday_summary'] = $request->friday_summary;
            }

            $ramadanLog = RamadanLog::updateOrCreate(
                ['student_id' => $studentId, 'date' => $date],
                $logData
            );

            // Sinkronisasi ke Tabel StudentHabit
            StudentHabit::updateOrCreate(
                [
                    'student_id' => $studentId, 
                    'report_date' => $date
                ],
                [
                    'prayer_subuh'   => $request->has('prayer_subuh'),
                    'prayer_dzuhur'  => $request->has('prayer_dzuhur'),
                    'prayer_ashar'   => $request->has('prayer_ashar'),
                    'prayer_maghrib' => $request->has('prayer_maghrib'),
                    'prayer_isya'    => $request->has('prayer_isya'),
                    'prayer_dhuha'   => $request->has('sunnah_dhuha'),
                    'odoa_surah'     => $request->tadarus_surah,
                    'odoa_ayat'      => $request->tadarus_ayah,
                ]
            );

            DB::commit();
            return redirect()->back()->with('success', 'Jurnal Ramadhan berhasil disimpan! Poin anda bertambah.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }

    /**
     * Rekapitulasi Admin
     */
    public function adminReport(Request $request)
    {
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        $selectedClass = $request->get('class_id');
        $date = $request->get('date', Carbon::now('Asia/Jakarta')->toDateString());
        $isFriday = Carbon::parse($date)->isFriday();

        // Inisialisasi Data Default
        $reports = collect();
        $latestLogs = collect();
        $stats = [
            'total_students' => 0,
            'fasting_count' => 0,
            'prayer_complete_count' => 0,
            'friday_log_count' => 0,
            'percentage_fasting' => 0
        ];

        if ($selectedClass) {
            $reports = Student::where('class_id', $selectedClass)
                        ->with(['ramadanLogs' => function($query) use ($date) {
                            $query->whereDate('date', $date);
                        }])
                        ->orderBy('name', 'asc')
                        ->get();

            $stats['total_students'] = $reports->count();
            $stats['fasting_count'] = $reports->filter(fn($s) => $s->ramadanLogs->first()?->is_fasting)->count();
            $stats['prayer_complete_count'] = $reports->filter(fn($s) => count(array_filter($s->ramadanLogs->first()?->prayers ?? [])) == 5)->count();
            $stats['friday_log_count'] = $reports->filter(fn($s) => $s->ramadanLogs->first()?->friday_khotib)->count();

        } else {
            $stats['total_students'] = Student::count();
            $logsToday = RamadanLog::whereDate('date', $date)->get();
            $stats['fasting_count'] = $logsToday->where('is_fasting', true)->count();
            $stats['prayer_complete_count'] = $logsToday->filter(function($log) {
                return count(array_filter($log->prayers ?? [])) == 5;
            })->count();
            $stats['friday_log_count'] = $logsToday->whereNotNull('friday_khotib')->count();

            $latestLogs = RamadanLog::with(['student.schoolClass'])
                ->whereDate('date', $date)
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();
        }

        $stats['percentage_fasting'] = $stats['total_students'] > 0 
            ? round(($stats['fasting_count'] / $stats['total_students']) * 100) 
            : 0;

        return view('ramadan.admin_report', compact(
            'classes', 
            'reports', 
            'selectedClass', 
            'date', 
            'isFriday', 
            'stats', 
            'latestLogs'
        ));
    }

    /**
     * Simpan Feedback Guru (Dengan Validasi & Pesan Error)
     */
    public function verifyFriday(Request $request, $id)
    {
        $log = RamadanLog::findOrFail($id);
        
        // VALIDASI SERVER SIDE
        $validated = $request->validate([
            'teacher_score' => 'required|numeric|min:0|max:100',
            'teacher_note' => 'nullable|string|max:500',
        ], [
            'teacher_score.min' => 'Nilai tidak boleh kurang dari 0.',
            'teacher_score.max' => 'Nilai tidak boleh lebih dari 100.',
        ]);

        $log->update([
            'teacher_score' => $validated['teacher_score'],
            'teacher_note' => $validated['teacher_note'],
            'teacher_verified_at' => now(),
            'teacher_id' => Auth::id(), 
        ]);

        return back()->with('success', 'Penilaian berhasil disimpan.');
    }
}