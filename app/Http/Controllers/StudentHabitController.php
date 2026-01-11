<?php

namespace App\Http\Controllers;

use App\Models\StudentHabit;
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
        $today = Carbon::today();
        
        // 1. Cek Status Hari Ini
        $todayEntry = StudentHabit::where('student_id', $studentId)
                        ->whereDate('report_date', $today)
                        ->first();

        // 2. Hitung Laporan Bulan Ini
        $monthlyCount = StudentHabit::where('student_id', $studentId)
                        ->whereMonth('report_date', $today->month)
                        ->whereYear('report_date', $today->year)
                        ->count();

        // 3. Ambil 5 Riwayat Terakhir
        $recentActivities = StudentHabit::where('student_id', $studentId)
                            ->orderBy('report_date', 'desc')
                            ->take(5)
                            ->get();

        // 4. Hitung Poin (Contoh: Jumlah hari lapor x 100)
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
        $today = Carbon::today();

        $todayEntry = StudentHabit::where('student_id', $studentId)
                        ->whereDate('report_date', $today)
                        ->first();

        return view('habits.student_index', compact('todayEntry'));
    }

    /**
     * Simpan Jurnal (Mendukung Simpan Sebagian / Draf)
     */
    public function store(Request $request)
    {
        $studentId = Auth::guard('student')->id();
        $today = Carbon::today()->toDateString();

        // 1. Cek Data Lama
        $existingEntry = StudentHabit::where('student_id', $studentId)
                            ->where('report_date', $today)
                            ->first();

        // VALIDASI: Diubah jadi 'nullable' semua agar bisa simpan DRAF (sebagian)
        $request->validate([
            'habit_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'odoa_audio'  => 'nullable|file|mimes:audio/mpeg,mpga,mp3,wav,aac,webm|max:10240',
            'odoa_surah'  => 'nullable|string|max:100',
            'odoa_ayat'   => 'nullable|string|max:50',
            // Validasi waktu opsional
            'habit_1_time' => 'nullable',
            'habit_7_time' => 'nullable',
        ], [
            'habit_photo.image' => 'File bukti harus berupa gambar.',
            'habit_photo.max' => 'Ukuran foto maksimal 5MB.',
            'odoa_audio.max' => 'Ukuran rekaman maksimal 10MB.',
        ]);

        try {
            DB::beginTransaction();

            // --- A. HANDLE FOTO KEGIATAN ---
            // Gunakan foto lama jika tidak ada upload baru
            $photoPath = $existingEntry ? $existingEntry->photo_path : null;
            
            if ($request->hasFile('habit_photo')) {
                // Hapus foto lama fisik jika ada
                if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }
                
                $file = $request->file('habit_photo');
                $filename = 'habit_' . $studentId . '_' . time() . '.' . $file->getClientOriginalExtension();
                $photoPath = $file->storeAs('habits', $filename, 'public');
            }

            // --- B. HANDLE AUDIO ODOA ---
            // Gunakan audio lama jika tidak ada upload baru
            $audioPath = $existingEntry ? $existingEntry->odoa_audio_path : null;

            if ($request->hasFile('odoa_audio')) {
                // Hapus audio lama fisik jika ada
                if ($audioPath && Storage::disk('public')->exists($audioPath)) {
                    Storage::disk('public')->delete($audioPath);
                }
                
                $audioFile = $request->file('odoa_audio');
                $ext = $audioFile->getClientOriginalExtension() ?: 'webm'; 
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
                    // 1. BANGUN PAGI
                    'habit_1' => $request->has('check_bangun'),
                    'habit_1_time' => $request->habit_1_time,

                    // 2. MANDI
                    'habit_2' => $request->has('check_mandi'),

                    // 3. IBADAH (SHALAT)
                    'prayer_subuh' => $request->has('prayer_subuh'),
                    'prayer_dhuha' => $request->has('prayer_dhuha'),
                    'prayer_dzuhur' => $request->has('prayer_dzuhur'),
                    'prayer_ashar' => $request->has('prayer_ashar'),
                    'prayer_maghrib' => $request->has('prayer_maghrib'),
                    'prayer_isya' => $request->has('prayer_isya'),

                    // 4. ODOA (AUDIO & TEXT)
                    'odoa_surah' => $request->odoa_surah,
                    'odoa_ayat' => $request->odoa_ayat,
                    'odoa_audio_path' => $audioPath,

                    // 5. OLAHRAGA
                    'habit_3' => $request->has('check_olahraga'),
                    'habit_3_activity' => $request->habit_3_activity,

                    // 6. MAKAN BERGIZI
                    'habit_5' => $request->has('check_makan'),
                    'habit_5_menu' => $request->habit_5_menu,

                    // 7. GEMAR BELAJAR
                    'habit_4' => $request->has('check_belajar'),
                    'habit_4_subject' => $request->habit_4_subject,

                    // 8. BERMASYARAKAT
                    'habit_6' => $request->has('check_sosial'),
                    'habit_6_activity' => $request->habit_6_activity,

                    // 9. TIDUR CEPAT
                    'habit_7' => $request->has('check_tidur'),
                    'habit_7_time' => $request->habit_7_time,

                    'photo_path' => $photoPath,
                ]
            );

            DB::commit();

            // --- D. LOGIKA PESAN DRAF VS LENGKAP ---
            // Cek apakah semua komponen utama sudah terisi?
            // Kriteria Lengkap: Ada Foto + Checklist Habit Utama (Minimal 7 Aspek)
            
            $hasPhoto = !empty($habit->photo_path);
            
            // Cek Shalat (Minimal 1 shalat terisi dianggap progres ibadah)
            $hasPrayer = $habit->prayer_subuh || $habit->prayer_dhuha || $habit->prayer_dzuhur || 
                         $habit->prayer_ashar || $habit->prayer_maghrib || $habit->prayer_isya;

            $isComplete = $habit->habit_1 && // Bangun
                          $habit->habit_2 && // Mandi
                          $hasPrayer &&      // Shalat
                          $habit->habit_3 && // Olahraga
                          $habit->habit_5 && // Makan
                          $habit->habit_4 && // Belajar
                          $habit->habit_6 && // Sosial
                          $habit->habit_7 && // Tidur
                          $hasPhoto;         // Foto

            if ($isComplete) {
                $message = 'Jurnal harian LENGKAP! Hebat, pertahankan kebiasaan baikmu.';
            } else {
                $missing = [];
                if (!$hasPhoto) $missing[] = 'Foto Bukti';
                if (!$habit->habit_1) $missing[] = 'Bangun Pagi';
                
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