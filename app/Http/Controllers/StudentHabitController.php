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

        // 4. Hitung Statistik Poin (Logic disamakan dengan controller lama)
        $totalPoints = StudentHabit::where('student_id', $studentId)->count() * 100;

        return view('habits.student_dashboard', compact(
            'todayEntry', 
            'monthlyCount', 
            'recentActivities',
            'totalPoints'
        ));
    }

    /**
     * Halaman Form Jurnal (Index)
     */
    public function index()
    {
        $studentId = Auth::guard('student')->id();
        $today = Carbon::now()->format('Y-m-d');

        // Cek apakah sudah mengisi hari ini (untuk ditampilkan kembali di form)
        $todayEntry = StudentHabit::where('student_id', $studentId)
                        ->where('report_date', $today)
                        ->first();

        // Ambil riwayat bulan ini
        $history = StudentHabit::where('student_id', $studentId)
                        ->whereMonth('report_date', Carbon::now()->month)
                        ->whereYear('report_date', Carbon::now()->year)
                        ->orderBy('report_date', 'desc')
                        ->get();

        return view('habits.student_index', compact('todayEntry', 'history', 'today'));
    }

    /**
     * Simpan atau Update Laporan (LOGIKA BARU)
     */
    public function store(Request $request)
    {
        $studentId = Auth::guard('student')->id();
        $today = Carbon::now()->format('Y-m-d');

        // 1. Cek apakah data hari ini sudah ada?
        $existingEntry = StudentHabit::where('student_id', $studentId)
                            ->where('report_date', $today)
                            ->first();

        // 2. Validasi Dinamis
        // Jika belum pernah upload foto hari ini, maka WAJIB. Jika sudah ada, jadi OPTIONAL (nullable).
        $photoRule = ($existingEntry && $existingEntry->photo_path) ? 'nullable' : 'required';

        $request->validate([
            'habit_1_time' => 'nullable', // Boleh kosong jika belum bangun/belum diisi
            'habit_photo' => "$photoRule|image|mimes:jpeg,png,jpg|max:5120",
        ], [
            'habit_photo.required' => 'Bukti foto kegiatan wajib diupload pertama kali ya!',
            'habit_photo.image' => 'File harus berupa gambar.',
            'habit_photo.max' => 'Ukuran foto maksimal 5MB.',
        ]);

        try {
            DB::beginTransaction();

            // 3. Handle Upload Foto
            $photoPath = $existingEntry ? $existingEntry->photo_path : null;

            if ($request->hasFile('habit_photo')) {
                // Hapus foto lama jika ada (untuk menghemat storage)
                if ($existingEntry && $existingEntry->photo_path) {
                    if (Storage::disk('public')->exists($existingEntry->photo_path)) {
                        Storage::disk('public')->delete($existingEntry->photo_path);
                    }
                }

                // Upload foto baru
                $file = $request->file('habit_photo');
                $filename = 'habit_' . $studentId . '_' . time() . '.' . $file->getClientOriginalExtension();
                $photoPath = $file->storeAs('habits', $filename, 'public');
            }

            // 4. Update Or Create (Kunci Perubahan)
            // Ini memungkinkan data di-"cicil" (incremental update)
            StudentHabit::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'report_date' => $today
                ],
                [
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
                    // 'student_note' => $request->student_note, // Jika ada di form
                ]
            );

            DB::commit();

            // Pesan sukses berbeda tergantung update atau baru
            $message = $existingEntry ? 'Data jurnal berhasil diperbarui!' : 'Hebat! Jurnal pertamamu hari ini berhasil disimpan.';

            return redirect()->route('student.habits.dashboard')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            // Hapus foto yang terlanjur ke-upload jika DB gagal (optional, good practice)
            // ... logic delete ...
            
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }
}