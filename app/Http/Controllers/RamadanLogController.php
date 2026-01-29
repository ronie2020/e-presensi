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
     */
    public function studentIndex()
    {
        $studentId = Auth::guard('student')->id();
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $todayLog = RamadanLog::where('student_id', $studentId)->whereDate('date', $today)->first();

        return view('ramadan.student_index', compact('todayLog', 'today'));
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
            $ramadanLog = RamadanLog::updateOrCreate(
                ['student_id' => $studentId, 'date' => $date],
                [
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
                ]
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
}