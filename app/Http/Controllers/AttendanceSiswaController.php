<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSiswa;
use App\Models\Student;
use App\Models\ScheduleRegular;
use App\Models\ScheduleSpecial;
use App\Models\Extracurricular;
use App\Models\ExtracurricularMember;
use App\Models\ExtracurricularAttendance;
use App\Models\StudentHabit;
use App\Models\ActivityLog;
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
        
        // 1. Cek Jadwal Khusus
        $schedule = ScheduleSpecial::where('date', $today->toDateString())->first();
        
        // 2. Jika tidak ada, Cek Jadwal Regular
        if (!$schedule) {
            $dayName = $today->locale('en')->dayName;
            $schedule = ScheduleRegular::where('day_name', $dayName)->first();
        }

        $scheduleConfig = [
            'type' => $schedule ? ($schedule instanceof ScheduleSpecial ? 'Special' : 'Regular') : 'Regular',
            'is_holiday' => $schedule ? ($schedule->is_holiday ?? false) : false,
            'description' => $schedule ? ($schedule->description ?? 'KBM Normal') : 'KBM Normal',
            'start_in' => $schedule ? $schedule->start_in : '06:00',
            'end_in'   => $schedule ? $schedule->end_in : '07:00',
            'start_out'=> $schedule ? $schedule->start_out : '14:00',
            'end_out'  => $schedule ? $schedule->end_out : '17:00',
            'dhuha_start' => '07:30', 'dhuha_end' => '10:00',
            'dhuhur_start' => '11:45', 'dhuhur_end' => '13:30',
            'makan_start' => '11:00', 'makan_end' => '13:00', 
        ];

        return view('scan.index', compact('scheduleConfig'));
    }

    /**
     * PROSES UTAMA SCAN QR
     */
    public function processScan(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'type' => 'required|in:Masuk,Pulang,Dhuha,Dhuhur,Ekstrakurikuler,Makan', 
            'extra_id' => 'nullable|exists:extracurriculars,id',
            'lat' => 'nullable',
            'long' => 'nullable',
        ]);

        $today = Carbon::today();
        
        // Cari siswa berdasarkan student_id ATAU nisn
        $student = Student::where('student_id', $request->student_id)
                    ->orWhere('nisn', $request->student_id)
                    ->first();

        if (!$student) {
            return response()->json(['message' => 'Siswa tidak ditemukan!'], 404);
        }

        switch ($request->type) {
            case 'Masuk':
            case 'Pulang':
                return $this->processAttendance($student, $request->type, $request, $today);
            
            case 'Dhuha':
            case 'Dhuhur':
                return $this->processReligious($student, $request->type, $today);

            case 'Makan': 
                return $this->processMeal($student, $today);

            case 'Ekstrakurikuler':
                return $this->processExtra($student, $request->extra_id, $today);
                
            default:
                return response()->json(['message' => 'Tipe scan tidak valid'], 400);
        }
    }

    /**
     * LOGIKA ABSENSI KBM (HARIAN)
     */
    private function processAttendance($student, $type, $request, $today)
    {
        // PERBAIKAN: Menambahkan 'type' => 'Harian' pada kriteria pencarian (array pertama)
        // Ini MENCEGAH sistem mengambil data 'Keagamaan' jika siswa sudah scan Dhuha sebelumnya
        $attendance = AttendanceSiswa::firstOrCreate(
            [
                'student_id' => $student->id, 
                'attendance_date' => $today->toDateString(),
                'type' => 'Harian' // <--- PENAMBAHAN KRITIKAL DI SINI
            ],
            [
                'status' => 'Alfa',
                // 'type' => 'Harian', // Tidak perlu di sini jika sudah ada di atas
                'time_in' => '00:00:00'
            ]
        );

        $now = Carbon::now();
        $timeString = $now->format('H:i:s');

        // Ambil Batas Waktu
        $scheduleLimit = '07:00:00'; 
        $special = ScheduleSpecial::where('date', $today->toDateString())->first();
        if ($special) {
            $scheduleLimit = $special->end_in; 
        } else {
            $dayName = $today->locale('en')->dayName;
            $regular = ScheduleRegular::where('day_name', $dayName)->first();
            if ($regular) {
                $scheduleLimit = $regular->end_in;
            }
        }

        // Logic MASUK
        if ($type == 'Masuk') {
            if ($attendance->time_in && $attendance->time_in != '00:00:00') {
                return response()->json(['message' => 'Anda sudah absen masuk hari ini!'], 422);
            }

            $limitTime = Carbon::parse($scheduleLimit);
            $status = $now->gt($limitTime) ? 'Terlambat' : 'Hadir';

            $attendance->update([
                'time_in' => $timeString,
                'status' => $status,
                'lat_in' => $request->lat,
                'long_in' => $request->long,
                'type' => 'Harian'
            ]);

            try {
                SendWaScanNotificationJob::dispatch($attendance);
            } catch (\Exception $e) {
                Log::error("Gagal dispatch Job WA Masuk: " . $e->getMessage());
            }

            return response()->json([
                'message' => "Absen Masuk Berhasil ($status)",
                'scan' => [
                    'student_name' => $student->name,
                    'status' => $status,
                    'time' => $timeString
                ]
            ]);
        }

        // Logic PULANG
        elseif ($type == 'Pulang') {
            if (!$attendance->time_in || $attendance->time_in == '00:00:00') {
                return response()->json(['message' => 'Anda belum absen masuk!'], 422);
            }
            if ($attendance->time_out) {
                return response()->json(['message' => 'Anda sudah absen pulang hari ini!'], 422);
            }

            $attendance->update([
                'time_out' => $timeString,
                'lat_out' => $request->lat,
                'long_out' => $request->long,
            ]);

            try {
                SendWaScanNotificationJob::dispatch($attendance);
            } catch (\Exception $e) {
                Log::error("Gagal dispatch Job WA Pulang: " . $e->getMessage());
            }

            return response()->json([
                'message' => "Absen Pulang Berhasil. Hati-hati di jalan!",
                'scan' => [
                    'student_name' => $student->name,
                    'status' => 'Pulang',
                    'time' => $timeString
                ]
            ]);
        }
    }

    /**
     * LOGIKA RELIGI (DHUHA & DHUHUR) - DIPERBAIKI
     */
    private function processReligious($student, $type, $today)
    {
        // Cek duplikasi di ActivityLog (Logika lama tetap dipakai untuk validasi)
        $existingLog = ActivityLog::where('student_id', $student->id)
            ->where('activity_type', 'Religious')
            ->where('activity_name', 'Shalat ' . $type)
            ->whereDate('created_at', $today)
            ->first();

        if ($existingLog) {
            return response()->json(['message' => "Sudah absen shalat {$type} hari ini!"], 422);
        }

        // =========================================================================
        // [PERBAIKAN UTAMA] SIMPAN KE TABEL UTAMA (AttendanceSiswa)
        // Agar muncul di ReportController (Laporan Harian/Religious)
        // =========================================================================
        AttendanceSiswa::firstOrCreate(
            [
                'student_id' => $student->id,
                'attendance_date' => $today->toDateString(),
                'type' => 'Keagamaan', // Penting: Harus 'Keagamaan' agar terbaca di filter report
                'activity' => $type    // 'Dhuha' atau 'Dhuhur'
            ],
            [
                'status' => 'Hadir',
                'time_in' => Carbon::now()->format('H:i:s'),
                'notes' => 'Scan QR Mandiri'
            ]
        );

        // Simpan ke ActivityLog (Untuk Poin & Gamification)
        ActivityLog::create([
            'student_id' => $student->id,
            'activity_type' => 'Religious',
            'type' => 'Religious',
            'activity_name' => 'Shalat ' . $type,
            'description' => "Siswa melakukan shalat {$type} di sekolah",
            'point_earned' => 5
        ]);

        try {
            AddReligiousPointJob::dispatch($student, 5, "Shalat {$type}");
        } catch (\Exception $e) {
            $student->increment('score', 5);
        }

        // Simpan ke StudentHabit (Untuk Rekap Bulanan Habit)
        $habit = StudentHabit::firstOrCreate(
            ['student_id' => $student->id, 'report_date' => $today->toDateString()]
        );

        if ($type == 'Dhuha') $habit->prayer_dhuha = true;
        elseif ($type == 'Dhuhur') $habit->prayer_dzuhur = true;
        $habit->save();

        return response()->json([
            'message' => "Shalat {$type} Tercatat. Poin +5!",
            'scan' => [
                'student_name' => $student->name, 
                'student_id' => $student->student_id, 
                'status' => 'Selesai'
            ]
        ]);
    }

    /**
     * LOGIKA MAKAN
     */
    private function processMeal($student, $today)
    {
        $existingLog = ActivityLog::where('student_id', $student->id)
            ->where('activity_type', 'Meal')
            ->whereDate('created_at', $today)
            ->first();

        if ($existingLog) {
            return response()->json([
                'message' => "Siswa ini sudah mengambil jatah makan siang!",
                'status' => 'error'
            ], 422);
        }

        ActivityLog::create([
            'student_id' => $student->id,
            'activity_type' => 'Meal',
            'type' => 'Meal',
            'activity_name' => 'Makan Bergizi Gratis',
            'description' => "Siswa mengambil jatah makan siang",
            'point_earned' => 2
        ]);

        $student->increment('score', 2);

        $habit = StudentHabit::firstOrCreate(
            ['student_id' => $student->id, 'report_date' => $today->toDateString()]
        );
        $habit->habit_5 = true; 
        $habit->habit_5_menu = 'Menu Sekolah (MBG)'; 
        $habit->save();

        return response()->json([
            'message' => "Scan Makan Berhasil. Selamat Makan!",
            'type' => 'success',
            'scan' => [
                'student_name' => $student->name,
                'student_id' => $student->student_id,
                'status' => 'Ambil Makan'
            ]
        ]);
    }

    /**
     * LOGIKA EKSKUL
     */
    private function processExtra($student, $extraId, $today)
    {
        if (!$extraId) {
            return response()->json(['message' => 'Silakan pilih kegiatan ekstrakurikuler!'], 422);
        }

        $extra = Extracurricular::find($extraId);
        if (!$extra) {
            return response()->json(['message' => 'Ekstrakurikuler tidak valid!'], 422);
        }
        
        $isMember = ExtracurricularMember::where('student_id', $student->id)
            ->where('extracurricular_id', $extraId)
            ->exists();

        $attendance = ExtracurricularAttendance::firstOrCreate([
            'extracurricular_id' => $extraId,
            'student_id' => $student->id,
            'date' => $today->toDateString()
        ], [
            'status' => 'Hadir',
            'check_in_time' => now()
        ]);

        if ($attendance->wasRecentlyCreated) {
            ActivityLog::create([
                'student_id' => $student->id,
                'activity_type' => 'Extracurricular',
                'type' => 'Extracurricular',
                'activity_name' => $extra->name,
                'description' => "Hadir kegiatan ekstrakurikuler",
                'point_earned' => 5
            ]);
            $student->increment('score', 5);
        }

        $msg = $isMember ? "Absen {$extra->name} Berhasil!" : "Absen {$extra->name} Berhasil (Bukan Anggota Tetap)";

        return response()->json([
            'message' => $msg,
            'scan' => [
                'student_name' => $student->name,
                'status' => 'Hadir Ekskul'
            ]
        ]);
    }
}