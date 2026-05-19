<?php

namespace App\Services;

use App\Models\AttendanceSiswa;
use App\Models\ActivityLog;
use App\Models\StudentHabit;
use App\Models\Extracurricular;
use App\Models\ExtracurricularMember;
use App\Models\ExtracurricularAttendance;
use App\Models\ScheduleRegular;
use App\Models\ScheduleSpecial;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendWaScanNotificationJob;

class AttendanceService
{
    /**
     * Mengambil Jadwal Hari Ini
     */
    public function getTodaySchedule(Carbon $date)
    {
        $schedule = ScheduleSpecial::where('date', $date->toDateString())->first();
        if (!$schedule) {
            // Menggunakan Carbon translatedFormat agar otomatis menyesuaikan bahasa
            $dayName = $date->locale('id')->translatedFormat('l');
            // Antisipasi penulisan Jum'at menjadi Jumat
            if (in_array($dayName, ['Jumat', "Jum'at"])) $dayName = 'Jumat';

            $schedule = ScheduleRegular::where('day_name', $dayName == 'Jumat' ? 'Jumat' : 'Biasa')->first();
        }
        return $schedule;
    }

    /**
     * Proses Absen Harian (Masuk / Pulang)
     * $source = 'kiosk' (Ketat/Strict) atau 'guru' (Fleksibel)
     */
    public function processDailyScan($student, Carbon $scanTime, $lat, $long, $schedule, $source = 'kiosk')
    {
        $todayDate = $scanTime->toDateString();
        $timeNow = $scanTime->toTimeString();
        $now = clone $scanTime;

        // Ambil batas waktu (Fallback jika kosong)
        $scheduleStartIn  = $schedule->start_in ?? '06:00:00'; 
        $scheduleLimit    = $schedule->end_in ?? '07:15:00'; 
        $scheduleStartOut = $schedule->start_out ?? '14:00:00'; 
        $scheduleEndOut   = $schedule->end_out ?? '17:00:00'; 

        return DB::transaction(function () use ($student, $todayDate, $timeNow, $now, $lat, $long, $scheduleStartIn, $scheduleLimit, $scheduleStartOut, $scheduleEndOut, $source) {
            
            // FIX RACE CONDITION (KUNCI MUTLAK):
            \App\Models\Student::where('id', $student->id)->lockForUpdate()->first();

            // Cek apakah data absen sudah ada
            $existingAttendance = AttendanceSiswa::where('student_id', $student->id)
                ->where('attendance_date', $todayDate)
                ->whereIn('type', ['Masuk', 'Pulang', 'Harian']) 
                ->first(); 

            // SKENARIO A: BELUM ABSEN MASUK
            if (!$existingAttendance || !$existingAttendance->time_in || $existingAttendance->time_in == '00:00:00') {
                
                // [KHUSUS KIOSK] Blokir jika terlalu pagi atau malam
                if ($source === 'kiosk') {
                    if ($timeNow < $scheduleStartIn) {
                        return ['success' => false, 'code' => 400, 'message' => 'Belum Waktunya Masuk (Mulai: ' . Carbon::parse($scheduleStartIn)->format('H:i') . ')'];
                    }
                    if ($timeNow > $scheduleEndOut) {
                        return ['success' => false, 'code' => 400, 'message' => 'Di Luar Jam Operasional Sekolah'];
                    }
                }

                $limitTime = Carbon::createFromTimeString($scheduleLimit);
                $isLate = $now->gt($limitTime);
                $finalStatus = $isLate ? 'Terlambat' : 'Hadir';
                $notesContext = ($source === 'kiosk') ? 'Kiosk' : 'Guru';
                $finalNotes = $isLate ? "Terlambat (Limit: {$scheduleLimit})" : "Hadir Tepat Waktu ({$notesContext})";

                $attendanceRecord = AttendanceSiswa::create([
                    'student_id'      => $student->id,
                    'attendance_date' => $todayDate,
                    'type'            => 'Harian', 
                    'status'          => $finalStatus,  
                    'time_in'         => $timeNow,
                    'lat_in'          => $lat,   
                    'long_in'         => $long,  
                    'notes'           => $finalNotes,
                ]);

                // TRIGGER POIN & BK UNTUK KETERLAMBATAN
               if ($isLate) {
                    $this->logActivity($student, 'Violation', 'Terlambat Masuk', "Terlambat hadir (Limit: {$scheduleLimit})", -5);
                    $student->checkBkThresholds();
                }

                // Pesan dinamis sesuai status kehadiran
                $pesanMasuk = $isLate 
                    ? "TERLAMBAT! Anda melewati batas waktu masuk ({$scheduleLimit})." 
                    : "HADIR TEPAT WAKTU! Absen masuk berhasil.";

                return [
                    'success' => true, 'code' => 200, 
                    'status_text' => $finalStatus,
                    'message' => $pesanMasuk,
                    'model' => $attendanceRecord,
                    'note' => $finalNotes
                ];
            } 
            // SKENARIO B: SUDAH ABSEN MASUK -> MAU PULANG
            else {
                if ($existingAttendance->time_out) {
                    return ['success' => false, 'code' => 409, 'message' => 'Sudah Absen Pulang (' . Carbon::parse($existingAttendance->time_out)->format('H:i') . ')'];
                }

                // Cek Cooldown (HANYA KIOSK: Mencegah Spam Scan mandiri oleh siswa)
                if ($existingAttendance->time_in && $source === 'kiosk') {
                    $timeIn = Carbon::parse($existingAttendance->time_in);
                    $diffMinutes = $timeIn->diffInMinutes($now);
                    
                    if ($diffMinutes < 5) {
                        return ['success' => false, 'code' => 429, 'message' => 'Anda baru saja absen masuk. Tunggu beberapa saat sebelum scan lagi.'];
                    }
                }

                $startOutTime = Carbon::createFromTimeString($scheduleStartOut);
                $isEarly = $now->lt($startOutTime);
                
                // Blokir kepulangan jika belum waktunya (HANYA KIOSK)
                if ($isEarly && $source === 'kiosk') {
                    return ['success' => false, 'code' => 400, 'message' => 'Belum Waktunya Pulang! Jadwal pulang dimulai pukul: ' . $startOutTime->format('H:i')];
                }
                
                $notesContext = ($source === 'kiosk') ? 'Kiosk' : 'Guru';
                
                // Beri label spesifik jika pulang awal
                $finalNotes = $isEarly 
                    ? ($existingAttendance->notes ? $existingAttendance->notes . " | Pulang Cepat ({$notesContext})" : "Pulang Cepat ({$notesContext})")
                    : ($existingAttendance->notes ? $existingAttendance->notes . " | Pulang ({$notesContext})" : "Pulang Sekolah ({$notesContext})");

                $existingAttendance->update([
                    'time_out' => $timeNow,
                    'lat_out'  => $lat,   
                    'long_out' => $long,  
                    'notes'    => $finalNotes
                ]);
                
                return [
                    'success' => true, 'code' => 200, 
                    'status_text' => $isEarly ? 'Pulang Cepat' : 'Pulang',
                    'message' => ($isEarly ? 'PULANG CEPAT! ' : 'SUKSES! ') . 'Absen Pulang Berhasil.',
                    'model' => $existingAttendance,
                    'note' => $finalNotes
                ];
            }
        });
    }
    
