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

// --- IMPORT MODEL BARU UNTUK POIN KEDISIPLINAN & EKSKUL ---
use App\Models\ActivityLog;
use App\Models\StudentHabit;
use App\Models\Extracurricular;
use App\Models\ExtracurricularMember;
use App\Models\ExtracurricularAttendance;

class KioskController extends Controller
{
    public function showKiosk()
    {
        return view('kiosk.index');
    }

    public function processKioskScan(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string', 
            'type'       => 'nullable|string', 
            'extra_id'   => 'nullable',
            'lat'        => 'nullable', 
            'long'       => 'nullable'  
        ]);

        $studentIdFromScan = $request->student_id;
        $scanType = $request->type ?? 'Harian'; 
        $now = Carbon::now();
        $today = $now->toDateString();
        $timeNow = $now->toTimeString(); 

        // 1. Cari Siswa
        $student = Student::where('student_id', $studentIdFromScan)
                            ->orWhere('rfid_id', $studentIdFromScan)
                            ->orWhere('nisn', $studentIdFromScan)
                            ->first();
        
        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa Tidak Ditemukan',
                'student_name' => 'N/A'
            ], 404);
        }

        if ($student->status !== 'active') {
            return response()->json([
               'status' => 'error',
               'message' => 'Status siswa tidak aktif!',
               'student_name' => $student->name
           ], 403);
       }

        // 2. Ambil Jadwal
        $schedule = $this->getTodaysSchedule($now);
        
        if (!$schedule && $scanType == 'Harian') {
            return response()->json([
                'status' => 'error',
                'message' => 'Hari Libur / Tidak Ada Jadwal',
                'student_name' => $student->name
            ], 400); 
        }

        // ==========================================
        // PENANGANAN MODE KHUSUS DENGAN POIN DISIPLIN
        // ==========================================
        
        // --- MODE MAKAN ---
        if ($scanType === 'Makan') {
            $exists = AttendanceSiswa::where('student_id', $student->id)->where('attendance_date', $today)->where('type', 'Meal')->first();
            if ($exists) return response()->json(['status' => 'error', 'message' => 'Sudah Ambil Makan', 'student_name' => $student->name], 409);
            
            $attendanceRecord = AttendanceSiswa::create(['student_id' => $student->id, 'attendance_date' => $today, 'type' => 'Meal', 'activity' => 'Makan Siang', 'status' => 'Hadir', 'time_in' => $timeNow, 'notes' => 'Ambil Makan Siang (Kiosk)']);
            
            $this->logActivity($student, 'Meal', 'Makan Bergizi', "Mengambil jatah makan siang", 2);
            StudentHabit::updateOrCreate(
                ['student_id' => $student->id, 'report_date' => $today],
                ['habit_5' => true, 'habit_5_menu' => 'Menu Sekolah (MBG)']
            );

            return response()->json(['status' => 'success', 'message' => 'SUKSES! Silahkan Ambil Makan.', 'student_name' => $student->name, 'time' => Carbon::parse($timeNow)->format('H:i'), 'scan' => $attendanceRecord], 200);
        }

        // --- MODE SHOLAT DHUHA & DHUHUR ---
        if (in_array($scanType, ['Dhuha', 'Dhuhur'])) {
            $exists = AttendanceSiswa::where('student_id', $student->id)->where('attendance_date', $today)->where('type', 'Keagamaan')->where('activity', $scanType)->first();
            if ($exists) return response()->json(['status' => 'error', 'message' => "Sudah Absen $scanType", 'student_name' => $student->name], 409);
            
            $attendanceRecord = AttendanceSiswa::create(['student_id' => $student->id, 'attendance_date' => $today, 'type' => 'Keagamaan', 'activity' => $scanType, 'status' => 'Hadir', 'time_in' => $timeNow, 'notes' => "Sholat $scanType (Kiosk)"]);
            
            $this->logActivity($student, 'Religious', "Sholat $scanType", "Melaksanakan shalat $scanType berjamaah", 5);
            $colName = ($scanType == 'Dhuha') ? 'prayer_dhuha' : 'prayer_dzuhur';
            StudentHabit::updateOrCreate(
                ['student_id' => $student->id, 'report_date' => $today],
                [$colName => true]
            );
            $student->checkBkThresholds();

            return response()->json(['status' => 'success', 'message' => "SUKSES! Absen $scanType Berhasil (+5 Poin).", 'student_name' => $student->name, 'time' => Carbon::parse($timeNow)->format('H:i'), 'scan' => $attendanceRecord], 200);
        }

        // --- MODE EKSTRAKURIKULER ---
        if ($scanType === 'Ekstrakurikuler') {
            $extraId = $request->extra_id;
            if (!$extraId) return response()->json(['status' => 'error', 'message' => 'Pilih Ekskul Dulu!'], 422);
            
            $extra = Extracurricular::find($extraId);
            if (!$extra) return response()->json(['status' => 'error', 'message' => 'Data Ekskul Tidak Valid'], 422);

            $attendanceRecord = AttendanceSiswa::updateOrCreate(
                ['student_id' => $student->id, 'attendance_date' => $today, 'type' => 'Extracurricular'],
                ['status' => 'Hadir', 'time_in' => $timeNow, 'notes' => 'Kegiatan Ekstrakurikuler (Kiosk)', 'activity' => $extra->name]
            );

            $detailAtt = ExtracurricularAttendance::firstOrCreate(
                ['extracurricular_id' => $extraId, 'student_id' => $student->id, 'date' => $today],
                ['status' => 'Hadir', 'time_in' => $timeNow]
            );

            $msg = "SUKSES! Absen Ekskul Berhasil.";
            
            if ($detailAtt->wasRecentlyCreated) {
                $isMember = ExtracurricularMember::where('student_id', $student->id)->where('extracurricular_id', $extraId)->exists();
                if ($isMember) {
                    $this->logActivity($student, 'Extracurricular', $extra->name, "Hadir kegiatan {$extra->name}", 5);
                    $msg = "{$student->name} Hadir {$extra->name} (+5 Poin)";
                } else {
                    $this->logActivity($student, 'Extracurricular', $extra->name, "Hadir kegiatan {$extra->name} (Tamu)", 0);
                    $msg = "{$student->name} Hadir {$extra->name} (Tamu)";
                }
            } else {
                $msg = "{$student->name} sudah absen {$extra->name} sebelumnya.";
            }

            return response()->json(['status' => 'success', 'message' => $msg, 'student_name' => $student->name, 'time' => Carbon::parse($timeNow)->format('H:i'), 'scan' => $attendanceRecord], 200);
        }

        // ==========================================
        // LOGIKA UTAMA (HARIAN: MASUK & PULANG)
        // DILENGKAPI DENGAN PEMBLOKIR WAKTU STRICT
        // ==========================================
        
        // Ambil batas waktu dari Database (Fallback jika kosong)
        $scheduleStartIn  = $schedule->start_in ?? '06:00:00'; 
        $scheduleLimit    = $schedule->end_in ?? '07:15:00'; 
        $scheduleStartOut = $schedule->start_out ?? '14:00:00'; 
        $scheduleEndOut   = $schedule->end_out ?? '17:00:00'; 

        $existingAttendance = AttendanceSiswa::where('student_id', $student->id)
            ->where('attendance_date', $today)
            ->whereIn('type', ['Masuk', 'Pulang', 'Harian']) 
            ->first();

        $finalStatus = '';
        $finalNotes = '';

        try {
            // SKENARIO A: BELUM ABSEN MASUK
            if (!$existingAttendance || !$existingAttendance->time_in || $existingAttendance->time_in == '00:00:00') {
                
                // --- 1. BLOKIR JIKA TERLALU PAGI ---
                if ($timeNow < $scheduleStartIn) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Belum Waktunya Masuk (Mulai: ' . Carbon::parse($scheduleStartIn)->format('H:i') . ')',
                        'student_name' => $student->name
                    ], 400);
                }

                // --- 2. BLOKIR JIKA DI LUAR JAM SEKOLAH (MALAM HARI) ---
                if ($timeNow > $scheduleEndOut) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Di Luar Jam Operasional Sekolah',
                        'student_name' => $student->name
                    ], 400);
                }

                $limitTime = Carbon::createFromTimeString($scheduleLimit);
                $isLate = $now->gt($limitTime);
                $attendanceType = 'Harian'; 
                
                $finalStatus = $isLate ? 'Terlambat' : 'Hadir';
                $finalNotes = $isLate ? "Terlambat (Limit: {$scheduleLimit})" : "Hadir Tepat Waktu (Kiosk)";

                $attendanceRecord = AttendanceSiswa::create([
                    'student_id'      => $student->id,
                    'attendance_date' => $today,
                    'type'            => $attendanceType, 
                    'status'          => $finalStatus,  
                    'time_in'         => $timeNow,
                    'lat_in'          => $request->lat,   
                    'long_in'         => $request->long,  
                    'notes'           => $finalNotes,
                ]);

                // TRIGGER BK UNTUK KETERLAMBATAN
                if ($isLate) {
                    $this->logActivity($student, 'Violation', 'Terlambat Masuk', "Terlambat hadir (Limit: {$scheduleLimit})", -5);
                    $student->checkBkThresholds();
                }

                $waktuSapaan = 'Absen Masuk Berhasil.';
                $messagePrefix = $isLate ? 'TERLAMBAT! ' : 'SUKSES! ';

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

                $startOutTime = Carbon::createFromTimeString($scheduleStartOut);
                
                // --- 3. BLOKIR JIKA BELUM WAKTUNYA PULANG (MENCEGAH BOLOS) ---
                if ($now->lt($startOutTime)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Belum Waktunya Pulang (Mulai: ' . $startOutTime->format('H:i') . ')',
                        'student_name' => $student->name
                    ], 400);
                }
                
                $finalNotes = $existingAttendance->notes ? $existingAttendance->notes . " | Pulang (Kiosk)" : "Pulang Sekolah (Kiosk)";

                $existingAttendance->update([
                    'time_out' => $timeNow,
                    'lat_out'  => $request->lat,   
                    'long_out' => $request->long,  
                    'notes'    => $finalNotes
                ]);
                
                $attendanceRecord = $existingAttendance;
                $waktuSapaan = 'Absen Pulang Berhasil.';
                $messagePrefix = 'SUKSES! ';
            }

            // Notifikasi WA
            try {
                SendWaScanNotificationJob::dispatch($attendanceRecord);
            } catch (\Exception $e) {
                Log::warning("Kiosk WA Failed: " . $e->getMessage());
            }

            return response()->json([
                'status' => 'success',
                'message' => $messagePrefix . $waktuSapaan,
                'student_name' => $student->name,
                'time' => Carbon::parse($timeNow)->format('H:i'),
                'note' => $finalNotes,
                'scan' => $attendanceRecord 
            ], 200);

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan absensi Kiosk: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error', 
                'message' => 'DB ERROR: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper Function: Catat Aktivitas Kedisiplinan
     */
    private function logActivity($student, $type, $name, $desc, $points) 
    {
        ActivityLog::create([
            'student_id' => $student->id, 
            'activity_type' => $type, 
            'activity_name' => $name,
            'description' => $desc, 
            'point_earned' => $points
        ]);
        
        if($points != 0) {
            $student->increment('score', $points);
        }
    }

    private function getTodaysSchedule(Carbon $now)
    {
        $today = $now->toDateString();
        $special = ScheduleSpecial::where('date', $today)->first();
        if ($special) return $special->is_holiday ? null : $special;

        $dayOfWeek = $now->dayOfWeek; 
        if ($dayOfWeek == 5) {
            return ScheduleRegular::where('day_name', 'Jumat')->first();
        } elseif ($dayOfWeek >= 1 && $dayOfWeek <= 4) {
            return ScheduleRegular::where('day_name', 'Biasa')->first();
        }

        return null;
    }
}