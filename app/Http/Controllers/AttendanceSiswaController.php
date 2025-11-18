<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSiswa;
use App\Models\Student;
use App\Models\ScheduleRegular;
use App\Models\ScheduleSpecial;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Untuk debugging
use App\Jobs\SendWaScanNotificationJob; // <-- IMPORT JOB

class AttendanceSiswaController extends Controller
{
    /**
     * Menampilkan halaman scanner.
     */
    public function showScanner()
    {
        // Ambil data 5 scan terakhir HARI INI untuk ditampilkan di log
        $recentScans = AttendanceSiswa::with('student') // Eager loading relasi student
            ->whereDate('attendance_date', Carbon::today())
            ->latest('created_at') // Urutkan berdasarkan waktu input terbaru
            ->take(5)
            ->get();

        return view('scan.index', [
            'recentScans' => $recentScans
        ]);
    }

    /**
     * Memproses data scan yang masuk dari JavaScript (AJAX).
     *
     * === LOGIKA DIPERBARUI ===
     * Menerapkan Time Windows (Jendela Waktu) dinamis dari Database.
     * Memecah 'Harian' menjadi 'Masuk' dan 'Pulang'.
     * Mempertahankan logika 'Dhuha' dan 'Dhuhur'.
     */
    public function processScan(Request $request)
    {
        // Validasi input dari AJAX
        $request->validate([
            'student_id' => 'required|string', // Ini adalah ID dari QR Code (misal: NISN)
            'type' => 'required|string|in:Harian,Dhuha,Dhuhur',
        ]);

        $studentIdFromScan = $request->student_id;
        $scanType = $request->type; // Tipe dari tombol (Harian, Dhuha, Dhuhur)
        $now = Carbon::now();
        $today = $now->toDateString();
        $timeNow = $now->toTimeString();

        // 1. Cari siswa berdasarkan student_id (NISN)
        $student = Student::where('student_id', $studentIdFromScan)->first();
        
        // JIKA SISWA TIDAK DITEMUKAN
        if (!$student) {
            return response()->json(['message' => 'Siswa dengan ID ' . $studentIdFromScan . ' tidak ditemukan.'], 404);
        }

        // 2. LOGIKA BARU BERDASARKAN TIPE SCAN
        $attendanceType = $scanType; // Default (akan berisi Dhuha/Dhuhur)
        $finalNotes = $scanType . ' Tepat Waktu';
        $finalStatus = 'Hadir';

        // HANYA JIKA TIPE ADALAH 'HARIAN', KITA CEK JAM MASUK/PULANG
        if ($scanType == 'Harian') {
            
            // A. TENTUKAN JADWAL HARI INI
            $schedule = $this->getTodaysSchedule($now);

            // JIKA TIDAK ADA JADWAL (Libur, Minggu, atau belum diset)
            if (!$schedule) {
                return response()->json([
                    'message' => 'Hari Libur / Tidak Ada Jadwal'
                ], 400); // 400 = Bad Request
            }

            // B. TENTUKAN WAKTU SCAN (MASUK / PULANG / DITOLAK)
            
            // Cek Jam Masuk (DINAMIS DARI DATABASE)
            $isMasukTime = ($timeNow >= $schedule->start_in && $timeNow <= $schedule->end_in);
            
            // Cek Jam Pulang (DINAMIS DARI DATABASE)
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
                        'message' => $student->name . ' sudah absen (Masuk) jam ' . $alreadyScanned->time_in
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
                        'message' => $student->name . ' sudah absen (Pulang) jam ' . $alreadyScanned->time_in
                    ], 409);
                }

            } else {
                // D. Jika Scan DI LUAR Jam Masuk maupun Jam Pulang
                return response()->json([
                    'message' => 'Di Luar Jam Absen Harian'
                ], 400); // 400 = Bad Request
            }

        } 
        // LOGIKA UNTUK DHUHA & DHUHUR (Tetap sama)
        else {
            // Cek apakah siswa sudah absen untuk tipe ini HARI INI
            $alreadyScanned = AttendanceSiswa::where('student_id', $student->id)
                ->where('attendance_date', $today)
                ->where('type', $scanType) // Cek $scanType ('Dhuha' atau 'Dhuhur')
                ->first();

            // JIKA SUDAH PERNAH SCAN
            if ($alreadyScanned) {
                return response()->json([
                    'message' => $student->name . ' sudah absen (' . $scanType . ') jam ' . $alreadyScanned->time_in
                ], 409); // 409 = Conflict
            }
            
            // Catatan: $finalNotes dan $finalStatus sudah di-set di default atas
        }


        // 4. SIMPAN DATA ABSENSI BARU
        try {
            $newScan = AttendanceSiswa::create([
                'student_id' => $student->id,
                'attendance_date' => $today,
                'type' => $attendanceType, // Akan berisi 'Masuk', 'Pulang', 'Dhuha', atau 'Dhuhur'
                'status' => $finalStatus,
                'time_in' => $timeNow,
                'notes' => $finalNotes,
            ]);

            // 5. PANGGIL JOB NOTIFIKASI JIKA TIPE-NYA 'Masuk' atau 'Pulang'
            if (in_array($attendanceType, ['Masuk', 'Pulang'])) {
                SendWaScanNotificationJob::dispatch($newScan);
            }

            // Load relasi student agar bisa dikirim balik ke log
            $newScan->load('student');

            // KIRIM RESPON SUKSES (JSON)
            return response()->json([
                'message' => $student->name . ' - Absensi ' . $attendanceType . ' berhasil dicatat!',
                'scan' => $newScan // Kirim data scan baru untuk ditampilkan di log
            ], 200);

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan absensi: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan internal saat menyimpan data.'], 500);
        }
    }

    /**
     * Fungsi helper untuk mencari jadwal hari ini.
     * (Logika ini sama dengan di KioskController)
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