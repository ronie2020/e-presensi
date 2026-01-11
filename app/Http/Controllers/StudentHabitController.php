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
        // Jika sedang udzur, maka input shalat diabaikan (tetap false)
        // Namun flag is_udzur_syar_i harus disimpan.
        $isUdzur = $request->has('is_udzur_syar_i');

        // PENGATURAN NILAI SHALAT
        if ($isUdzur) {
            // Jika Udzur, semua shalat otomatis false (tidak wajib)
            $valSubuh = false;
            $valDhuha = false;
            $valDzuhur = false;
            $valAshar = false;
            $valMaghrib = false;
            $valIsya = false;
        } else {
            // Jika TIDAK Udzur, ambil dari input atau data sekolah
            $valSubuh   = $request->has('prayer_subuh');
            $valDhuha   = $schoolDhuha ? true : $request->has('prayer_dhuha'); 
            $valDzuhur  = $schoolDzuhur ? true : $request->has('prayer_dzuhur');
            $valAshar   = $request->has('prayer_ashar');
            $valMaghrib = $request->has('prayer_maghrib');
            $valIsya    = $request->has('prayer_isya');
        }

        // [FIX VARIABLE NAMES] Menggunakan habit_5 sesuai database
        $valMakan = $request->has('habit_5'); 
        $valMenuMakan = $request->habit_5_menu;

        $request->validate([
            'habit_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'odoa_audio'  => 'nullable|file|mimes:audio/mpeg,mpga,mp3,wav,aac,webm|max:10240',
            'odoa_surah'  => 'nullable|string|max:100',
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
                $ext = $audioFile->getClientOriginalExtension() ?: 'webm'; 
                $audioFilename = 'odoa_' . $studentId . '_' . time() . '.' . $ext;
                $audioPath = $audioFile->storeAs('habits/audio', $audioFilename, 'public');
            }

            // --- C. SIMPAN KE DATABASE (FIXED NAMES) ---
            // Kita gunakan $request->has('habit_X') karena checkbox HTML tidak kirim value jika unchecked
            $habit = StudentHabit::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'report_date' => $today
                ],
                [
                    // FLAG UDZUR
                    'is_udzur_syar_i' => $isUdzur,

                    // 1. BANGUN PAGI
                    'habit_1' => $request->has('habit_1'), // Fixed from check_bangun
                    'habit_1_time' => $request->habit_1_time,

                    // 2. MANDI
                    'habit_2' => $request->has('habit_2'), // Fixed from check_mandi

                    // 3. SHALAT
                    'prayer_subuh' => $valSubuh,
                    'prayer_dhuha' => $valDhuha,
                    'prayer_dzuhur' => $valDzuhur,
                    'prayer_ashar' => $valAshar,
                    'prayer_maghrib' => $valMaghrib,
                    'prayer_isya' => $valIsya,

                    // 4. ODOA
                    'odoa_surah' => $request->odoa_surah,
                    'odoa_ayat' => $request->odoa_ayat,
                    'odoa_audio_path' => $audioPath,

                    // 5. OLAHRAGA
                    'habit_3' => $request->has('habit_3'), // Fixed from check_olahraga
                    'habit_3_activity' => $request->habit_3_activity,

                    // 6. MAKAN BERGIZI
                    'habit_5' => $valMakan, // Fixed from check_makan
                    'habit_5_menu' => $valMenuMakan,

                    // 7. BELAJAR
                    'habit_4' => $request->has('habit_4'), // Fixed from check_belajar
                    'habit_4_subject' => $request->habit_4_subject,

                    // 8. SOSIAL
                    'habit_6' => $request->has('habit_6'), // Fixed from check_sosial
                    'habit_6_activity' => $request->habit_6_activity,

                    // 9. TIDUR
                    'habit_7' => $request->has('habit_7'), // Fixed from check_tidur
                    'habit_7_time' => $request->habit_7_time,

                    'photo_path' => $photoPath,
                ]
            );

            DB::commit();

            // --- D. LOGIKA GAMIFIKASI (FIXED FOR UDZUR) ---
            $hasPhoto = !empty($habit->photo_path);
            
            // Cek Shalat: Selesai jika (ada minimal 1 shalat) ATAU (sedang udzur)
            $prayerCondition = ($habit->prayer_subuh || $habit->prayer_dhuha || $habit->prayer_dzuhur || 
                                $habit->prayer_ashar || $habit->prayer_maghrib || $habit->prayer_isya);
            
            if ($habit->is_udzur_syar_i) {
                $prayerCondition = true; // Bypass cek shalat jika udzur
            }

            $isComplete = $habit->habit_1 && // Bangun
                          $habit->habit_2 && // Mandi
                          $prayerCondition && // Shalat/Udzur
                          $habit->habit_3 && // Olahraga
                          $habit->habit_5 && // Makan
                          $habit->habit_4 && // Belajar
                          $habit->habit_6 && // Sosial
                          $habit->habit_7 && // Tidur
                          $hasPhoto;         // Foto

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