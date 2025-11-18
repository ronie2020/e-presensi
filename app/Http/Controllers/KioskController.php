<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AttendanceSiswa;
// 1. TAMBAHKAN MODEL JADWAL
use App\Models\ScheduleRegular;
use App\Models\ScheduleSpecial;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendWaScanNotificationJob; // Kita panggil Job notifikasi WA

class KioskController extends Controller
{
    /**
     * Menampilkan halaman Kiosk Scanner.
     */
    public function showKiosk()
    {
        // Layout 'kiosk-layout' adalah layout baru yang akan kita buat
        return view('kiosk.index');
    }

    /**
     * Memproses data scan yang masuk dari Kiosk (via input RFID/Keyboard).
     *
     * === LOGIKA DIPERBARUI ===
     * Menerapkan Time Windows (Jendela Waktu) untuk Masuk dan Pulang.
     */
    public function processKioskScan(Request $request)
    {
        $request->validate([
            'scan_data' => 'required|string', // Ini adalah ID dari QR Code atau RFID
        ]);

        $studentIdFromScan = $request->scan_data;
        // $scanType = 'Harian'; // Asumsi Kiosk hanya untuk absensi Harian (Kita akan tentukan di bawah)
        
        $now = Carbon::now();
        $today = $now->toDateString();
        $timeNow = $now->toTimeString(); // Format HH:MM:SS
        $dayOfWeek = $now->dayOfWeek; // 1=Senin, 5=Jumat

        // 1. Cari siswa berdasarkan student_id (NISN) ATAU rfid_id
        $student = Student::where('student_id', $studentIdFromScan)
                            ->orWhere('rfid_id', $studentIdFromScan)
                            ->first();
        
        // JIKA SISWA TIDAK DITEMUKAN
        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa Tidak Ditemukan',
                'student_name' => 'N/A'
            ], 404);
        }

        // 2. TENTUKAN JADWAL HARI INI (LOGIKA BARU)
        $schedule = $this->getTodaysSchedule($now);

        // JIKA TIDAK ADA JADWAL (Libur, Minggu, atau belum diset)
        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hari Libur / Tidak Ada Jadwal',
                'student_name' => $student->name
            ], 400); // 400 = Bad Request
        }

        // 3. TENTUKAN WAKTU SCAN (MASUK / PULANG / DITOLAK)
        // (Logika ini disamakan dengan AttendanceSiswaController)
        
        $attendanceType = null; // Tipe akan jadi 'Masuk' atau 'Pulang'
        $finalNotes = '';
        $finalStatus = 'Hadir';

        // A. Cek Jam Masuk (DINAMIS DARI DATABASE)
        // $isMasukTime = ($timeNow >= '06:00:00' && $timeNow <= '07:00:00');
        $isMasukTime = ($timeNow >= $schedule->start_in && $timeNow <= $schedule->end_in);
        
        // B. Cek Jam Pulang (DINAMIS DARI DATABASE)
        /* $isPulangTime = false;
        if ($dayOfWeek == 5) { // Jumat: 11.00 - 13.00
            $isPulangTime = ($timeNow >= '11:00:00' && $timeNow <= '13:00:00');
        } elseif ($dayOfWeek >= 1 && $dayOfWeek <= 4) { // Senin-Kamis: 14.00 - 16.00
            $isPulangTime = ($timeNow >= '14:00:00' && $timeNow <= '16:00:00');
        }
        */
        $isPulangTime = ($timeNow >= $schedule->start_out && $timeNow <= $schedule->end_out);


        // C. Eksekusi Logika Berdasarkan Waktu
        if ($isMasukTime) {
            $attendanceType = 'Masuk';
            
            // Logika cek keterlambatan
            if ($timeNow > $schedule->end_in) {
                $endTime = Carbon::parse($schedule->end_in);
                $minutesLate = $now->diffInMinutes($endTime);
                $finalNotes = 'Terlambat ' . $minutesLate . ' menit';
            } else {
                $finalNotes = 'Masuk Tepat Waktu';
            }
            
            // Cek apakah sudah absen MASUK hari ini
            $alreadyScanned = AttendanceSiswa::where('student_id', $student->id)
                ->where('attendance_date', $today)
                ->where('type', 'Masuk') // Cek spesifik tipe Masuk
                ->first();
            
            if ($alreadyScanned) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sudah Absen Masuk (' . \Carbon\Carbon::parse($alreadyScanned->time_in)->format('H:i') . ')',
                    'student_name' => $student->name
                ], 409); // 409 = Conflict
            }

        } elseif ($isPulangTime) {
            $attendanceType = 'Pulang';
            $finalNotes = 'Pulang Sekolah';
            
            // Cek apakah sudah absen PULANG hari ini
            $alreadyScanned = AttendanceSiswa::where('student_id', $student->id)
                ->where('attendance_date', $today)
                ->where('type', 'Pulang')
                ->first();

            if ($alreadyScanned) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sudah Absen Pulang (' . \Carbon\Carbon::parse($alreadyScanned->time_in)->format('H:i') . ')',
                    'student_name' => $student->name
                ], 409);
            }

        } else {
            // D. Jika Scan DI LUAR Jam Masuk maupun Jam Pulang
            return response()->json([
                'status' => 'error',
                'message' => 'Di Luar Jam Absen',
                'student_name' => $student->name
            ], 400); // 400 = Bad Request
        }
        
        // 3. SIMPAN DATA ABSENSI BARU
        try {
            $newScan = AttendanceSiswa::create([
                'student_id' => $student->id,
                'attendance_date' => $today,
                'type' => $attendanceType, // Disimpan sebagai 'Masuk' atau 'Pulang'
                'status' => $finalStatus,
                'time_in' => $timeNow,
                'notes' => $finalNotes,
            ]);

            // 4. PANGGIL JOB NOTIFIKASI WA
            SendWaScanNotificationJob::dispatch($newScan);

            // 5. KIRIM RESPON SUKSES (JSON)
            return response()->json([
                'status' => 'success',
                'message' => 'Absensi ' . $attendanceType . ' Berhasil!',
                'student_name' => $student->name,
                'time' => \Carbon\Carbon::parse($newScan->time_in)->format('H:i')
            ], 200);

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan absensi Kiosk: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Kesalahan Server'], 500);
        }
    }

    /**
     * 4. TAMBAHKAN FUNGSI HELPER INI
     * Fungsi helper untuk mencari jadwal hari ini.
     */
    private function getTodaysSchedule(Carbon $now)
    {
        $today = $now->toDateString();
        
        // 1. Cek Jadwal Khusus (Prioritas)
        $special = ScheduleSpecial::where('date', $today)->first();
        if ($special) {
            if ($special->is_holiday) {
                return null; // Hari libur
            }
            return $special; // Ada jadwal khusus
        }

        // 2. Cek Jadwal Reguler
        $dayOfWeek = $now->dayOfWeek; // 0=Minggu, 1=Senin, ..., 5=Jumat, 6=Sabtu
        
        if ($dayOfWeek == 5) { // Jumat
            return ScheduleRegular::where('day_type', 'Jumat')->first();
        } elseif ($dayOfWeek >= 1 && $dayOfWeek <= 4) { // Senin-Kamis
            return ScheduleRegular::where('day_type', 'Biasa')->first();
        }

        return null; // Hari Minggu atau Sabtu (tidak ada jadwal reguler)
    }
}