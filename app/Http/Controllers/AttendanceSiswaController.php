<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSiswa;
use App\Models\Student;
use App\Models\ScheduleRegular;
use App\Models\ScheduleSpecial;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Untuk debugging
use App\Jobs\SendWaScanNotificationJob; // <-- IMPORT JOB WA
use App\Jobs\AddReligiousPointJob;      // <-- TAMBAHAN: IMPORT JOB POIN

class AttendanceSiswaController extends Controller
{
    // ... (showScanner function biarkan seperti sebelumnya) ...
    public function showScanner()
    {
        // ... (Isi function showScanner sama persis dengan file aslimu, tidak diubah)
        
        $today = Carbon::today();
        $logs = AttendanceSiswa::with('student')
            ->whereDate('attendance_date', $today)
            ->latest('created_at')
            ->get();
            
        $recentScans = [];

        foreach ($logs as $log) {
            $studentId = $log->student?->student_id;
            
            if (!$studentId) continue;

            if (!isset($recentScans[$studentId])) {
                $recentScans[$studentId] = [
                    'student_id' => $studentId,
                    'student_name' => $log->student->name,
                    'is_manual' => in_array($log->status, ['Sakit', 'Izin', 'Alfa']),
                    'data_harian' => false,
                    'data_dhuha' => false,
                    'data_dhuhur' => false,
                    'time_in' => null,
                    'time_out' => null,
                    'dhuha_time' => null,
                    'dhuhur_time' => null,
                    'status' => 'Belum Absen',
                    'notes' => $log->notes,
                ];
            }
            
            $scan = &$recentScans[$studentId];

            if ($log->type == 'Harian') {
                $scan['data_harian'] = true;
                if ($log->time_in) $scan['time_in'] = $log->time_in;
                if ($log->time_out) $scan['time_out'] = $log->time_out;
                
                if ($scan['time_in'] && $scan['time_out']) {
                    $scan['status'] = 'Pulang';
                } elseif ($scan['time_in']) {
                    $scan['status'] = 'Masuk';
                }
                if ($scan['is_manual']) $scan['status'] = $log->status;

            } elseif ($log->type == 'Keagamaan') {
                $scanTime = $log->time_in;
                if ($log->activity == 'Dhuha') {
                    $scan['data_dhuha'] = true;
                    $scan['dhuha_time'] = $scanTime; 
                } elseif ($log->activity == 'Dhuhur') {
                    $scan['data_dhuhur'] = true;
                    $scan['dhuhur_time'] = $scanTime;
                }
            }
        }
        
        return view('scan.index', [
            'recentScans' => array_values($recentScans),
        ]);
    }
    
