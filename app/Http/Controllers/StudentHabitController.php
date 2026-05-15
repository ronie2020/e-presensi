<?php

namespace App\Http\Controllers;

use App\Models\StudentHabit;
use App\Models\RamadanLog;
use App\Models\AttendanceSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StudentHabitController extends Controller
{
    /**
     * Dashboard Utama Siswa
     */
    public function dashboard()
    {
        $studentId = Auth::guard('student')->id();
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        
        // --- DATA HABIT ---
        $todayEntry = StudentHabit::where('student_id', $studentId)->whereDate('report_date', $today)->first();
        $monthlyCount = StudentHabit::where('student_id', $studentId)->whereMonth('report_date', Carbon::now()->month)->count();
        $recentActivities = StudentHabit::where('student_id', $studentId)->orderBy('report_date', 'desc')->take(5)->get();
        
        // Poin Habit: Misal 1 hari lengkap = 10 Poin, draft = 5 poin (Sesuaikan dengan logika Anda)
        $totalHabitsPoints = StudentHabit::where('student_id', $studentId)->count() * 10; 

        // --- DATA LITERASI ---
        // (Pastikan Anda sudah 'use App\Models\LiteracyJournal;')
        $literacy_journals = \App\Models\LiteracyJournal::where('student_id', $studentId)->orderBy('created_at', 'desc')->get();
        
        $totalPages = $literacy_journals->where('verified_at', '!=', null)->sum('pages_read');
        $totalBooks = $literacy_journals->count();
        
        // Poin Literasi: Misal 1 halaman baca = 2 Poin
        $literacyPoints = $totalPages * 2;

        $literacy_stats = [
            'points' => $literacyPoints,
            'total_books' => $totalBooks,
            'total_pages' => $totalPages,
            // Logika target progres literasi bisa ditaruh di sini
            'progress' => min(($literacyPoints / 1000) * 100, 100), 
            'next_target' => 1000
        ];

        // Total Poin Keseluruhan untuk view
        $totalPoints = $totalHabitsPoints; 

        return view('habits.student_dashboard', compact(
            'todayEntry', 
            'monthlyCount', 
            'recentActivities',
            'totalPoints',
            'literacy_journals',
            'literacy_stats'
        ));
    }

    /**
     * Halaman Input Jurnal
     */
    public function index()
    {
        $studentId = Auth::guard('student')->id();
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $todayEntry = StudentHabit::where('student_id', $studentId)
                        ->whereDate('report_date', $today)
                        ->first();

        // [HYBRID SYSTEM] Cek Absensi Sekolah
        $schoolDhuha = AttendanceSiswa::where('student_id', $studentId)
                        ->whereDate('attendance_date', $today)
                        ->where('type', 'Keagamaan') 
                        ->where('activity', 'Dhuha')
                        ->where('status', 'Hadir')
                        ->exists();

        $schoolDzuhur = AttendanceSiswa::where('student_id', $studentId)
                        ->whereDate('attendance_date', $today)
                        ->where('type', 'Keagamaan')
                        ->where('activity', 'Dhuhur')
                        ->where('status', 'Hadir')
                        ->exists();

        $schoolMbgMenu = null; 
        
        return view('habits.student_index', compact(
            'todayEntry', 
            'schoolDhuha', 
            'schoolDzuhur',
            'schoolMbgMenu'
        ));
    }

    /**
     * Simpan Jurnal
     */
    public function store(Request $request)
    {        
        $studentId = Auth::guard('student')->id();
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        
        $existingEntry = StudentHabit::where('student_id', $studentId)
                            ->where('report_date', $today)
                            ->first();

        // --- A. LOGIKA HYBRID ---
        $schoolDhuha = AttendanceSiswa::where('student_id', $studentId)
                        ->whereDate('attendance_date', $today) 
                        ->where('type', 'Keagamaan')
                        ->where('activity', 'Dhuha')
                        ->where('status', 'Hadir')
                        ->exists();

        $schoolDzuhur = AttendanceSiswa::where('student_id', $studentId)
                        ->whereDate('attendance_date', $today)
                        ->where('type', 'Keagamaan')
                        ->where('activity', 'Dhuhur')
                        ->where('status', 'Hadir')
                        ->exists();

        $isUdzur = $request->has('is_udzur_syar_i');

        if ($isUdzur) {
            $valSubuh = $valDhuha = $valDzuhur = $valAshar = $valMaghrib = $valIsya = false;
        } else {
            $valSubuh   = $request->has('prayer_subuh');
            $valDhuha   = $schoolDhuha ? true : $request->has('prayer_dhuha'); 
            $valDzuhur  = $schoolDzuhur ? true : $request->has('prayer_dzuhur');
            $valAshar   = $request->has('prayer_ashar');
            $valMaghrib = $request->has('prayer_maghrib');
            $valIsya    = $request->has('prayer_isya');
        }

        $valMakan = $request->has('habit_5'); 
        $valMenuMakan = $request->habit_5_menu;
     
        $request->validate([
            'habit_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',    
            'odoa_audio'  => 'nullable|file|mimes:mp3,wav,aac,webm,ogg,m4a,weba|max:10240',
            'odoa_surah'  => 'nullable|string|max:100',
            'odoa_ayat'   => 'nullable|string|max:100', 
            'habit_1_time' => 'nullable',
            'habit_7_time' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            
            // --- B. HANDLE FILES ---
            $photoPath = $existingEntry ? $existingEntry->photo_path : null;
            if ($request->hasFile('habit_photo')) {
                if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }
                $file = $request->file('habit_photo');
                $filename = 'habit_' . $studentId . '_' . time() . '.' . $file->getClientOriginalExtension();
                $photoPath = $file->storeAs('habits', $filename, 'public');
            }

            $audioPath = $existingEntry ? $existingEntry->odoa_audio_path : null;
            if ($request->hasFile('odoa_audio')) {
                if ($audioPath && Storage::disk('public')->exists($audioPath)) {
                    Storage::disk('public')->delete($audioPath);
                }
                $audioFile = $request->file('odoa_audio');
                
                // Gunakan extension asli dari browser jika ada, atau fallback ke ogg/webm
                $ext = $audioFile->getClientOriginalExtension();
                if(empty($ext) || $ext == 'bin') {
                    // Deteksi manual sederhana jika extension hilang
                    $mime = $audioFile->getMimeType();
                    if(str_contains($mime, 'ogg')) $ext = 'ogg';
                    elseif(str_contains($mime, 'wav')) $ext = 'wav';
                    else $ext = 'webm';
                }

                $audioFilename = 'odoa_' . $studentId . '_' . time() . '.' . $ext;
                $audioPath = $audioFile->storeAs('habits/audio', $audioFilename, 'public');
            }

            // --- C. SIMPAN KE DATABASE ---            
            $habit = StudentHabit::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'report_date' => $today
                ],
                [
                    'is_udzur_syar_i' => $isUdzur,
                    'habit_1' => $request->has('habit_1'),
                    'habit_1_time' => $request->habit_1_time,
                    'habit_2' => $request->has('habit_2'),
                    'prayer_subuh' => $valSubuh,
                    'prayer_dhuha' => $valDhuha,
                    'prayer_dzuhur' => $valDzuhur,
                    'prayer_ashar' => $valAshar,
                    'prayer_maghrib' => $valMaghrib,
                    'prayer_isya' => $valIsya, 
                    'odoa_surah' => $request->odoa_surah,
                    'odoa_ayat' => $request->odoa_ayat,
                    'odoa_audio_path' => $audioPath,
                    'habit_3' => $request->has('habit_3'),
                    'habit_3_activity' => $request->habit_3_activity,
                    'habit_5' => $valMakan,
                    'habit_5_menu' => $valMenuMakan,
                    'habit_4' => $request->has('habit_4'),
                    'habit_4_subject' => $request->habit_4_subject,
                    'habit_6' => $request->has('habit_6'),
                    'habit_6_activity' => $request->habit_6_activity,
                    'habit_7' => $request->has('habit_7'),
                    'habit_7_time' => $request->habit_7_time,
                    'photo_path' => $photoPath,
                ]
            );

           // 2. SINKRONISASI KE RAMADAN (Jika sedang bulan Ramadan)
            $ramadanLog = RamadanLog::where('student_id', $studentId)->whereDate('date', $today)->first();
            
            // Format tilawah/tadarus dari input ODOA Kebiasaan
            $tadarusText = null;
            if ($request->odoa_surah) {
                $tadarusText = $request->odoa_surah;
                if ($request->odoa_ayat) {
                    $tadarusText .= ' Ayat ' . $request->odoa_ayat;
                }
            }

            RamadanLog::updateOrCreate(
                ['student_id' => $studentId, 'date' => $today],
                [                    
                    'is_fasting' => $isUdzur ? false : ($ramadanLog->is_fasting ?? true),
                    'prayers' => [
                        'subuh'   => $valSubuh,
                        'dzuhur'  => $valDzuhur,
                        'ashar'   => $valAshar,
                        'maghrib' => $valMaghrib,
                        'isya'    => $valIsya,
                    ],                    
                    'sunnah_deeds' => array_merge($ramadanLog->sunnah_deeds ?? [], [
                        'dhuha' => $valDhuha
                    ]),
                    'tadarus_surah' => $tadarusText ?: ($ramadanLog->tadarus_surah ?? null)
                ]
            );

            DB::commit();

            // --- D. LOGIKA GAMIFIKASI ---
            $hasPhoto = !empty($habit->photo_path);
            
            $prayerCondition = ($habit->prayer_subuh || $habit->prayer_dhuha || $habit->prayer_dzuhur || 
                                $habit->prayer_ashar || $habit->prayer_maghrib || $habit->prayer_isya);
            
            if ($habit->is_udzur_syar_i) {
                $prayerCondition = true;
            }

            $isComplete = $habit->habit_1 &&
                          $habit->habit_2 &&
                          $prayerCondition &&
                          $habit->habit_3 &&
                          $habit->habit_5 &&
                          $habit->habit_4 &&
                          $habit->habit_6 &&
                          $habit->habit_7 &&
                          $hasPhoto;

            if ($isComplete) {
                $message = 'Jurnal harian LENGKAP! Hebat, pertahankan kebiasaan baikmu.';
            } else {
                $message = 'Tersimpan sebagai DRAF. Jangan lupa lengkapi data yang belum diisi ya.';
            }

            return redirect()->route('student.habits.dashboard')
                   ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal Menyimpan: ' . $e->getMessage());
        }
    }
}