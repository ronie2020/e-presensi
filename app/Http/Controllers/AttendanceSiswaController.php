<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSiswa;
use App\Models\Student;
use App\Models\ScheduleRegular;
use App\Models\ScheduleSpecial;
// [BARU] Import Model-Model Ekskul
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
        
        // 1. Ambil Log Absensi Harian & Sholat
        $logs = AttendanceSiswa::with('student')
            ->whereDate('attendance_date', $today)
            ->latest('created_at')
            ->get();
            
        // 2. Ambil Log Absensi Ekskul Hari Ini
        $ekskulLogs = ExtracurricularAttendance::with(['student', 'extracurricular'])
            ->whereDate('date', $today)
            ->latest('created_at')
            ->get();

        $recentScans = [];

        // A. Proses Log Harian/Sholat
        foreach ($logs as $log) {
            $studentId = $log->student?->student_id;
            if (!$studentId) continue;

            if (!isset($recentScans[$studentId])) {
                $recentScans[$studentId] = $this->initScanData($log->student, $log->notes);
            }
            
            $scan = &$recentScans[$studentId];

            if ($log->type == 'Harian') {
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

        // B. Proses Log Ekskul (GABUNGKAN KE TABEL)
        foreach ($ekskulLogs as $elog) {
            $studentId = $elog->student?->student_id;
            if (!$studentId) continue;

            if (!isset($recentScans[$studentId])) {
                $recentScans[$studentId] = $this->initScanData($elog->student, '-');
            }
            
            // Tandai bahwa siswa ini punya data ekskul
            $recentScans[$studentId]['data_ekskul'] = true;
            // Simpan detailnya (Waktu & Nama Ekskul)
            $recentScans[$studentId]['ekskul_time'] = $elog->time_in;
            $recentScans[$studentId]['ekskul_name'] = $elog->extracurricular->name;
        }
        
        $extracurriculars = Extracurricular::all();

        return view('scan.index', [
            'recentScans' => array_values($recentScans),
            'extracurriculars' => $extracurriculars
        ]);
    }

    // Helper Inisialisasi Data (Pastikan method ini ada di dalam Class)
    private function initScanData($student, $notes) {
        return [
            'student_id' => $student->student_id,
            'student_name' => $student->name,
            'data_harian' => false,
            'data_dhuha' => false,
            'data_dhuhur' => false,
            'data_ekskul' => false, // [BARU] Flag Ekskul
            'time_in' => null,
            'time_out' => null,
            'dhuha_time' => null,
            'dhuhur_time' => null,
            'ekskul_time' => null, // [BARU] Waktu Ekskul
            'ekskul_name' => null, // [BARU] Nama Ekskul
            'status' => 'Belum Absen',
            'notes' => $notes,
        ];
    }
    
    public function processScan(Request $request)
    {
        // Validasi Input
        $request->validate([
            'student_id' => 'required|string', 
            'type' => 'required|string|in:Harian,Dhuha,Dhuhur,Ekstrakurikuler',
        ]);

        $studentIdNisn = $request->student_id;
        $scanType = $request->type;
        $now = Carbon::now();
        $today = $now->toDateString();
        $timeNow = $now->toTimeString(); 

        // Cari Siswa
        $student = Student::where('student_id', $studentIdNisn)->first();
        if (!$student) {
            return response()->json(['message' => 'Siswa dengan ID ' . $studentIdNisn . ' tidak ditemukan.'], 404);
        }

        // =========================================================================
        // LOGIKA 1: EKSTRAKURIKULER (BARU)
        // =========================================================================
        if ($scanType == 'Ekstrakurikuler') {
            $extraName = $request->activity; // Nama Ekskul dari dropdown frontend
            
            // 1. Cek Apakah Ekskul Dipilih?
            if (!$extraName) {
                return response()->json(['message' => 'Silakan pilih kegiatan ekstrakurikuler terlebih dahulu di dropdown.'], 400);
            }

            // 2. Validasi Data Ekskul di Database
            $ekskul = Extracurricular::where('name', $extraName)->first();
            if (!$ekskul) {
                return response()->json(['message' => 'Data Ekskul tidak valid.'], 400);
            }

            // 3. Validasi Keanggotaan (Siswa harus terdaftar)
            // Jika ingin mematikan validasi ini (semua siswa boleh ikut), hapus blok if ini.
            $isMember = ExtracurricularMember::where('extracurricular_id', $ekskul->id)
                        ->where('student_id', $student->student_id)
                        ->exists();

            if (!$isMember) {
                return response()->json([
                    'message' => "Gagal! {$student->name} belum terdaftar sebagai anggota {$ekskul->name}.",
                    'status' => 'error'
                ], 400); // Status 400 akan memicu alert merah di frontend
            }

            // 4. Cek Duplikasi (Apakah sudah absen hari ini di ekskul yang sama?)
            $alreadyPresent = ExtracurricularAttendance::where('extracurricular_id', $ekskul->id)
                            ->where('student_id', $student->student_id)
                            ->whereDate('date', $today)
                            ->exists();

            if ($alreadyPresent) {
                return response()->json([
                    'message' => "Siswa ini sudah melakukan absen {$ekskul->name} hari ini."
                ], 409); // Status 409 (Conflict) memicu alert "Sudah Absen"
            }

            // 5. Simpan Data Absensi Ekskul
            try {
                ExtracurricularAttendance::create([
                    'extracurricular_id' => $ekskul->id,
                    'student_id' => $student->student_id,
                    'date' => $today,
                    'time_in' => $timeNow,
                ]);

                return response()->json([
                    'message' => "Absen {$ekskul->name} Berhasil",
                    'scan' => [
                        'student' => $student,
                        'type' => 'Ekstrakurikuler',
                        'activity' => $ekskul->name,
                        'status' => 'Hadir'
                    ]
                ], 200);

            } catch (\Exception $e) {
                Log::error("Error Absen Ekskul: " . $e->getMessage());
                return response()->json(['message' => 'Terjadi kesalahan sistem saat menyimpan absen ekskul.'], 500);
            }
        }

        // =========================================================================
        // LOGIKA 2: ABSENSI HARIAN (EXISTING)
        // =========================================================================
        if ($scanType == 'Harian') {
            
            $schedule = $this->getTodaysSchedule($now);

            if (!$schedule) {
                return response()->json(['message' => 'Hari Libur / Tidak Ada Jadwal Absen Harian'], 400);
            }

            $isPulangTime = ($timeNow >= $schedule->start_out && $timeNow <= $schedule->end_out);
            $isMasukTime = ($timeNow >= $schedule->start_in && $timeNow < $schedule->start_out);

            $attendance = AttendanceSiswa::where('student_id', $student->id)
                                         ->where('attendance_date', $today)
                                         ->where('type', 'Harian')
                                         ->first();
            
            if ($attendance && in_array($attendance->status, ['Sakit', 'Izin', 'Alfa'])) {
                return response()->json([
                    'message' => "KONFLIK: Absensi sudah tercatat sebagai {$attendance->status} (Manual)."
                ], 409);
            }

            // A. LOGIKA PULANG
            if ($isPulangTime) {
                if (!$attendance || !$attendance->time_in) {
                    return response()->json([
                        'message' => "GAGAL: {$student->name} Belum Absen Masuk hari ini. Tidak bisa absen Pulang."
                    ], 409);
                }
                
                if ($attendance->time_out) {
                    return response()->json([
                        'message' => "KONFLIK: Absensi Pulang sudah lengkap jam {$attendance->time_out}."
                    ], 409);
                }
                
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
                     Log::error("Error saat mencatat Absen Pulang: " . $e->getMessage());
                     return response()->json(['message' => 'Gagal mencatat Absen Pulang.'], 500);
                }

            } 
            // B. LOGIKA MASUK (TEPAT WAKTU & TERLAMBAT)
            elseif ($isMasukTime) {
                
                if ($attendance && $attendance->time_in) {
                    return response()->json([
                        'message' => "KONFLIK: {$student->name} sudah Absen Masuk jam {$attendance->time_in}."
                    ], 409);
                }

                $statusAbsen = 'Hadir'; // Default status
                $finalNotes = 'Masuk Tepat Waktu';
                
                // Cek Keterlambatan
                if ($timeNow > $schedule->end_in) { 
                    $endCarbon = Carbon::parse($schedule->end_in);
                    $nowCarbon = Carbon::parse($timeNow);
                    $minutesLate = $endCarbon->diffInMinutes($nowCarbon); 
                    
                    $statusAbsen = 'Terlambat'; 
                    $finalNotes = 'Terlambat ' . $minutesLate . ' menit';
                }
                
                try {
                    $newAttendance = AttendanceSiswa::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'attendance_date' => $today,
                            'type' => 'Harian'
                        ],
                        [
                            'status' => $statusAbsen, 
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
                     Log::error("Error saat mencatat Absen Masuk: " . $e->getMessage());
                     return response()->json(['message' => 'Gagal mencatat Absen Masuk.'], 500);
                }

            } 
            else {
                return response()->json([
                    'message' => 'Di Luar Jam Absen. (Masuk: '.$schedule->start_in.'-'.$schedule->start_out.', Pulang: '.$schedule->start_out.'-'.$schedule->end_out.')'
                ], 400); 
            }
        }
        
        // =========================================================================
        // LOGIKA 3: ABSENSI KEAGAMAAN (Update agar Aman)
        // =========================================================================
        else {
            $activity = $scanType; // Dhuha atau Dhuhur
            $attendance = AttendanceSiswa::where('student_id', $student->id)
                                         ->where('attendance_date', $today)
                                         ->where('type', 'Keagamaan')
                                         ->where('activity', $activity)
                                         ->first();

            if ($attendance) {
                return response()->json([
                    'message' => "KONFLIK: {$student->name} sudah Absen {$activity} pada jam {$attendance->time_in}."
                ], 409); 
            }

            try {
                // 1. Simpan Absen Dulu (Ini yang paling penting)
                $newAttendance = AttendanceSiswa::create([
                    'student_id' => $student->id,
                    'attendance_date' => $today,
                    'status' => 'Hadir',
                    'type' => 'Keagamaan',
                    'activity' => $activity,
                    'time_in' => $timeNow,
                    'notes' => "Absen {$activity} otomatis.",
                ]);
                
                // 2. Jalankan Job (WA & Poin) dengan Pengecekan agar tidak error 500
                try {
                    if (class_exists(\App\Jobs\SendWaScanNotificationJob::class)) {
                        \App\Jobs\SendWaScanNotificationJob::dispatch($newAttendance);
                    }
                    
                    if (class_exists(\App\Jobs\AddReligiousPointJob::class)) {
                        \App\Jobs\AddReligiousPointJob::dispatch($newAttendance);
                    } else {
                        // Fallback manual jika Job tidak ada: Tambah poin langsung (Opsional)
                        // $student->increment('points', 5); 
                    }
                } catch (\Exception $jobError) {
                    // Jika Job gagal (misal koneksi WA mati), biarkan saja. Jangan gagalkan absensi.
                    Log::warning("Job Absen Gagal: " . $jobError->getMessage());
                }

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

    private function getTodaysSchedule(Carbon $now)
    {
        // Logika Penjadwalan Harian (Tidak Berubah)
        $today = $now->toDateString();
        $special = ScheduleSpecial::where('date', $today)->first();
        if ($special) {
            if ($special->is_holiday) return null;
            return (object)[
                'start_in' => $special->start_in,
                'end_in' => $special->end_in,
                'start_out' => $special->start_out,
                'end_out' => $special->end_out,
            ];
        }

        $dayOfWeek = $now->dayOfWeek; 
        $dayType = null;
        if ($dayOfWeek == 5) $dayType = 'Jumat';
        elseif ($dayOfWeek >= 1 && $dayOfWeek <= 4) $dayType = 'Biasa';
        
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
        return null; 
    }
}