    /**
     * Memproses data scan QR Code dari frontend.
     */
    public function processScan(Request $request)
    {
        // Validasi input dari AJAX
        $request->validate([
            'student_id' => 'required|string', // Ini adalah ID dari QR Code (misal: NISN)
            'type' => 'required|string|in:Harian,Dhuha,Dhuhur',
        ]);

        $studentIdNisn = $request->student_id;
        $scanType = $request->type;
        $now = Carbon::now();
        $today = $now->toDateString();
        // Gunakan format string yang konsisten untuk perbandingan dengan database
        $timeNow = $now->toTimeString(); 

        // 1. Cari siswa berdasarkan NISN
        $student = Student::where('student_id', $studentIdNisn)->first();
        if (!$student) {
            return response()->json(['message' => 'Siswa dengan ID ' . $studentIdNisn . ' tidak ditemukan.'], 404);
        }

        // --- Logika Absensi Harian ---
        if ($scanType == 'Harian') {
            
            $schedule = $this->getTodaysSchedule($now);

            // Cek Jadwal
            if (!$schedule) {
                return response()->json(['message' => 'Hari Libur / Tidak Ada Jadwal Absen Harian'], 400);
            }

            // === PERBAIKAN LOGIKA WAKTU (SAMA DENGAN KIOSK) ===
            
            // Cek Waktu Pulang (Prioritas 1)
            // Siswa bisa pulang jika >= start_out (14:00) DAN <= end_out (17:00)
            $isPulangTime = ($timeNow >= $schedule->start_out && $timeNow <= $schedule->end_out);

            // Cek Waktu Masuk (Prioritas 2)
            // Siswa bisa masuk jika >= start_in (05:30) DAN < start_out (14:00)
            // Artinya jam 07:05, 08:00, 12:00 masih dianggap MASUK (tapi terlambat)
            $isMasukTime = ($timeNow >= $schedule->start_in && $timeNow < $schedule->start_out);

            // Cari record Harian siswa hari ini
            $attendance = AttendanceSiswa::where('student_id', $student->id)
                                         ->where('attendance_date', $today)
                                         ->where('type', 'Harian')
                                         ->first();
            
            // JIKA SUDAH ADA RECORD SAKIT/IZIN/ALFA (MANUAL)
            if ($attendance && in_array($attendance->status, ['Sakit', 'Izin', 'Alfa'])) {
                return response()->json([
                    'message' => "KONFLIK: Absensi sudah tercatat sebagai {$attendance->status} (Manual)."
                ], 409);
            }

            // --- EKSEKUSI LOGIKA ---
            
            // A. LOGIKA PULANG
            if ($isPulangTime) {
                if (!$attendance || !$attendance->time_in) {
                    // Konflik: Belum ada record Masuk
                    // Jika kebijakan sekolah memperbolehkan pulang tanpa scan masuk, hapus validasi ini.
                    return response()->json([
                        'message' => "GAGAL: {$student->name} Belum Absen Masuk hari ini. Tidak bisa absen Pulang."
                    ], 409);
                }
                
                if ($attendance->time_out) {
                    // Konflik: Sudah Pulang
                    return response()->json([
                        'message' => "KONFLIK: Absensi Pulang sudah lengkap jam {$attendance->time_out}."
                    ], 409);
                }
                
                // Update record Pulang
                try {
                    $attendance->update([
                        'time_out' => $timeNow,
                        'notes' => $attendance->notes . ' | Pulang Sekolah',
                    ]);
    
                    SendWaScanNotificationJob::dispatch($attendance);
                    return response()->json([
                        'message' => "{$student->name} berhasil Absen Pulang jam {$timeNow}.",
                        'scan' => $attendance->load('student')
                    ], 200);
                } catch (\Exception $e) {
                     Log::error("Error saat mencatat Absen Pulang: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                     return response()->json(['message' => 'Gagal mencatat Absen Pulang. Cek log server.'], 500);
                }

            } 
            // B. LOGIKA MASUK (TEPAT WAKTU & TERLAMBAT)
            elseif ($isMasukTime) {
                
                if ($attendance && $attendance->time_in) {
                    // Konflik: Sudah Masuk
                    return response()->json([
                        'message' => "KONFLIK: {$student->name} sudah Absen Masuk jam {$attendance->time_in}."
                    ], 409);
                }

                $finalNotes = 'Masuk Tepat Waktu';
                
                // LOGIKA TERLAMBAT (Jika waktu sekarang > batas masuk normal jam 07:00)
                if ($timeNow > $schedule->end_in) { 
                    $endCarbon = Carbon::parse($schedule->end_in);
                    $nowCarbon = Carbon::parse($timeNow);
                    $minutesLate = $endCarbon->diffInMinutes($nowCarbon); 
                    $finalNotes = 'Terlambat ' . $minutesLate . ' menit';
                }
                
                // Buat/Update record
                try {
                    $newAttendance = AttendanceSiswa::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'attendance_date' => $today,
                            'type' => 'Harian'
                        ],
                        [
                            'status' => 'Hadir',
                            'time_in' => $timeNow,
                            'notes' => $finalNotes,
                        ]
                    );
                    
                    SendWaScanNotificationJob::dispatch($newAttendance);
                    return response()->json([
                        'message' => "{$student->name} berhasil Absen Masuk jam {$timeNow} ({$finalNotes}).",
                        'scan' => $newAttendance->load('student')
                    ], 200);

                } catch (\Exception $e) {
                     Log::error("Error saat mencatat Absen Masuk: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                     return response()->json(['message' => 'Gagal mencatat Absen Masuk. Cek log server.'], 500);
                }

            } 
            // Di luar Jam Absensi
            else {
                return response()->json([
                    'message' => 'Di Luar Jam Absen. (Masuk: '.$schedule->start_in.'-'.$schedule->start_out.', Pulang: '.$schedule->start_out.'-'.$schedule->end_out.')'
                ], 400); 
            }
        
        // --- Logika Absensi Keagamaan (Dhuha / Dhuhur) ---
        } else {
            $activity = $scanType;
            $attendance = AttendanceSiswa::where('student_id', $student->id)
                                         ->where('attendance_date', $today)
                                         ->where('type', 'Keagamaan')
                                         ->where('activity', $activity)
                                         ->first();

            if ($attendance) {
                // Konflik: Sudah Absen Keagamaan untuk aktivitas ini
                return response()->json([
                    'message' => "KONFLIK: {$student->name} sudah Absen {$activity} pada jam {$attendance->time_in}."
                ], 409); 
            }

            // Catat Absen Keagamaan
            try {
                $newAttendance = AttendanceSiswa::create([
                    'student_id' => $student->id,
                    'attendance_date' => $today,
                    'status' => 'Hadir',
                    'type' => 'Keagamaan',
                    'activity' => $activity,
                    'time_in' => $timeNow,
                    'notes' => "Absen {$activity} otomatis.",
                ]);
                
                // 1. Jalankan Job Notifikasi WA (walaupun di jobnya kita matikan sementara)
                SendWaScanNotificationJob::dispatch($newAttendance);

                // 2. TAMBAHAN: Jalankan Job Poin Kebaikan
                AddReligiousPointJob::dispatch($newAttendance);

                return response()->json([
                    'message' => "{$student->name} berhasil Absen {$activity} jam {$timeNow}.",
                    'scan' => $newAttendance->load('student')
                ], 200);

            } catch (\Exception $e) {
                Log::error("Error saat mencatat Absen Keagamaan ($activity): " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                return response()->json(['message' => "Gagal mencatat Absen {$activity}. Cek log server."], 500);
            }
        }
    }

    // ... (getTodaysSchedule function sama) ...
    private function getTodaysSchedule(Carbon $now)
    {
        $today = $now->toDateString();
        
        // 1. Cek Jadwal Khusus (Prioritas)
        $special = ScheduleSpecial::where('date', $today)->first();
        if ($special) {
            if ($special->is_holiday) {
                return null; // Hari libur
            }
            // Jadwal khusus harus memiliki start_in, end_in, start_out, end_out
            return (object)[
                'start_in' => $special->start_in,
                'end_in' => $special->end_in,
                'start_out' => $special->start_out,
                'end_out' => $special->end_out,
            ];
        }

        // 2. Cek Jadwal Reguler
        $dayOfWeek = $now->dayOfWeek; // 0=Minggu, 1=Senin, ..., 5=Jumat, 6=Sabtu
        
        $dayType = null;
        if ($dayOfWeek == 5) { // Jumat
            $dayType = 'Jumat';
        } elseif ($dayOfWeek >= 1 && $dayOfWeek <= 4) { // Senin-Kamis
            $dayType = 'Biasa';
        }
        
        if ($dayType) {
            $regular = ScheduleRegular::where('day_type', $dayType)->first();
            if ($regular) {
                return (object)[
                    'start_in' => $regular->start_in,
                    'end_in' => $regular->end_in,
                    'start_out' => $regular->start_out,
                    'end_out' => $regular->end_out,
                ];
            }
        }

        return null; // Hari Minggu atau Sabtu (tidak ada jadwal reguler)
    }
}