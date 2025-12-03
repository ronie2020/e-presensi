<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSiswa;
use App\Models\Student;
use App\Models\ScheduleRegular;
use App\Models\ScheduleSpecial;
use App\Models\Extracurricular;
use App\Models\ExtracurricularMember;
use App\Models\ExtracurricularAttendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 
use App\Jobs\SendWaScanNotificationJob; 
use App\Jobs\AddReligiousPointJob;      

class AttendanceSiswaController extends Controller
{
    /**
     * Menampilkan Halaman Scanner
     */
    public function showScanner()
    {
        $today = Carbon::today();
        
        // --- 1. LOGIKA JADWAL DINAMIS (BARU) ---
        // Default Config (Jika tidak ada jadwal di DB)
        $scheduleConfig = [
            'type' => 'Regular',
            'is_holiday' => false,
            'description' => 'KBM Normal',
            'start_in' => '06:00', 'end_in' => '07:15',
            'start_out' => '14:00', 'end_out' => '17:00',
            // Jam Sholat (Bisa di-hardcode atau ambil dari DB jika nanti ada tabelnya)
            'dhuha_start' => '07:30', 'dhuha_end' => '09:30',
            'dhuhur_start' => '11:45', 'dhuhur_end' => '12:30',
        ];

        // Cek Jadwal Khusus Hari Ini
        $specialSchedule = ScheduleSpecial::whereDate('date', $today)->first();

        if ($specialSchedule) {
            $scheduleConfig['type'] = $specialSchedule->is_holiday ? 'Holiday' : 'Special';
            $scheduleConfig['is_holiday'] = (bool) $specialSchedule->is_holiday;
            $scheduleConfig['description'] = $specialSchedule->description ?? 'Kegiatan Khusus';
            
            // Jika bukan libur, update jam operasional sesuai jadwal khusus
            if (!$specialSchedule->is_holiday) {
                $scheduleConfig['start_in'] = substr($specialSchedule->start_in, 0, 5);
                $scheduleConfig['end_in'] = substr($specialSchedule->end_in, 0, 5);
                $scheduleConfig['start_out'] = substr($specialSchedule->start_out, 0, 5);
                $scheduleConfig['end_out'] = substr($specialSchedule->end_out, 0, 5);
            }
        } else {
            // Jika Tidak Ada Jadwal Khusus, Cek Jadwal Reguler (Senin-Jumat)
            $dayOfWeek = $today->dayOfWeek; // 0=Minggu, 1=Senin, ..., 6=Sabtu
            $dayType = ($dayOfWeek == 5) ? 'Jumat' : 'Biasa'; // 5 = Jumat
            
            // Kecuali Sabtu/Minggu (Bisa disesuaikan jika sekolah 5 hari kerja)
            if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                // Opsional: Set Sabtu/Minggu jadi libur jika tidak ada KBM
                // $scheduleConfig['is_holiday'] = true;
                // $scheduleConfig['description'] = 'Libur Akhir Pekan';
            } else {
                $regularSchedule = ScheduleRegular::where('day_type', $dayType)->first();
                if ($regularSchedule) {
                    $scheduleConfig['start_in'] = substr($regularSchedule->start_in, 0, 5);
                    $scheduleConfig['end_in'] = substr($regularSchedule->end_in, 0, 5);
                    $scheduleConfig['start_out'] = substr($regularSchedule->start_out, 0, 5);
                    $scheduleConfig['end_out'] = substr($regularSchedule->end_out, 0, 5);
                }
            }
        }
        
        // --- 2. AMBIL RIWAYAT HARI INI (LOGIKA LAMA) ---
        $logs = AttendanceSiswa::with('student')
            ->whereDate('attendance_date', $today)
            ->latest('created_at')
            ->limit(50) 
            ->get();
            
        $ekskulLogs = ExtracurricularAttendance::with(['student', 'extracurricular'])
            ->whereDate('date', $today)
            ->latest('created_at')
            ->limit(50)
            ->get();

        $recentScans = [];

        // Parsing Log Harian & Keagamaan
        foreach ($logs as $log) {
            $studentId = $log->student?->student_id;
            if (!$studentId) continue;

            if (!isset($recentScans[$studentId])) {
                $recentScans[$studentId] = $this->initScanData($log->student, $log->notes);
            }
            
            $scan = &$recentScans[$studentId];

            if (in_array($log->type, ['Harian', 'Masuk', 'Pulang'])) {
                $scan['data_harian'] = true;
                if ($log->time_in) $scan['time_in'] = $log->time_in;
                if ($log->time_out) $scan['time_out'] = $log->time_out;
                
                if ($scan['time_in'] && $scan['time_out']) {
                    $scan['status'] = 'Pulang';
                } elseif ($scan['time_in']) {
                    $scan['status'] = ($log->status === 'Terlambat') ? 'Terlambat' : 'Masuk';
                }
                
                if (in_array($log->status, ['Sakit', 'Izin', 'Alfa'])) {
                     $scan['status'] = $log->status;
                }

            } elseif ($log->type == 'Keagamaan') {
                if ($log->activity == 'Dhuha') {
                    $scan['data_dhuha'] = true;
                    $scan['dhuha_time'] = $log->time_in; 
                } elseif ($log->activity == 'Dhuhur') {
                    $scan['data_dhuhur'] = true;
                    $scan['dhuhur_time'] = $log->time_in;
                }
            }
        }