    /**
     * Proses Absen Keagamaan
     */
    public function processReligious($student, $type, Carbon $scanTime)
    {
        return DB::transaction(function () use ($student, $type, $scanTime) {
            $todayDate = $scanTime->toDateString();

            // FIX RACE CONDITION
            \App\Models\Student::where('id', $student->id)->lockForUpdate()->first();

            $exists = AttendanceSiswa::where('student_id', $student->id)->where('attendance_date', $todayDate)
                ->where('type', 'Keagamaan')->where('activity', $type)->exists();

            if ($exists) return ['success' => false, 'code' => 409, 'message' => "Sudah Absen $type hari ini!"];

            $att = AttendanceSiswa::create([
                'student_id' => $student->id, 'attendance_date' => $todayDate,
                'type' => 'Keagamaan', 'activity' => $type, 'status' => 'Hadir',
                'time_in' => $scanTime->toTimeString(), 'notes' => "Sholat $type"
            ]);

            $this->logActivity($student, 'Religious', "Shalat $type", "Melaksanakan shalat $type berjamaah", 5);
            $colName = ($type == 'Dhuha') ? 'prayer_dhuha' : 'prayer_dzuhur';
            StudentHabit::updateOrCreate(['student_id' => $student->id, 'report_date' => $todayDate], [$colName => true]);
            $student->checkBkThresholds();

            return ['success' => true, 'code' => 200, 'status_text' => 'Selesai', 'message' => "Absen {$type} Tercatat (+5 Poin)", 'model' => $att, 'type' => $type];
        });
    }

