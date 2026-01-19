<?php

namespace App\Http\Controllers;

use App\Models\StudentHabit;
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
        
        // 1. Cek Status Hari Ini
        $todayEntry = StudentHabit::where('student_id', $studentId)
                        ->whereDate('report_date', $today)
                        ->first();

        // 2. Hitung Laporan Bulan Ini
        $monthlyCount = StudentHabit::where('student_id', $studentId)
                        ->whereMonth('report_date', Carbon::now('Asia/Jakarta')->month)
                        ->whereYear('report_date', Carbon::now('Asia/Jakarta')->year)
                        ->count();

        // 3. Ambil 5 Riwayat Terakhir
        $recentActivities = StudentHabit::where('student_id', $studentId)
                            ->orderBy('report_date', 'desc')
                            ->take(5)
                            ->get();

        // 4. Hitung Poin (Total hari x 100)
        $totalPoints = StudentHabit::where('student_id', $studentId)->count() * 100;

        return view('habits.student_dashboard', compact(
            'todayEntry', 
            'monthlyCount', 
            'recentActivities',
            'totalPoints'
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

        // [LOGIKA UDZUR SYAR'I]
        $isUdzur = $request->has('is_udzur_syar_i');

        // PENGATURAN NILAI SHALAT
        if ($isUdzur) {
            $valSubuh = false;
            $valDhuha = false;
            $valDzuhur = false;
            $valAshar = false;
            $valMaghrib = false;
            $valIsya = false;
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

        // [FIX VALIDASI UNTUK FIREFOX/OGG]
        $request->validate([
            'habit_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            // Tambahkan 'ogg' dan 'weba', hapus 'audio/mpeg' (karena mimes itu extension)
            'odoa_audio'  => 'nullable|file|mimes:mp3,wav,aac,webm,ogg,m4a,weba|max:10240',
            'odoa_surah'  => 'nullable|string|max:100',
            'odoa_ayat'   => 'nullable|string|max:100', // [TAMBAHAN] Validasi ayat agar aman
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
            // AMAN: Urutan array di sini tidak harus sama dengan urutan kolom di MySQL
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
                    'prayer_isya' => $valIsya, // PASTIKAN KOLOM INI ADA DI DB
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