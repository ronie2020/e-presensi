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
    /**
     * Tampilan Tracker untuk Siswa
     * [PERBAIKAN] Menyamakan nama variabel dengan View dan menambahkan lastVerifiedLog
     */
    public function studentIndex()
    {
        $studentId = Auth::guard('student')->id();
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        
        // [FIX 1] Ubah nama variabel dari $todayLog ke $todayRamadanLog agar sesuai dengan tab-ramadan-jurnal.blade.php
        $todayRamadanLog = RamadanLog::where('student_id', $studentId)->whereDate('date', $today)->first();

        // [FIX 2] Tambahkan query untuk mengambil log terakhir yang sudah dinilai guru
        // Ini wajib ada agar section "Nilai Guru" di blade bisa muncul
        $lastVerifiedLog = RamadanLog::where('student_id', $studentId)
                            ->whereNotNull('teacher_verified_at')
                            ->orderBy('date', 'desc')
                            ->first();

        return view('ramadan.student_index', compact('todayRamadanLog', 'today', 'lastVerifiedLog'));
    }

    /**
     * Tampilan Leaderboard Kebaikan (Gamifikasi)
     */
    public function leaderboard()
    {
        $topStudents = Student::withCount(['ramadanLogs as points_raw'])
            ->with('schoolClass')
            ->get()
            ->map(function($s) {
                // Contoh logika poin sederhana: 1 hari isi jurnal = 100 poin
                // Anda bisa mengembangkannya nanti (misal: shalat 5 waktu = +50, puasa = +50)
                $s->ramadan_points = ($s->points_raw ?? 0) * 100;
                return $s;
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

        try {
            DB::beginTransaction();

            // 1. Simpan ke Tabel Ramadan
            // Data array untuk update/create
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

            // [TAMBAHAN] Simpan Laporan Jumat jika ada inputnya
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

            // 2. SINKRONISASI KE TAB KEBIASAAN / KEAGAMAAN
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
                     // SINKRONISASI TILAWAH RAMADAN KE ODOA HABIT
                    'odoa_surah'     => $request->tadarus_surah,
                    'odoa_ayat'      => $request->tadarus_ayah,
                ]
            );

            DB::commit();
            return redirect()->back()->with('success', 'Jurnal Ramadhan & Keagamaan berhasil disinkronkan!');

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

        $reports = [];
        if ($selectedClass) {
            $reports = Student::where('class_id', $selectedClass)
                        ->with(['ramadanLogs' => function($query) use ($date) {
                            $query->whereDate('date', $date);
                        }])
                        ->orderBy('name', 'asc')
                        ->get();
        }

        return view('ramadan.admin_report', compact('classes', 'reports', 'selectedClass', 'date'));
    }

    /**
     * [UPDATED] Simpan Feedback / Motivasi Guru (Harian & Jumat)
     * Method ini menangani penilaian umum, tidak terbatas Jumat saja.
     */
    public function verifyFriday(Request $request, $id)
    {
        $log = RamadanLog::findOrFail($id);
        
        $request->validate([
            'teacher_score' => 'required|numeric|min:0|max:100',
            'teacher_note' => 'nullable|string|max:500', // Limit diperbesar untuk motivasi panjang
        ]);

        $log->update([
            'teacher_score' => $request->teacher_score,
            'teacher_note' => $request->teacher_note,
            'teacher_verified_at' => now(),
            'teacher_id' => Auth::id(), 
        ]);

        return back()->with('success', 'Catatan & Motivasi berhasil disimpan.');
    }
}