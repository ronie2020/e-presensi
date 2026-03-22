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
    const RAMADAN_START_DATE = '2026-02-19'; 
    const FILL_START_TIME = '00:00'; 
    const FILL_END_TIME = '23:59';  

     private function getRamadanDay($date)
    {
        $start = Carbon::parse(self::RAMADAN_START_DATE);
        $current = Carbon::parse($date);
        if ($current->lt($start)) return 0;
        return $current->diffInDays($start) + 1;
    }
    
    private function syncStudentPoints($studentId)
    {
        $logs = RamadanLog::where('student_id', $studentId)->get();
        $totalPoints = 0;

        foreach($logs as $log) {           
            if ($log->is_fasting) $totalPoints += 50;                        
            $prayers = is_array($log->prayers) ? array_filter($log->prayers) : [];
            $totalPoints += (count($prayers) * 10);

            // 3. Sunnah 
            $sunnahs = is_array($log->sunnah_deeds) ? $log->sunnah_deeds : [];
            if ($sunnahs['tarawih'] ?? false) $totalPoints += 15;
            if ($sunnahs['witir'] ?? false) $totalPoints += 5;
            if ($sunnahs['dhuha'] ?? false) $totalPoints += 5;
            if ($sunnahs['rawatib'] ?? false) $totalPoints += 5;
            if ($sunnahs['sedekah'] ?? false) $totalPoints += 5;

            // 4. Tilawah, Jumat, Kultum
            if (!empty($log->tadarus_surah)) $totalPoints += 20;
            if (!empty($log->friday_khotib)) $totalPoints += 20;
            if (!empty($log->kultum_summary)) $totalPoints += 15;
        }
        
        Student::where('id', $studentId)->update(['ramadan_points' => $totalPoints]);
    }

    public function studentIndex()
    {
        $studentId = Auth::guard('student')->id();
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $now = Carbon::now('Asia/Jakarta');
        
        $startDate = self::RAMADAN_START_DATE;
        $totalRamadanDays = 30; 

        $calendarLogs = RamadanLog::where('student_id', $studentId)
                            ->pluck('id', 'date') 
                            ->toArray();
               
        $startTime = Carbon::parse($today . ' ' . self::FILL_START_TIME, 'Asia/Jakarta');
        $endTime = Carbon::parse($today . ' ' . self::FILL_END_TIME, 'Asia/Jakarta');
        
        $ramadanDay = $this->getRamadanDay($today);
        $isBeforeRamadan = Carbon::parse($today)->lt(Carbon::parse(self::RAMADAN_START_DATE));

        // === LOGIKA RAMADHAN SELESAI ===
        $isRamadanEnded = $ramadanDay > $totalRamadanDays;

        $canFill = false;
        $topStudents = collect();

        if ($isRamadanEnded) {
            // Jika selesai, kunci form dan ambil Top 3 Siswa
            $canFill = false;
            $topStudents = Student::where('ramadan_points', '>', 0)
                ->orderByDesc('ramadan_points')
                ->take(3)
                ->get();
        } else {
            // Jika belum selesai, cek waktu pengisian
            if ($now->between($startTime, $endTime)) {
                $canFill = true;
            }
        }
     
        $todayRamadanLog = RamadanLog::where('student_id', $studentId)
                            ->whereDate('date', $today)
                            ->first();
       
        $lastVerifiedLog = RamadanLog::where('student_id', $studentId)
                            ->whereNotNull('teacher_verified_at')
                            ->orderBy('date', 'desc')
                            ->first();
        
        return view('ramadan.student_index', compact(
            'todayRamadanLog', 
            'today', 
            'lastVerifiedLog',
            'canFill',
            'ramadanDay',
            'isBeforeRamadan',
            'startDate',
            'calendarLogs',
            'totalRamadanDays',
            'isRamadanEnded',   // Passing ke view
            'topStudents'       // Passing ke view
        ));
    }

     public function leaderboard()
    {
        $topStudents = Student::with('schoolClass')
            ->whereHas('schoolClass')
            ->where('ramadan_points', '>', 0)
            ->orderByDesc('ramadan_points')
            ->take(10)
            ->get();

        $topStudents->map(function($student) {
            $student->points = $student->ramadan_points;
            return $student;
        });

        return view('ramadan.leaderboard', compact('topStudents'));
    }

    public function store(Request $request)
    {
        $studentId = Auth::guard('student')->id();
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $requestDate = $request->input('date', $today);

        // PROTEKSI TAMBAHAN: Tolak simpan jika Ramadhan sudah selesai
        $ramadanDay = $this->getRamadanDay($today);
        if ($ramadanDay > 30) {
            return redirect()->back()->with('error', 'Maaf, waktu pengisian Jurnal Ramadhan tahun ini telah ditutup.');
        }

        if ($requestDate !== $today) {
             return redirect()->back()->with('error', 'Anda hanya dapat mengisi jurnal untuk hari ini.');
        }

        $request->validate([
            'tadarus_surah' => 'nullable|string|max:100',
            'tadarus_ayah' => 'nullable|numeric|max:9999',
            'murojaah_surah' => 'nullable|string|max:100',
            'friday_khotib' => 'nullable|string|max:100',
            'friday_summary' => 'nullable|string|max:1000',
            'kultum_penceramah' => 'nullable|string|max:100',
            'kultum_summary' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $logData = [
                'is_fasting' => $request->boolean('is_fasting'),
                'prayers' => [
                    'subuh'   => $request->boolean('prayer_subuh'),
                    'dzuhur'  => $request->boolean('prayer_dzuhur'),
                    'ashar'   => $request->boolean('prayer_ashar'),
                    'maghrib' => $request->boolean('prayer_maghrib'),
                    'isya'    => $request->boolean('prayer_isya'),
                ],
                'sunnah_deeds' => [
                    'tarawih' => $request->boolean('sunnah_tarawih'),
                    'witir'   => $request->boolean('sunnah_witir'),
                    'dhuha'   => $request->boolean('sunnah_dhuha'),
                    'rawatib' => $request->boolean('sunnah_rawatib'),
                    'sedekah' => $request->boolean('sunnah_sedekah'),
                ],
                'tadarus_surah' => $request->tadarus_surah,
                'tadarus_ayah' => $request->tadarus_ayah,
                'murojaah_surah' => $request->murojaah_surah,        
                'kultum_penceramah' => $request->kultum_penceramah,
                'kultum_summary' => $request->kultum_summary,
            ];

            if ($request->has('friday_khotib')) {
                $logData['friday_khotib'] = $request->friday_khotib;
            }
            if ($request->has('friday_summary')) {
                $logData['friday_summary'] = $request->friday_summary;
            }

            RamadanLog::updateOrCreate(
                ['student_id' => $studentId, 'date' => $requestDate],
                $logData
            );

            StudentHabit::updateOrCreate(
                ['student_id' => $studentId, 'report_date' => $requestDate],
                [
                    'prayer_subuh'   => $request->boolean('prayer_subuh'),
                    'prayer_dzuhur'  => $request->boolean('prayer_dzuhur'),
                    'prayer_ashar'   => $request->boolean('prayer_ashar'),
                    'prayer_maghrib' => $request->boolean('prayer_maghrib'),
                    'prayer_isya'    => $request->boolean('prayer_isya'),
                    'prayer_dhuha'   => $request->boolean('sunnah_dhuha'),
                    'odoa_surah'     => $request->tadarus_surah,
                    'odoa_ayat'      => $request->tadarus_ayah,
                ]
            );

            $this->syncStudentPoints($studentId);

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
            $stats['total_students'] = Student::whereHas('schoolClass')->count();
            
            $logsToday = RamadanLog::whereHas('student.schoolClass')
                        ->whereDate('date', $date)
                        ->get();
            
            $stats['fasting_count'] = $logsToday->where('is_fasting', true)->count();
            
            $stats['prayer_complete_count'] = $logsToday->filter(function($log) {
                $prayers = is_array($log->prayers) ? $log->prayers : [];
                return count(array_filter($prayers)) == 5;
            })->count();
            
            $stats['friday_log_count'] = $logsToday->whereNotNull('friday_khotib')->count();

            $query = RamadanLog::with(['student.schoolClass'])
                ->whereHas('student.schoolClass')
                ->whereDate('date', $date);

            if ($request->status === 'pending') {
                $query->whereNull('teacher_verified_at');
            }

            if ($request->search) {
                $query->whereHas('student', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            }

            $latestLogs = $query->orderBy('updated_at', 'desc')->paginate(12)->withQueryString();
        }

        $stats['percentage_fasting'] = $stats['total_students'] > 0 
            ? round(($stats['fasting_count'] / $stats['total_students']) * 100) 
            : 0;
        
        $startDate = self::RAMADAN_START_DATE; 
        $todayDate = Carbon::now('Asia/Jakarta')->toDateString();

        $topStudentsQuery = Student::with('schoolClass')
            ->whereHas('schoolClass')
            ->withCount(['ramadanLogs' => function ($query) use ($startDate, $todayDate) {
                $query->whereBetween('date', [$startDate, $todayDate]);
            }]);
       
        if ($selectedClass) {
            $topStudentsQuery->where('class_id', $selectedClass);
        }

        $topStudents = $topStudentsQuery->orderByDesc('ramadan_logs_count')
            ->take(3)
            ->get()
            ->map(function($student) {
                $student->total_logs_count = $student->ramadan_logs_count;
                return $student;
            });

        return view('ramadan.admin_report', compact(
            'classes', 
            'reports', 
            'selectedClass', 
            'date', 
            'isFriday', 
            'stats', 
            'latestLogs',
            'topStudents' 
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