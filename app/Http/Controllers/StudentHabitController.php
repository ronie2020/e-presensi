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
     * Dashboard Utama Siswa (Halaman Awal)
     */
    public function dashboard()
    {
        $studentId = Auth::guard('student')->id();
        $today = Carbon::today();
        
        // 1. Cek Status Hari Ini
        $todayEntry = StudentHabit::where('student_id', $studentId)
                        ->where('report_date', $today->format('Y-m-d'))
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

        // 4. Hitung Statistik Sederhana (Contoh: Total "Bangun Pagi" bulan ini)
        // Kita bisa kembangkan ini nanti jadi sistem Poin/XP
        $totalHabitsDone = 0;
        // Logic sederhana: Total hari mengisi * 100 poin (misalnya)
        $totalPoints = StudentHabit::where('student_id', $studentId)->count() * 100;

        return view('habits.student_dashboard', compact(
            'todayEntry', 
            'monthlyCount', 
            'recentActivities',
            'totalPoints'
        ));
    }

    // Halaman Form Input (Yang sebelumnya bernama index)
    // Sebaiknya route-nya disesuaikan, misal /habits/journal
    public function index()
    {
        $studentId = Auth::guard('student')->id();
        $today = Carbon::now()->format('Y-m-d');

        // Cek apakah sudah mengisi hari ini
        $todayEntry = StudentHabit::where('student_id', $studentId)
                        ->where('report_date', $today)
                        ->first();

        // Ambil riwayat bulan ini untuk kalender
        $history = StudentHabit::where('student_id', $studentId)
                        ->whereMonth('report_date', Carbon::now()->month)
                        ->whereYear('report_date', Carbon::now()->year)
                        ->get()
                        ->keyBy(function($item) {
                            return $item->report_date->format('d');
                        });

        return view('habits.student_index', compact('todayEntry', 'history', 'today'));
    }

    // Simpan Laporan
    public function store(Request $request)
    {
        // 1. Validasi dengan Pesan Bahasa Indonesia
        $request->validate([
            'habit_1_time' => 'required',
            'habit_3_activity' => 'nullable|string',
            'habit_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
        ], [
            'habit_1_time.required' => 'Jam bangun pagi wajib diisi ya!',
            'habit_photo.required' => 'Jangan lupa upload bukti foto kegiatanmu hari ini.',
            'habit_photo.image' => 'File yang diupload harus berupa gambar.',
            'habit_photo.max' => 'Ukuran foto terlalu besar (maksimal 5MB).',
        ]);

        $studentId = Auth::guard('student')->id();
        $today = Carbon::now()->format('Y-m-d');

        // 2. Cek Duplikasi (Mencegah Double Submit)
        if (StudentHabit::where('student_id', $studentId)->where('report_date', $today)->exists()) {
            return back()->with('error', 'Kamu sudah mengisi jurnal untuk tanggal ini!');
        }

        try {
            DB::beginTransaction(); // Mulai Transaksi Database

            // 3. Upload Foto
            $photoPath = null;
            if ($request->hasFile('habit_photo')) {
                $file = $request->file('habit_photo');
                // Format nama file: habit_ID_TIMESTAMP.ext
                $filename = 'habit_' . $studentId . '_' . time() . '.' . $file->getClientOriginalExtension();
                $photoPath = $file->storeAs('habits', $filename, 'public');
            }

            // 4. Simpan Data
            StudentHabit::create([
                'student_id' => $studentId,
                'report_date' => $today,
                
                // Habit 1: Bangun & Ibadah
                'habit_1' => $request->has('check_1'),
                'habit_1_time' => $request->habit_1_time,
                'habit_1_note' => $request->habit_1_note,

                // Habit 2: Mandi/Rapi
                'habit_2' => $request->has('check_2'),

                // Habit 3: Olahraga
                'habit_3' => $request->has('check_3'),
                'habit_3_activity' => $request->habit_3_activity,

                // Habit 4: Belajar
                'habit_4' => $request->has('check_4'),
                'habit_4_subject' => $request->habit_4_subject,

                // Habit 5: Makan Sehat
                'habit_5' => $request->has('check_5'),
                'habit_5_menu' => $request->habit_5_menu,

                // Habit 6: Bermasyarakat
                'habit_6' => $request->has('check_6'),
                'habit_6_activity' => $request->habit_6_activity,

                // Habit 7: Tidur Cukup
                'habit_7' => $request->has('check_7'),
                'habit_7_time' => $request->habit_7_time,

                'photo_path' => $photoPath,
                'student_note' => $request->student_note,
            ]);

            DB::commit(); // Simpan permanen jika tidak ada error

            // Redirect ke Dashboard setelah sukses (atau tetap di index)
            // Disini saya arahkan ke dashboard agar siswa melihat progressnya
            return redirect()->route('student.habits.dashboard')->with('success', 'Hebat! Poin kamu bertambah!');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika ada error
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data. Coba lagi ya!')->withInput();
        }
    }
}