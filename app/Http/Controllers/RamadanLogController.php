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
    const RAMADAN_START_DATE = '2026-02-18'; 
    const FILL_START_TIME = '12:00'; 
    const FILL_END_TIME = '23:59';  

    private function getRamadanDay($date)
    {
        $start = Carbon::parse(self::RAMADAN_START_DATE);
        $current = Carbon::parse($date);
        // Jika sebelum Ramadhan, return 0
        if ($current->lt($start)) return 0;
        return $current->diffInDays($start) + 1;
    }

    public function studentIndex()
    {
        $studentId = Auth::guard('student')->id();
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $now = Carbon::now('Asia/Jakarta');
        
        $startDate = self::RAMADAN_START_DATE;

        // Ambil log untuk kalender grid
        $calendarLogs = RamadanLog::where('student_id', $studentId)
                            ->pluck('id', 'date') 
                            ->toArray();
        
        // --- LOGIKA CAN FILL (DIPERBAIKI) ---
        // 1. Cek apakah hari ini masih dalam rentang jam pengisian
        $startTime = Carbon::parse($today . ' ' . self::FILL_START_TIME, 'Asia/Jakarta');
        $endTime = Carbon::parse($today . ' ' . self::FILL_END_TIME, 'Asia/Jakarta');
        
        // Default false, cek kondisi
        $canFill = false;

        // Jika sekarang berada di antara jam mulai dan jam akhir
        if ($now->between($startTime, $endTime)) {
            $canFill = true;
        }

        // Ambil data log hari ini
        $todayRamadanLog = RamadanLog::where('student_id', $studentId)
                            ->whereDate('date', $today)
                            ->first();

        // Log terakhir yang dinilai guru (untuk notifikasi/info)
        $lastVerifiedLog = RamadanLog::where('student_id', $studentId)
                            ->whereNotNull('teacher_verified_at')
                            ->orderBy('date', 'desc')
                            ->first();
        
        $ramadanDay = $this->getRamadanDay($today);
        $isBeforeRamadan = Carbon::parse($today)->lt(Carbon::parse(self::RAMADAN_START_DATE));

        return view('ramadan.student_index', compact(
            'todayRamadanLog', 
            'today', 
            'lastVerifiedLog',
            'canFill',
            'ramadanDay',
            'isBeforeRamadan',
            'startDate',
            'calendarLogs'
        ));
    }

    public function leaderboard()
    {
        // Optimasi: Gunakan Eager Loading untuk log agar query tidak berat
        // FIX: Tambahkan whereHas('schoolClass') agar alumni tidak masuk leaderboard
        $students = Student::whereHas('schoolClass')
                    ->with(['ramadanLogs', 'schoolClass'])
                    ->get();

        $topStudents = $students->map(function($student) {
            $totalPoints = 0;

            foreach($student->ramadanLogs as $log) {
                // 1. Puasa (50 Poin)
                if ($log->is_fasting) $totalPoints += 50;

                // 2. Shalat Wajib (10 Poin per waktu)
                // Pastikan prayers berbentuk array sebelum dihitung
                $prayers = is_array($log->prayers) ? array_filter($log->prayers) : [];
                $totalPoints += (count($prayers) * 10);

                // 3. Sunnah (Menggunakan data_get atau null coalescing agar aman)
                if ($log->sunnah_deeds['tarawih'] ?? false) $totalPoints += 15;
                if ($log->sunnah_deeds['witir'] ?? false) $totalPoints += 5;
                if ($log->sunnah_deeds['dhuha'] ?? false) $totalPoints += 5;
                if ($log->sunnah_deeds['rawatib'] ?? false) $totalPoints += 5;
                if ($log->sunnah_deeds['sedekah'] ?? false) $totalPoints += 5;

                // 4. Tilawah (20 Poin)
                if (!empty($log->tadarus_surah)) $totalPoints += 20;

                // 5. Jumat (20 Poin)
                if (!empty($log->friday_khotib)) $totalPoints += 20;

                // 6. KULTUM (15 Poin)
                if (!empty($log->kultum_summary)) $totalPoints += 15;
            }

            $student->ramadan_points = $totalPoints;
            return $student;
        })
        ->sortByDesc('ramadan_points')
        ->values()
        ->take(10); // Ambil 10 besar saja setelah sorting

        return view('ramadan.leaderboard', compact('topStudents'));
    }

    public function store(Request $request)
    {
        $studentId = Auth::guard('student')->id();
        
        // Ambil tanggal hari ini server side untuk keamanan
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        
        // Input date dari form (biasanya hidden input)
        $requestDate = $request->input('date', $today);

        // KEAMANAN: Pastikan siswa hanya mengisi untuk hari ini
        if ($requestDate !== $today) {
             return redirect()->back()->with('error', 'Anda hanya dapat mengisi jurnal untuk hari ini.');
        }

        // VALIDASI INPUT
        $request->validate([
            'tadarus_surah' => 'nullable|string|max:100',
            'tadarus_ayah' => 'nullable|numeric|max:300',
            'murojaah_surah' => 'nullable|string|max:100',
            'friday_khotib' => 'nullable|string|max:100',
            'friday_summary' => 'nullable|string|max:1000',
            'kultum_penceramah' => 'nullable|string|max:100',
            'kultum_summary' => 'nullable|string|max:1000',
        ]);

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
                // NEW KULTUM DATA
                'kultum_penceramah' => $request->kultum_penceramah,
                'kultum_summary' => $request->kultum_summary,
            ];

            // Tambahkan data Jumat jika ada
            if ($request->has('friday_khotib')) {
                $logData['friday_khotib'] = $request->friday_khotib;
            }
            if ($request->has('friday_summary')) {
                $logData['friday_summary'] = $request->friday_summary;
            }

            // Simpan ke RamadanLog
            $ramadanLog = RamadanLog::updateOrCreate(
                ['student_id' => $studentId, 'date' => $requestDate],
                $logData
            );

            // Sinkronisasi ke StudentHabit (Sistem Habit Tracker Sekolah)
            StudentHabit::updateOrCreate(
                [
                    'student_id' => $studentId, 
                    'report_date' => $requestDate
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
            return redirect()->back()->with('success', 'Alhamdulillah! Jurnal Ramadhan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function adminReport(Request $request)
    {
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        $selectedClass = $request->get('class_id');
        $date = $request->get('date', Carbon::now('Asia/Jakarta')->toDateString());
        $isFriday = Carbon::parse($date)->isFriday();

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
            
            // Perbaikan Logika Count menggunakan filter
            $stats['fasting_count'] = $reports->filter(function($s) {
                return $s->ramadanLogs->first()?->is_fasting;
            })->count();

            $stats['prayer_complete_count'] = $reports->filter(function($s) {
                $log = $s->ramadanLogs->first();
                $prayers = isset($log->prayers) && is_array($log->prayers) ? $log->prayers : [];
                return count(array_filter($prayers)) == 5;
            })->count();

            $stats['friday_log_count'] = $reports->filter(function($s) {
                return !empty($s->ramadanLogs->first()?->friday_khotib);
            })->count();

        } else {
            // Stats Global (JIKA BELUM PILIH KELAS)
            
            // === FIX: HANYA HITUNG SISWA AKTIF (YANG PUNYA KELAS) ===
            // Sebelumnya: Student::count(); (Ini menghitung alumni juga)
            $stats['total_students'] = Student::whereHas('schoolClass')->count();
            
            // Ambil logs hari ini hanya dari siswa yang punya kelas
            $logsToday = RamadanLog::whereHas('student.schoolClass')
                        ->whereDate('date', $date)
                        ->get();
            
            $stats['fasting_count'] = $logsToday->where('is_fasting', true)->count();
            
            $stats['prayer_complete_count'] = $logsToday->filter(function($log) {
                $prayers = is_array($log->prayers) ? $log->prayers : [];
                return count(array_filter($prayers)) == 5;
            })->count();
            
            $stats['friday_log_count'] = $logsToday->whereNotNull('friday_khotib')->count();

            // Feed Terbaru
            $latestLogs = RamadanLog::with(['student.schoolClass'])
                ->whereHas('student.schoolClass') // Pastikan yang muncul cuma siswa aktif
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

    public function verifyFriday(Request $request, $id)
    {
        $log = RamadanLog::findOrFail($id);
        
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