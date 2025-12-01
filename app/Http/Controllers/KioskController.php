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
    public function showKiosk()
    {
        return view('kiosk.index');
    }

    public function processKioskScan(Request $request)
    {
        $request->validate([
            'scan_data' => 'required|string', 
        ]);

        $studentIdFromScan = $request->scan_data;
        $now = Carbon::now();
        $today = $now->toDateString();
        $timeNow = $now->toTimeString(); 

        // 1. Cari Siswa
        $student = Student::where('student_id', $studentIdFromScan)
                            ->orWhere('rfid_id', $studentIdFromScan)
                            ->first();
        
        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa Tidak Ditemukan',
                'student_name' => 'N/A'
            ], 404);
        }

        // 2. Ambil Jadwal
        $schedule = $this->getTodaysSchedule($now);
        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hari Libur / Tidak Ada Jadwal',
                'student_name' => $student->name
            ], 400); 
        }

        // 3. Cek Status Absensi Siswa Hari Ini di Database
        $existingAttendance = AttendanceSiswa::where('student_id', $student->id)
            ->where('attendance_date', $today)
            ->where('type', '!=', 'Keagamaan') 
            ->where('type', '!=', 'Ekstrakurikuler') 
            ->first();

        $attendanceType = '';
        $finalStatus = 'Hadir';
        $finalNotes = '';
        $isUpdate = false;

        // SKENARIO A: BELUM ABSEN MASUK
        if (!$existingAttendance) {
            $isWithinOperationalHours = ($timeNow >= $schedule->start_in && $timeNow <= $schedule->end_out);

            if ($isWithinOperationalHours) {
                $attendanceType = 'Masuk';
                
                if ($timeNow > $schedule->end_in) {
                    $endTime = Carbon::parse($schedule->end_in);
                    $startTime = Carbon::parse($timeNow);
                    $minutesLate = $endTime->diffInMinutes($startTime);
                    
                    $finalStatus = 'Terlambat'; 
                    $finalNotes = "Terlambat {$minutesLate} menit";
                } else {
                    $finalStatus = 'Hadir';
                    $finalNotes = 'Masuk Tepat Waktu';
                }

            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Di Luar Jam Operasional Sekolah',
                    'student_name' => $student->name
                ], 400);
            }
        } 
        
        // SKENARIO B: SUDAH ABSEN MASUK -> MAU PULANG
        else {
            if ($existingAttendance->time_out) {
                 return response()->json([
                    'status' => 'error',
                    'message' => 'Sudah Absen Pulang (' . Carbon::parse($existingAttendance->time_out)->format('H:i') . ')',
                    'student_name' => $student->name
                ], 409);
            }

            if ($timeNow >= $schedule->start_out) {
                $attendanceType = 'Pulang';
                $finalNotes = $existingAttendance->notes . ' | Pulang Sekolah';
                $isUpdate = true;
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Belum Waktunya Pulang (Mulai: ' . $schedule->start_out . ')',
                    'student_name' => $student->name
                ], 400);
            }
        }
        
        // 4. EKSEKUSI SIMPAN DATA
        try {
            if ($isUpdate) {
                $existingAttendance->update([
                    'time_out' => $timeNow,
                    'notes' => $finalNotes
                ]);
                $attendanceRecord = $existingAttendance;
            } else {
                $attendanceRecord = AttendanceSiswa::create([
                    'student_id' => $student->id,
                    'attendance_date' => $today,
                    'type' => $attendanceType, 
                    'status' => $finalStatus,  
                    'time_in' => $timeNow,
                    'notes' => $finalNotes,
                ]);
            }

            // [FIX] Try-Catch untuk Notifikasi WA agar tidak crash 500
            try {
                SendWaScanNotificationJob::dispatch($attendanceRecord);
            } catch (\Exception $e) {
                Log::warning("Kiosk WA Failed: " . $e->getMessage());
            }

            $messagePrefix = ($finalStatus == 'Terlambat') ? 'TERLAMBAT! ' : 'SUKSES! ';
            
            return response()->json([
                'status' => 'success',
                'message' => $messagePrefix . "Absen {$attendanceType} Berhasil.",
                'student_name' => $student->name,
                'time' => Carbon::parse($timeNow)->format('H:i'),
                'note' => $finalNotes
            ], 200);

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan absensi Kiosk: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Kesalahan Server'], 500);
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