<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // <-- DIPERBARUI: Tambahkan Request
use App\Models\Student;
use App\Models\AttendanceSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendWaManualNotificationJob;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman Laporan Rekapitulasi Harian.
     * Termasuk daftar siswa Sakit, Izin, Alpa, dan Belum Absen.
     */
    public function dailyReport(Request $request) // <-- DIPERBARUI: Tambahkan Request $request
    {
        // 1. TENTUKAN TANGGAL
        // Validasi jika ada input tanggal
        $request->validate(['date' => 'nullable|date']);
        // Ambil tanggal dari request, jika tidak ada, gunakan hari ini
        $selectedDate = $request->has('date') ? Carbon::parse($request->date) : Carbon::today();


        // 2. Ambil SEMUA siswa aktif (untuk dropdown dan perbandingan)
        $allStudents = Student::with('schoolClass')
            ->select('students.*') // Menghindari konflik kolom 'id'
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->orderBy('classes.name', 'asc') // Urutkan berdasarkan NAMA KELAS
            ->orderBy('students.name', 'asc')       // Lalu urutkan berdasarkan NAMA SISWA
            ->get();

        // 3. Ambil SEMUA data absensi PADA TANGGAL YANG DIPILIH
        $todayAttendances = AttendanceSiswa::with('student.schoolClass')
            ->where('attendance_date', $selectedDate)
            ->orderBy('created_at', 'asc') // Urutkan berdasarkan waktu input
            ->get();

        // 4. Pisahkan data absensi berdasarkan status
        $sakitList = $todayAttendances->where('status', 'Sakit');
        $izinList = $todayAttendances->where('status', 'Izin');
        $alfaList = $todayAttendances->where('status', 'Alfa');

        // 5. Dapatkan daftar ID siswa yang sudah memiliki data absensi (Hadir, Sakit, Izin, Alpa)
        // Kita hanya cek absensi 'Harian'
        $attendedIds = $todayAttendances->where('type', 'Harian')->pluck('student_id');

        // 6. Filter daftar SEMUA siswa untuk menemukan yang "Belum Absen"
        $belumAbsenList = $allStudents->whereNotIn('id', $attendedIds);

        // 7. Kirim semua data ke view
        return view('reports.daily', [
            'sakitList' => $sakitList,
            'izinList' => $izinList,
            'alfaList' => $alfaList,
            'belumAbsenList' => $belumAbsenList,
            'allStudents' => $allStudents,
            'todayAttendances' => $todayAttendances, // <-- BARU: Kirim semua absensi hari ini ke view
            'selectedDate_db' => $selectedDate,       // <-- DIPERBAIKI: Typo _db sudah diperbaiki
        ]);
    }

    /**
     * Menyimpan data absensi yang diinput secara manual (Sakit, Izin, Alpa).
     */
    public function storeManualEntry(Request $request)
    {
        // 1. Validasi data dari form
        $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'status' => 'required|string|in:Sakit,Izin,Alfa,Hadir', // Validasi 'Hadir'
            'notes' => 'nullable|string|max:255',
            'time_in' => 'nullable|date_format:H:i', // Validasi Waktu Masuk dari modal
            'time_out' => 'nullable|date_format:H:i', // Validasi Waktu Pulang dari modal
        ]);

        $today = Carbon::today();
        
        // 2. LOGIKA DIPERBARUI: Gunakan updateOrCreate
        //    Cari absensi 'Harian' siswa hari ini.
        
        // Tentukan Waktu Masuk:
        // Jika form mengirim 'time_in', gunakan itu.
        // Jika tidak (dari form Rekap Harian lama), gunakan jam sekarang.
        $timeNow = Carbon::now()->toTimeString();
        $timeIn = $request->input('time_in', $timeNow);

        // Jika statusnya 'Hadir' tapi time_in tidak ada, set ke jam sekarang
        if ($request->status == 'Hadir' && !$request->has('time_in')) {
             $timeIn = $timeNow;
        }

        $attendance = AttendanceSiswa::updateOrCreate(
            [
                // Kunci untuk mencari
                'student_id' => $request->student_id,
                'attendance_date' => $today,
                'type' => 'Harian' 
            ],
            [
                // Data untuk di-update atau dibuat
                'status' => $request->status, // Status dari form (Hadir, Sakit, Izin, Alfa)
                'time_in' => $timeIn, // Waktu masuk (dari modal atau jam sekarang)
                'time_out' => $request->input('time_out'), // Waktu pulang (dari modal)
                'notes' => 'Diinput manual oleh: ' . Auth::user()->name . '. ' . $request->notes,
            ]
        );

        // 3. PANGGIL JOB UNTUK KIRIM WA
        // (Ini tetap berjalan, baik saat create maupun update)
        SendWaManualNotificationJob::dispatch($attendance);

        // 4. Redirect kembali dengan pesan sukses
        //    DIPERBARUI: Redirect kembali ke halaman sebelumnya (bisa dari Manajamen Siswa atau Rekap Harian)
        return redirect()->back()
            ->with('success', 'Absensi (' . $request->status . ') untuk siswa berhasil disimpan/diperbarui.');
    }
}