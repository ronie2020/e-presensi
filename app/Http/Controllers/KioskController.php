<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AttendanceSiswa;
use App\Models\ScheduleRegular;
use App\Models\ScheduleSpecial;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendWaScanNotificationJob; 

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
     * Menerapkan Time Windows (Jendela Waktu) yang mengizinkan KETERLAMBATAN.
     */
    public function processKioskScan(Request $request)
    {
        $request->validate([
            'scan_data' => 'required|string', // Ini adalah ID dari QR Code atau RFID
        ]);

        $studentIdFromScan = $request->scan_data;
        
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

        // 2. TENTUKAN JADWAL HARI INI
        $schedule = $this->getTodaysSchedule($now);

        // JIKA TIDAK ADA JADWAL (Libur, Minggu, atau belum diset)
        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hari Libur / Tidak Ada Jadwal',
                'student_name' => $student->name
            ], 400); 
        }

        // 3. TENTUKAN WAKTU SCAN (MASUK / PULANG / DITOLAK)
        
        $attendanceType = null; 
        $finalNotes = '';
        $finalStatus = 'Hadir';

        // === PERBAIKAN LOGIKA WAKTU DI SINI ===
        
        // A. Waktu PULANG (Prioritas Cek)
        // Siswa hanya bisa pulang jika waktu >= start_out (Misal jam 14:00)
        // Dan harus <= end_out (Misal jam 16:00 atau 17:00)
        $isPulangTime = ($timeNow >= $schedule->start_out && $timeNow <= $schedule->end_out);

        // B. Waktu MASUK (DIPERLUAS UNTUK TERLAMBAT)
        // Siswa bisa absen MASUK dari jam start_in (05:30) 
        // SAMPAI SEBELUM jam start_out (Misal sebelum 14:00).
        // Jadi kalau scan jam 08:00, 09:00, 10:00 tetap dianggap "Masuk" (tapi terlambat).
        $isMasukTime = ($timeNow >= $schedule->start_in && $timeNow < $schedule->start_out);

        // C. Eksekusi Logika Berdasarkan Waktu
        
        // Cek PULANG dulu agar tidak tertimpa logika Masuk jika waktunya beririsan tipis (jarang terjadi)
        if ($isPulangTime) {
            $attendanceType = 'Pulang';
            $finalNotes = 'Pulang Sekolah';
            
            // Cek apakah sudah absen MASUK? (Opsional: Jika mau wajibkan masuk dulu)
            $checkMasuk = AttendanceSiswa::where('student_id', $student->id)
                ->where('attendance_date', $today)
                ->where('type', 'Masuk')
                ->first();

            if (!$checkMasuk) {
                // Jika kebijakan sekolah: Boleh pulang meski lupa absen masuk, hapus blok if ini.
                // Tapi user request: "siswa yang sudah absen [masuk]... tidak bisa absen pulang sebelum waktunya"
                // Asumsi: Harus masuk dulu.
                 // return response()->json([
                 //    'status' => 'error',
                 //    'message' => 'Belum Absen Masuk!',
                 //    'student_name' => $student->name
                 // ], 400);
            }

            // Cek apakah SUDAH absen PULANG hari ini
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

        } elseif ($isMasukTime) {
            // INI LOGIKA MASUK (TEPAT WAKTU & TERLAMBAT)
            $attendanceType = 'Masuk';
            
            // Cek Keterlambatan
            // Jika Waktu Sekarang > Batas Masuk (07:00), maka TERLAMBAT
            if ($timeNow > $schedule->end_in) {
                $endTime = Carbon::parse($schedule->end_in);
                $startTime = Carbon::parse($timeNow); // Waktu scan sekarang
                
                // Hitung selisih menit (pembulatan ke atas)
                $minutesLate = $endTime->diffInMinutes($startTime); 
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

        } else {
            // D. Jika Scan DI LUAR Jam Masuk maupun Jam Pulang
            // Contoh: Scan jam 04:00 pagi atau jam 23:00 malam (di luar range start_in s/d end_out)
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
            // Jika Terlambat, beri indikasi di message agar Kiosk bisa memberi warna kuning/merah (opsional)
            $msgPrefix = stripos($finalNotes, 'Terlambat') !== false ? 'TERLAMBAT! ' : 'SUKSES! ';

            return response()->json([
                'status' => 'success',
                'message' => $msgPrefix . 'Absensi ' . $attendanceType . ' Berhasil.',
                'student_name' => $student->name,
                'time' => \Carbon\Carbon::parse($newScan->time_in)->format('H:i'),
                'note' => $finalNotes // Kirim notes agar bisa ditampilkan di layar Kiosk
            ], 200);

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan absensi Kiosk: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Kesalahan Server'], 500);
        }
    }

    /**
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