        // Parsing Log Ekskul
        foreach ($ekskulLogs as $elog) {
            $studentId = $elog->student?->student_id;
            if (!$studentId) continue;

            if (!isset($recentScans[$studentId])) {
                $recentScans[$studentId] = $this->initScanData($elog->student, '-');
            }
            
            $recentScans[$studentId]['data_ekskul'] = true;
            $recentScans[$studentId]['ekskul_time'] = $elog->time_in;
            $recentScans[$studentId]['ekskul_name'] = $elog->extracurricular->name ?? '-';
        }
        
        $extracurriculars = Extracurricular::all();

        // --- 3. RETURN VIEW DENGAN DATA BARU ---
        return view('scan.index', [ // Pastikan nama view ini sesuai dengan lokasi file blade Anda
            'recentScans' => array_values($recentScans),
            'extracurriculars' => $extracurriculars,
            'scheduleConfig' => $scheduleConfig, // <--- Variabel penting untuk JS Frontend
        ]);
    }

    private function initScanData($student, $notes) {
        return [
            'student_id' => $student->student_id ?? '-',
            'student_name' => $student->name ?? 'Unknown',
            'data_harian' => false,
            'data_dhuha' => false,
            'data_dhuhur' => false,
            'data_ekskul' => false, 
            'time_in' => null,
            'time_out' => null,
            'dhuha_time' => null,
            'dhuhur_time' => null,
            'ekskul_time' => null, 
            'ekskul_name' => null, 
            'status' => 'Belum Absen',
            'notes' => $notes,
        ];
    }
    
    /**
     * Memproses QR Code yang discan
     */
    public function processScan(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string', 
            'type' => 'required|string',
        ]);

        $studentIdNisn = $request->student_id;
        $scanType = $request->type;
        $now = Carbon::now();
        $today = $now->toDateString();
        $timeNow = $now->toTimeString(); 

        $student = Student::where('student_id', $studentIdNisn)->first();
        if (!$student) {
            return response()->json(['message' => 'Siswa tidak ditemukan (NISN: ' . $studentIdNisn . ')'], 404);
        }

        // ================= LOGIKA EKSKUL =================
        if ($scanType == 'Ekstrakurikuler') {
            $extraName = $request->activity; 
            if (!$extraName) return response()->json(['message' => 'Pilih kegiatan ekskul dulu.'], 400);

            $ekskul = Extracurricular::where('name', $extraName)->first();
            if (!$ekskul) return response()->json(['message' => 'Data Ekskul tidak valid.'], 400);

            $isMember = ExtracurricularMember::where('extracurricular_id', $ekskul->id)
                        ->where('student_id', $student->id) 
                        ->exists();

            if (!$isMember) {
                return response()->json(['message' => "{$student->name} bukan anggota {$ekskul->name}.", 'status' => 'error'], 400);
            }

            $alreadyPresent = ExtracurricularAttendance::where('extracurricular_id', $ekskul->id)
                            ->where('student_id', $student->id)
                            ->whereDate('date', $today)
                            ->exists();

            if ($alreadyPresent) {
                return response()->json(['message' => "Sudah absen {$ekskul->name} hari ini."], 409);
            }

            try {
                ExtracurricularAttendance::create([
                    'extracurricular_id' => $ekskul->id,
                    'student_id' => $student->id,
                    'date' => $today,
                    'time_in' => $timeNow,
                ]);

                return response()->json([
                    'message' => "Absen {$ekskul->name} Berhasil",
                    'scan' => ['student' => $student, 'type' => 'Ekstrakurikuler', 'activity' => $ekskul->name, 'status' => 'Hadir']
                ], 200);

            } catch (\Exception $e) {
                Log::error("Error Absen Ekskul: " . $e->getMessage());
                return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
            }
        }

        // ================= LOGIKA HARIAN =================
        if ($scanType == 'Harian') {
            
            // Ambil jadwal untuk validasi Terlambat/Pulang
            $schedule = $this->getTodaysSchedule($now);
            
            // Jika Libur atau NULL, Tolak Absen (Opsional, tergantung kebijakan)
            if (!$schedule) {
                 return response()->json(['message' => 'Hari ini libur atau jadwal belum diatur.'], 400);
            }

            $attendance = AttendanceSiswa::where('student_id', $student->id)
                                         ->where('attendance_date', $today)
                                         ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
                                         ->first();
            
            if ($attendance && in_array($attendance->status, ['Sakit', 'Izin', 'Alfa'])) {
                return response()->json(['message' => "Siswa tercatat {$attendance->status} hari ini."], 409);
            }

            // A. SCAN MASUK
            if (!$attendance) {
                if ($timeNow > $schedule->end_out) {
                    return response()->json(['message' => 'Sekolah sudah tutup.'], 400);
                }

                $statusAbsen = 'Hadir'; 
                $finalNotes = 'Masuk Tepat Waktu';
                
                if ($timeNow > $schedule->end_in) { 
                    $endCarbon = Carbon::parse($schedule->end_in);
                    $nowCarbon = Carbon::parse($timeNow);
                    $minutesLate = $endCarbon->diffInMinutes($nowCarbon); 
                    
                    $statusAbsen = 'Terlambat'; 
                    $finalNotes = 'Terlambat ' . $minutesLate . ' menit';
                }

                try {
                    $newAttendance = AttendanceSiswa::create([
                        'student_id' => $student->id,
                        'attendance_date' => $today,
                        'type' => 'Masuk', 
                        'status' => $statusAbsen, 
                        'time_in' => $timeNow,
                        'notes' => $finalNotes,
                    ]);
                    
                    // Dispatch WA Job
                    try { if (class_exists(SendWaScanNotificationJob::class)) SendWaScanNotificationJob::dispatch($newAttendance); } catch (\Exception $e) {}

                    return response()->json([
                        'message' => "{$student->name} Absen Masuk ({$statusAbsen}).",
                        'scan' => $newAttendance->load('student')
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json(['message' => 'Gagal Masuk: ' . $e->getMessage()], 500);
                }
            }
            
            // B. SCAN PULANG
            else {
                if ($attendance->time_out) {
                    return response()->json(['message' => "Sudah Absen Pulang jam {$attendance->time_out}."], 409);
                }

                if ($timeNow >= $schedule->start_out) {
                    try {
                        $attendance->update([
                            'time_out' => $timeNow,
                            'notes' => $attendance->notes . ' | Pulang',
                        ]);
        
                        // Dispatch WA Job
                        try { if (class_exists(SendWaScanNotificationJob::class)) SendWaScanNotificationJob::dispatch($attendance); } catch (\Exception $e) {}

                        return response()->json([
                            'message' => "{$student->name} Absen Pulang Berhasil.",
                            'scan' => $attendance->load('student')
                        ], 200);
                    } catch (\Exception $e) {
                         return response()->json(['message' => 'Gagal Pulang: ' . $e->getMessage()], 500);
                    }
                } else {
                    return response()->json(['message' => "Belum jam pulang (Mulai {$schedule->start_out})."], 400);
                }
            }
        }
        
        // ================= LOGIKA KEAGAMAAN =================
        else {
            $activity = $scanType; 
            $attendance = AttendanceSiswa::where('student_id', $student->id)
                                         ->where('attendance_date', $today)
                                         ->where('type', 'Keagamaan')
                                         ->where('activity', $activity)
                                         ->first();

            if ($attendance) {
                return response()->json(['message' => "Sudah Absen {$activity}."], 409); 
            }

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
                
                // Dispatch Point Job
                try { if (class_exists(AddReligiousPointJob::class)) AddReligiousPointJob::dispatch($newAttendance); } catch (\Exception $e) {}

                return response()->json([
                    'message' => "{$student->name} Absen {$activity} Berhasil.",
                    'scan' => $newAttendance->load('student')
                ], 200);

            } catch (\Exception $e) {
                return response()->json(['message' => "Gagal Keagamaan: " . $e->getMessage()], 500);
            }
        }
    }

    /**
     * Helper untuk mengambil object Jadwal
     * Mengembalikan NULL jika hari libur atau tidak ada jadwal
     */
    private function getTodaysSchedule(Carbon $now)
    {
        $today = $now->toDateString();
        try {
            // 1. Cek Jadwal Khusus
            $special = ScheduleSpecial::where('date', $today)->first();
            if ($special) {
                // Jika is_holiday true, return null (Libur)
                // Jika false, return object special (Event tapi masuk)
                return $special->is_holiday ? null : $special;
            }

            // 2. Cek Jadwal Reguler
            $dayOfWeek = $now->dayOfWeek; 
            if ($dayOfWeek == 5) { // Jumat
                return ScheduleRegular::where('day_type', 'Jumat')->first();
            } elseif ($dayOfWeek >= 1 && $dayOfWeek <= 4) { // Senin-Kamis
                return ScheduleRegular::where('day_type', 'Biasa')->first();
            }
            
            // Sabtu Minggu return null (kecuali ada settingan lain)
            return null;

        } catch (\Exception $e) {
            Log::error("Jadwal Error: " . $e->getMessage());
            return null;
        }
    }
}