<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSiswa;
use App\Models\Student;
use App\Models\ScheduleRegular;
use App\Models\ScheduleSpecial;
// [BARU] Import Model Ekskul
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
        
        // Ambil log harian biasa
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
                    $scan['status'] = ($log->status === 'Terlambat') ? 'Terlambat' : 'Masuk';
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
        
        // [BARU] Ambil Data Ekskul untuk Dropdown
        $extracurriculars = Extracurricular::all();

        return view('scan.index', [
            'recentScans' => array_values($recentScans),
            'extracurriculars' => $extracurriculars // Kirim ke view
        ]);
    }
    
    public function processScan(Request $request)
    {
        // Validasi input, izinkan 'Ekstrakurikuler'
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

        // --- 1. LOGIKA ABSENSI EKSTRAKURIKULER (BARU) ---
        if ($scanType == 'Ekstrakurikuler') {
            $extraName = $request->activity; // Nama Ekskul dari dropdown
            
            // Validasi Data Ekskul
            $ekskul = Extracurricular::where('name', $extraName)->first();
            if (!$ekskul) {
                return response()->json(['message' => 'Data Ekskul tidak valid atau belum dipilih.'], 400);
            }

            // Validasi Keanggotaan (Opsional: Matikan jika ingin open for all)
            $isMember = ExtracurricularMember::where('extracurricular_id', $ekskul->id)
                        ->where('student_id', $student->student_id)
                        ->exists();

            if (!$isMember) {
                return response()->json([
                    'message' => "Siswa ini bukan anggota {$ekskul->name}!",
                    'status' => 'error'
                ], 400);
            }

            // Cek Duplikasi Absen Hari Ini
            $alreadyPresent = ExtracurricularAttendance::where('extracurricular_id', $ekskul->id)
                            ->where('student_id', $student->student_id)
                            ->whereDate('date', $today)
                            ->exists();

            if ($alreadyPresent) {
                return response()->json(['message' => "Sudah absen {$ekskul->name} hari ini."], 409);
            }

            // Simpan Absen
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
                return response()->json(['message' => 'Gagal mencatat absen ekskul.'], 500);
            }
        }

        // --- 2. Logika Absensi Harian (TETAP SAMA SEPERTI KODE ASLI ANDA) ---
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
                     Log::error("Error saat mencatat Absen Pulang: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                     return response()->json(['message' => 'Gagal mencatat Absen Pulang. Cek log server.'], 500);
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
                
                // Jika waktu sekarang > batas toleransi masuk (end_in)
                if ($timeNow > $schedule->end_in) { 
                    $endCarbon = Carbon::parse($schedule->end_in);
                    $nowCarbon = Carbon::parse($timeNow);
                    $minutesLate = $endCarbon->diffInMinutes($nowCarbon); 
                    
                    $statusAbsen = 'Terlambat'; // <-- UBAH STATUS JADI TERLAMBAT
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
                     Log::error("Error saat mencatat Absen Masuk: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                     return response()->json(['message' => 'Gagal mencatat Absen Masuk. Cek log server.'], 500);
                }

            } 
            else {
                return response()->json([
                    'message' => 'Di Luar Jam Absen. (Masuk: '.$schedule->start_in.'-'.$schedule->start_out.', Pulang: '.$schedule->start_out.'-'.$schedule->end_out.')'
                ], 400); 
            }
        
        // --- 3. Logika Absensi Keagamaan (TETAP SAMA) ---
        } else {
            $activity = $scanType;
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
                $newAttendance = AttendanceSiswa::create([
                    'student_id' => $student->id,
                    'attendance_date' => $today,
                    'status' => 'Hadir',
                    'type' => 'Keagamaan',
                    'activity' => $activity,
                    'time_in' => $timeNow,
                    'notes' => "Absen {$activity} otomatis.",
                ]);
                
                SendWaScanNotificationJob::dispatch($newAttendance);
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

    private function getTodaysSchedule(Carbon $now)
    {
        // ... (Kode getTodaysSchedule tetap sama seperti file asli Anda) ...
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