    /**
     * Proses Absen Makan Bergizi
     */
    public function processMeal($student, Carbon $scanTime)
    {
        return DB::transaction(function () use ($student, $scanTime) {
            $todayDate = $scanTime->toDateString();

            // FIX RACE CONDITION
            \App\Models\Student::where('id', $student->id)->lockForUpdate()->first();

            $existing = AttendanceSiswa::where('student_id', $student->id)->where('attendance_date', $todayDate)
                ->where('type', 'Meal')->first();

            if ($existing) return ['success' => false, 'code' => 409, 'message' => "SUDAH mengambil makan siang!"];

            $att = AttendanceSiswa::create([
                'student_id' => $student->id, 'attendance_date' => $todayDate,
                'type' => 'Meal', 'status' => 'Hadir', 'activity' => 'Makan Siang',
                'time_in' => $scanTime->toTimeString()
            ]);

            $this->logActivity($student, 'Meal', 'Makan Bergizi', "Mengambil jatah makan siang", 2);
            StudentHabit::updateOrCreate(['student_id' => $student->id, 'report_date' => $todayDate], ['habit_5' => true, 'habit_5_menu' => 'Menu Sekolah (MBG)']);

            $count = AttendanceSiswa::whereDate('attendance_date', $todayDate)->where('type', 'Meal')->count();
            return ['success' => true, 'code' => 200, 'status_text' => 'Ambil Makan', 'message' => "Berhasil Ambil Makan", 'model' => $att, 'type' => 'Makan', 'taken' => $count];
        });
    }

    /**
     * Proses Absen Ekstrakurikuler
     */
    public function processExtra($student, $extraId, Carbon $scanTime)
    {
        if (!$extraId) return ['success' => false, 'code' => 422, 'message' => 'Pilih kegiatan ekstrakurikuler dulu!'];
        $extra = Extracurricular::find($extraId);
        if (!$extra) return ['success' => false, 'code' => 422, 'message' => 'Data Ekskul tidak valid'];

        return DB::transaction(function () use ($student, $extra, $extraId, $scanTime) {
            $todayDate = $scanTime->toDateString();
            
            // FIX RACE CONDITION
            \App\Models\Student::where('id', $student->id)->lockForUpdate()->first();

            // CEK APAKAH SUDAH PERNAH ABSEN EKSKUL INI HARI INI
            $alreadyExists = ExtracurricularAttendance::where('extracurricular_id', $extraId)
                ->where('student_id', $student->id)
                ->where('date', $todayDate)
                ->exists();

            if ($alreadyExists) {
                // KEMBALIKAN ERROR (success: false) AGAR KIOSK MENOLAK (MUNCUL WARNA MERAH)
                return ['success' => false, 'code' => 409, 'message' => "SUDAH absen {$extra->name} hari ini!"];
            }

            // JIKA BELUM, SIMPAN DATA BARU
            $mainAtt = AttendanceSiswa::updateOrCreate(
                ['student_id' => $student->id, 'attendance_date' => $todayDate, 'type' => 'Extracurricular'],
                ['status' => 'Hadir', 'time_in' => $scanTime->toTimeString(), 'activity' => $extra->name]
            );

            ExtracurricularAttendance::create([
                'extracurricular_id' => $extraId, 
                'student_id' => $student->id, 
                'date' => $todayDate,
                'time_in' => $scanTime->toTimeString()
            ]);

            $isMember = ExtracurricularMember::where('student_id', $student->id)->where('extracurricular_id', $extraId)->exists();
            $points = $isMember ? 5 : 0;
            $statusPoin = $isMember ? "(+5 Poin)" : "(Tamu)";
            
            $this->logActivity($student, 'Extracurricular', $extra->name, "Hadir kegiatan {$extra->name}", $points);
            $msg = "Hadir {$extra->name} {$statusPoin}";

            $mainAtt->setAttribute('extra_name', $extra->name);
            return ['success' => true, 'code' => 200, 'status_text' => 'Hadir Ekskul', 'message' => $msg, 'model' => $mainAtt, 'type' => 'Extracurricular'];
        });
    }

    /**
     * Catat Aktivitas Kedisiplinan & Poin
     */
    public function logActivity($student, $type, $name, $desc, $points) 
    {
        ActivityLog::create([
            'student_id' => $student->id, 
            'activity_type' => $type,    // Wajib (Baru)
            'activity_name' => $name,
            'description' => $desc, 
            'point_earned' => $points
        ]);
        
        if($points != 0) {
            $student->increment('score', $points);
        }
    }
}