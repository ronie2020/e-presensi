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
    public function showScanner()
    {
        $today = Carbon::today();
        
        $logs = AttendanceSiswa::with('student')
            ->whereDate('attendance_date', $today)
            ->latest('created_at')
            ->get();
            
        $ekskulLogs = ExtracurricularAttendance::with(['student', 'extracurricular'])
            ->whereDate('date', $today)
            ->latest('created_at')
            ->get();

        $recentScans = [];

        foreach ($logs as $log) {
            $studentId = $log->student?->student_id;
            if (!$studentId) continue;

            if (!isset($recentScans[$studentId])) {
                $recentScans[$studentId] = $this->initScanData($log->student, $log->notes);
            }
            
            $scan = &$recentScans[$studentId];

            if ($log->type == 'Harian' || $log->type == 'Masuk' || $log->type == 'Pulang') {
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
            $recentScans[$studentId]['ekskul_name'] = $elog->extracurricular->name;
        }
        
        $extracurriculars = Extracurricular::all();

        return view('scan.index', [
            'recentScans' => array_values($recentScans),
            'extracurriculars' => $extracurriculars
        ]);
    }

    private function initScanData($student, $notes) {
        return [
            'student_id' => $student->student_id,
            'student_name' => $student->name,
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
            'type' => 'required|string|in:Harian,Dhuha,Dhuhur,Ekstrakurikuler',
        ]);

        $studentIdNisn = $request->student_id;
        $scanType = $request->type;
        $now = Carbon::now();
        $today = $now->toDateString();
        $timeNow = $now->toTimeString(); 

        $student = Student::where('student_id', $studentIdNisn)->first();
        if (!$student) {
            return response()->json(['message' => 'Siswa dengan ID ' . $studentIdNisn . ' tidak ditemukan.'], 404);
        }

        // =========================================================================
        // LOGIKA 1: EKSTRAKURIKULER
        // =========================================================================
        if ($scanType == 'Ekstrakurikuler') {
            $extraName = $request->activity; 
            if (!$extraName) return response()->json(['message' => 'Pilih kegiatan ekskul dulu.'], 400);

            $ekskul = Extracurricular::where('name', $extraName)->first();
            if (!$ekskul) return response()->json(['message' => 'Ekskul tidak valid.'], 400);

            $isMember = ExtracurricularMember::where('extracurricular_id', $ekskul->id)
                        ->where('student_id', $student->student_id)
                        ->exists();

            if (!$isMember) {
                return response()->json(['message' => "{$student->name} bukan anggota {$ekskul->name}.", 'status' => 'error'], 400);
            }

            $alreadyPresent = ExtracurricularAttendance::where('extracurricular_id', $ekskul->id)
                            ->where('student_id', $student->student_id)
                            ->whereDate('date', $today)
                            ->exists();

            if ($alreadyPresent) {
                return response()->json(['message' => "Sudah absen {$ekskul->name} hari ini."], 409);
            }

            try {
                ExtracurricularAttendance::create([
                    'extracurricular_id' => $ekskul->id,
                    'student_id' => $student->student_id,
                    'date' => $today,
                    'time_in' => $timeNow,
                ]);

                return response()->json([
                    'message' => "Absen {$ekskul->name} Berhasil",
                    'scan' => ['student' => $student, 'type' => 'Ekstrakurikuler', 'activity' => $ekskul->name, 'status' => 'Hadir']
                ], 200);

            } catch (\Exception $e) {
                Log::error("Error Absen Ekskul: " . $e->getMessage());
                return response()->json(['message' => 'Error sistem.'], 500);
            }
        }

        // =========================================================================
        // LOGIKA 2: ABSENSI HARIAN (DIPERBAIKI: Try-Catch WA Ditambahkan)
        // =========================================================================
        if ($scanType == 'Harian') {
            
            $schedule = $this->getTodaysSchedule($now);
            if (!$schedule) return response()->json(['message' => 'Libur / Tidak Ada Jadwal'], 400);

            $attendance = AttendanceSiswa::where('student_id', $student->id)
                                         ->where('attendance_date', $today)
                                         ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
                                         ->first();
            
            if ($attendance && in_array($attendance->status, ['Sakit', 'Izin', 'Alfa'])) {
                return response()->json(['message' => "Absensi tercatat sebagai {$attendance->status}."], 409);
            }

            // A. JIKA BELUM ADA DATA MASUK
            if (!$attendance) {
                if ($timeNow <= $schedule->end_out) {
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
                        
                        // [FIX] Bungkus WA dengan Try-Catch agar tidak bikin error 500
                        try {
                            if (class_exists(SendWaScanNotificationJob::class)) {
                                SendWaScanNotificationJob::dispatch($newAttendance);
                            }
                        } catch (\Exception $e) {
                            Log::warning("Gagal kirim WA Masuk: " . $e->getMessage());
                        }

                        return response()->json([
                            'message' => "{$student->name} Absen Masuk ({$statusAbsen}).",
                            'scan' => $newAttendance->load('student')
                        ], 200);
                    } catch (\Exception $e) {
                        return response()->json(['message' => 'Gagal simpan database.'], 500);
                    }
                } else {
                    return response()->json(['message' => 'Sekolah sudah tutup.'], 400);
                }
            }
            
            // B. JIKA SUDAH ADA DATA MASUK -> CEK PULANG
            else {
                if ($attendance->time_out) {
                    return response()->json(['message' => "Sudah Absen Pulang jam {$attendance->time_out}."], 409);
                }

                if ($timeNow >= $schedule->start_out) {
                    try {
                        $attendance->update([
                            'time_out' => $timeNow,
                            'notes' => $attendance->notes . ' | Pulang Sekolah',
                        ]);
        
                        // [FIX] Bungkus WA dengan Try-Catch agar tidak bikin error 500
                        try {
                            if (class_exists(SendWaScanNotificationJob::class)) {
                                SendWaScanNotificationJob::dispatch($attendance);
                            }
                        } catch (\Exception $e) {
                            Log::warning("Gagal kirim WA Pulang: " . $e->getMessage());
                        }

                        return response()->json([
                            'message' => "{$student->name} Absen Pulang Berhasil.",
                            'scan' => $attendance->load('student')
                        ], 200);
                    } catch (\Exception $e) {
                         return response()->json(['message' => 'Gagal Absen Pulang.'], 500);
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
                
                // Job WA & Poin
                try {
                    // [MODIFIED] WA dimatikan sementara sesuai permintaan user
                    // if (class_exists(\App\Jobs\SendWaScanNotificationJob::class)) {
                    //     \App\Jobs\SendWaScanNotificationJob::dispatch($newAttendance);
                    // }
                    
                    // Poin tetap jalan
                    if (class_exists(\App\Jobs\AddReligiousPointJob::class)) {
                        \App\Jobs\AddReligiousPointJob::dispatch($newAttendance);
                    }
                } catch (\Exception $e) {
                    Log::warning("Gagal Job Keagamaan: " . $e->getMessage());
                }

                return response()->json([
                    'message' => "{$student->name} Absen {$activity} Berhasil.",
                    'scan' => $newAttendance->load('student')
                ], 200);

            } catch (\Exception $e) {
                return response()->json(['message' => "Gagal Absen {$activity}."], 500);
            }
        }
    }

    private function getTodaysSchedule(Carbon $now)
    {
        $today = $now->toDateString();
        $special = ScheduleSpecial::where('date', $today)->first();
        if ($special) return $special->is_holiday ? null : $special;

        $dayOfWeek = $now->dayOfWeek; 
        if ($dayOfWeek == 5) return ScheduleRegular::where('day_type', 'Jumat')->first();
        elseif ($dayOfWeek >= 1 && $dayOfWeek <= 4) return ScheduleRegular::where('day_type', 'Biasa')->first();
        
        return null; 
    }
}