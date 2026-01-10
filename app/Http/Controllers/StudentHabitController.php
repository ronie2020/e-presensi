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
     * Halaman Form Jurnal
     */
    public function index()
    {
        $studentId = Auth::guard('student')->id();
        $today = Carbon::now()->format('Y-m-d');

        $todayEntry = StudentHabit::where('student_id', $studentId)
                        ->where('report_date', $today)
                        ->first();

        $history = StudentHabit::where('student_id', $studentId)
                        ->whereMonth('report_date', Carbon::now()->month)
                        ->orderBy('report_date', 'desc')
                        ->get();

        return view('habits.student_index', compact('todayEntry', 'history', 'today'));
    }

    /**
     * Simpan Laporan (LOGIKA PEMETAAN BARU)
     */
    public function store(Request $request)
    {
        $studentId = Auth::guard('student')->id();
        $today = Carbon::now()->format('Y-m-d');

        // Cek data lama untuk validasi foto
        $existingEntry = StudentHabit::where('student_id', $studentId)
                            ->where('report_date', $today)
                            ->first();

        // Foto wajib jika belum pernah upload hari ini
        $photoRule = ($existingEntry && $existingEntry->photo_path) ? 'nullable' : 'required';

        $request->validate([
            'habit_photo' => "$photoRule|image|mimes:jpeg,png,jpg|max:5120",
        ], [
            'habit_photo.required' => 'Bukti foto kolase kegiatan wajib diupload!',
            'habit_photo.max' => 'Ukuran foto maksimal 5MB.',
        ]);

        try {
            DB::beginTransaction();

            // Handle Upload Foto
            $photoPath = $existingEntry ? $existingEntry->photo_path : null;

            if ($request->hasFile('habit_photo')) {
                // Hapus foto lama biar hemat storage
                if ($existingEntry && $existingEntry->photo_path) {
                    if (Storage::disk('public')->exists($existingEntry->photo_path)) {
                        Storage::disk('public')->delete($existingEntry->photo_path);
                    }
                }
                
                $file = $request->file('habit_photo');
                $filename = 'habit_' . $studentId . '_' . time() . '.' . $file->getClientOriginalExtension();
                $photoPath = $file->storeAs('habits', $filename, 'public');
            }

            // SIMPAN DATA (MAPPING INPUT -> DB)
            StudentHabit::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'report_date' => $today
                ],
                [
                    // 1. BANGUN TIDUR, MANDI DAN RAPI
                    'habit_1' => $request->has('check_bangun'), // Input check_bangun -> habit_1
                    'habit_1_time' => $request->habit_1_time,
                    'habit_2' => $request->has('check_mandi'),  // Input check_mandi -> habit_2

                    // 2. SHALAT TEPAT WAKTU (Kolom Baru)
                    'prayer_subuh' => $request->has('prayer_subuh'),
                    'prayer_dhuha' => $request->has('prayer_dhuha'),   // Bisa dari Scanner atau Manual
                    'prayer_dzuhur' => $request->has('prayer_dzuhur'), // Bisa dari Scanner atau Manual
                    'prayer_ashar' => $request->has('prayer_ashar'),
                    'prayer_maghrib' => $request->has('prayer_maghrib'),
                    'prayer_isya' => $request->has('prayer_isya'),

                    // 3. OLAHRAGA
                    'habit_3' => $request->has('check_olahraga'),
                    'habit_3_activity' => $request->habit_3_activity,

                    // 4. MAKAN BERGIZI (Disimpan di habit_5 sesuai DB lama)
                    'habit_5' => $request->has('check_makan'),
                    'habit_5_menu' => $request->habit_5_menu,

                    // 5. GEMAR BELAJAR (Disimpan di habit_4 sesuai DB lama)
                    'habit_4' => $request->has('check_belajar'),
                    'habit_4_subject' => $request->habit_4_subject,

                    // 6. BERMASYARAKAT
                    'habit_6' => $request->has('check_sosial'),
                    'habit_6_activity' => $request->habit_6_activity,

                    // 7. TIDUR CEPAT
                    'habit_7' => $request->has('check_tidur'),
                    'habit_7_time' => $request->habit_7_time,

                    'photo_path' => $photoPath,
                ]
            );

            DB::commit();

            return redirect()->route('student.habits.dashboard')
                   ->with('success', 'Jurnal 7 Kebiasaan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }
}