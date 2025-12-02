<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSiswa;
use App\Models\Student;
use App\Models\ScheduleRegular;
use App\Models\ScheduleSpecial; // [FIX] Hapus double backslash
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
    public function showScanner()
    {
        $today = Carbon::today();
        
        // Ambil riwayat hari ini
        $logs = AttendanceSiswa::with('student')
            ->whereDate('attendance_date', $today)
            ->latest('created_at')
            ->limit(50) 
            ->get();
            
        // Ambil riwayat ekskul hari ini
        $ekskulLogs = ExtracurricularAttendance::with(['student', 'extracurricular'])
            ->whereDate('date', $today)
            ->latest('created_at')
            ->limit(50)
            ->get();

        $recentScans = [];

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

        return view('scan.index', [
            'recentScans' => array_values($recentScans),
            'extracurriculars' => $extracurriculars
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

        // Cari Siswa berdasarkan NISN (dari QR Code)
        $student = Student::where('student_id', $studentIdNisn)->first();
        if (!$student) {
            return response()->json(['message' => 'Siswa tidak ditemukan (NISN: ' . $studentIdNisn . ')'], 404);
        }

        // =========================================================================
        // LOGIKA 1: EKSTRAKURIKULER
        // =========================================================================
        if ($scanType == 'Ekstrakurikuler') {
            $extraName = $request->activity; 
            if (!$extraName) return response()->json(['message' => 'Pilih kegiatan ekskul dulu.'], 400);

            $ekskul = Extracurricular::where('name', $extraName)->first();
            if (!$ekskul) return response()->json(['message' => 'Data Ekskul tidak valid.'], 400);

            // [FIX] Gunakan $student->id (Integer) bukan NISN, kecuali DB Anda memang pakai string
            $isMember = ExtracurricularMember::where('extracurricular_id', $ekskul->id)
                        ->where('student_id', $student->id) 
                        ->exists();

            if (!$isMember) {
                return response()->json(['message' => "{$student->name} bukan anggota {$ekskul->name}.", 'status' => 'error'], 400);
            }

            // Cek Double Scan
            $alreadyPresent = ExtracurricularAttendance::where('extracurricular_id', $ekskul->id)
                            ->where('student_id', $student->id) // [FIX] Konsisten pakai ID
                            ->whereDate('date', $today)
                            ->exists();

            if ($alreadyPresent) {
                return response()->json(['message' => "Sudah absen {$ekskul->name} hari ini."], 409);
            }

            try {
                ExtracurricularAttendance::create([
                    'extracurricular_id' => $ekskul->id,
                    'student_id' => $student->id, // [FIX] Konsisten pakai ID
                    'date' => $today,
                    'time_in' => $timeNow,
                ]);

                return response()->json([
                    'message' => "Absen {$ekskul->name} Berhasil",
                    'scan' => ['student' => $student, 'type' => 'Ekstrakurikuler', 'activity' => $ekskul->name, 'status' => 'Hadir']
                ], 200);

            } catch (\Exception $e) {
                Log::error("Error Absen Ekskul: " . $e->getMessage());
                // [DEBUG MODE] Tampilkan pesan error asli
                return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
            }
        }

        // =========================================================================
        // LOGIKA 2: ABSENSI HARIAN
        // =========================================================================
        if ($scanType == 'Harian') {
            
            $schedule = $this->getTodaysSchedule($now);
            
            // [SAFETY] Jika jadwal belum diisi admin, pakai default agar tidak error 500
            if (!$schedule) {
                 $schedule = (object) [
                    'start_in' => '06:00:00',
                    'end_in' => '07:15:00', 
                    'start_out' => '12:00:00',
                    'end_out' => '17:00:00'
                 ];
                 // Atau return error jika ingin strict:
                 // return response()->json(['message' => 'Jadwal hari ini belum diatur.'], 400);
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
                    
                    try {
                        if (class_exists(SendWaScanNotificationJob::class)) {
                            SendWaScanNotificationJob::dispatch($newAttendance);
                        }
                    } catch (\Exception $waError) {
                        Log::warning("WA Error: " . $waError->getMessage());
                    }

                    return response()->json([
                        'message' => "{$student->name} Absen Masuk ({$statusAbsen}).",
                        'scan' => $newAttendance->load('student')
                    ], 200);
                } catch (\Exception $e) {
                    // [DEBUG MODE] Tampilkan error asli
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
        
                        try {
                            if (class_exists(SendWaScanNotificationJob::class)) {
                                SendWaScanNotificationJob::dispatch($attendance);
                            }
                        } catch (\Exception $waError) {
                            Log::warning("WA Pulang Error: " . $waError->getMessage());
                        }

                        return response()->json([
                            'message' => "{$student->name} Absen Pulang Berhasil.",
                            'scan' => $attendance->load('student')
                        ], 200);
                    } catch (\Exception $e) {
                         // [DEBUG MODE] Tampilkan error asli
                         return response()->json(['message' => 'Gagal Pulang: ' . $e->getMessage()], 500);
                    }
                } else {
                    return response()->json(['message' => "Belum jam pulang (Mulai {$schedule->start_out})."], 400);
                }
            }
        }
        
        // =========================================================================
        // LOGIKA 3: ABSENSI KEAGAMAAN
        // =========================================================================
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
                
                try {
                    if (class_exists(AddReligiousPointJob::class)) {
                        AddReligiousPointJob::dispatch($newAttendance);
                    }
                } catch (\Exception $jobError) {
                    Log::warning("Job Poin Error: " . $jobError->getMessage());
                }

                return response()->json([
                    'message' => "{$student->name} Absen {$activity} Berhasil.",
                    'scan' => $newAttendance->load('student')
                ], 200);

            } catch (\Exception $e) {
                // [DEBUG MODE] Tampilkan error asli
                return response()->json(['message' => "Gagal Keagamaan: " . $e->getMessage()], 500);
            }
        }
    }

    private function getTodaysSchedule(Carbon $now)
    {
        $today = $now->toDateString();
        try {
            $special = ScheduleSpecial::where('date', $today)->first();
            if ($special) return $special->is_holiday ? null : $special;

            $dayOfWeek = $now->dayOfWeek; 
            if ($dayOfWeek == 5) return ScheduleRegular::where('day_type', 'Jumat')->first();
            elseif ($dayOfWeek >= 1 && $dayOfWeek <= 4) return ScheduleRegular::where('day_type', 'Biasa')->first();
        } catch (\Exception $e) {
            Log::error("Jadwal Error: " . $e->getMessage());
            return null;
        }
        
        return null; 
    }
}