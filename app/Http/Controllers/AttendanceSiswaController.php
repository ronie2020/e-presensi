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
use Illuminate\Support\Facades\DB;
use App\Jobs\SendWaScanNotificationJob;

class AttendanceSiswaController extends Controller
{
    // Konfigurasi Jam 
    private $timeConfig = [
        'dhuha_start' => '07:30', 'dhuha_end' => '08:00',
        'makan_start' => '09:00', 'makan_end' => '10:00',
        'dhuhur_start' => '11:45', 'dhuhur_end' => '13:30',
    ];

    public function showScanner()
    {
        $today = Carbon::today();
        $schedule = $this->getTodaySchedule($today);

        // Default Config jika tidak ada jadwal
        $defaultSchedule = [
            'start_in' => '06:00:00', 'end_in' => '07:00:00',
            'start_out'=> '14:00:00', 'end_out'=> '17:00:00'
        ];

        // Merge Schedule Data
        $scheduleConfig = array_merge($this->timeConfig, [
            'type'        => $schedule ? ($schedule instanceof ScheduleSpecial ? 'Special' : 'Regular') : 'Regular',
            'is_holiday'  => $schedule ? ($schedule->is_holiday ?? false) : false,
            'description' => $schedule ? ($schedule->description ?? 'KBM Normal') : 'KBM Normal',
            'start_in'    => $schedule->start_in ?? $defaultSchedule['start_in'],
            'end_in'      => $schedule->end_in ?? $defaultSchedule['end_in'],
            'start_out'   => $schedule->start_out ?? $defaultSchedule['start_out'],
            'end_out'     => $schedule->end_out ?? $defaultSchedule['end_out'],
        ]);

        // Statistik Makan (Optimized Count)
        $statsConfig = [
            'total_target'  => Student::where('status', 'active')->count(),
            'current_taken' => AttendanceSiswa::whereDate('attendance_date', $today)
                                ->where('type', 'Meal')
                                ->count()
        ];

        // Ambil Data Scan Terakhir (Optimized Eager Loading)
        $latestScans = AttendanceSiswa::with(['student:id,name,student_id,nisn'])
            ->whereDate('attendance_date', $today)
            ->latest('updated_at')
            ->limit(10)
            ->get();

        // Transformasi Data untuk Frontend
        $recentScans = $latestScans->map(function($item) {
            return $this->formatScanData($item);
        });

        $extracurriculars = Extracurricular::orderBy('name')->get();

        return view('scan.index', compact('scheduleConfig', 'statsConfig', 'recentScans', 'extracurriculars'));
    }

    public function processScan(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string|max:50',
            'type'       => 'required|in:Masuk,Pulang,Dhuha,Dhuhur,Ekstrakurikuler,Makan', 
            'extra_id'   => 'nullable|exists:extracurriculars,id',
            'lat' => 'nullable', 'long' => 'nullable',
        ]);

        $today = Carbon::today();
        
        // 1. Cari siswa (Optimized)
        // Menggunakan pencarian ID atau NISN
        $student = Student::where(function($q) use ($request) {
                        $q->where('student_id', $request->student_id)
                          ->orWhere('nisn', $request->student_id);
                    })
                    ->first();

        // 2. Logika Validasi Status Siswa yang Spesifik
        if (!$student) {
            return response()->json(['message' => 'Data siswa tidak ditemukan di database!'], 404);
        }

        if ($student->status !== 'active') {
             return response()->json(['message' => 'Status siswa tidak aktif / Non-aktif!'], 403);
        }

        try {
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
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }

    // --- PRIVATE METHODS (LOGIC ISOLATION) ---

    private function processAttendance($student, $type, $request, $today)
    {
        $schedule = $this->getTodaySchedule($today);
        $scheduleLimit = $schedule->end_in ?? '07:15:00'; 
        $scheduleStartOut = $schedule->start_out ?? '14:00:00'; 

        return DB::transaction(function () use ($student, $type, $request, $today, $scheduleLimit, $scheduleStartOut) {
            
            // LOCK ROW: Mencegah double scan (Race Condition)
            $attendance = AttendanceSiswa::where('student_id', $student->id)
                ->where('attendance_date', $today->toDateString())
                ->where('type', 'Harian')
                ->lockForUpdate() 
                ->first();

            $now = Carbon::now();
            $timeString = $now->format('H:i:s');

            // --- MASUK ---
            if ($type == 'Masuk') {
                if ($attendance && $attendance->time_in && $attendance->time_in != '00:00:00') {
                    return response()->json(['message' => $student->name . ' sudah absen masuk hari ini pada ' . substr($attendance->time_in, 0, 5)], 422);
                }

                $limitTime = Carbon::createFromTimeString($scheduleLimit);
                $isLate = $now->gt($limitTime);
                $status = $isLate ? 'Terlambat' : 'Hadir';

                // OPTIMASI: Langsung simpan data yang valid tanpa membuat record Alfa sementara
                if (!$attendance) {
                    $attendance = AttendanceSiswa::create([
                        'student_id'      => $student->id, 
                        'attendance_date' => $today->toDateString(),
                        'type'            => 'Harian',
                        'status'          => $status,
                        'time_in'         => $timeString,
                        'lat_in'          => $request->lat,
                        'long_in'         => $request->long,
                        'notes'           => $isLate ? "Terlambat (Limit: $scheduleLimit)" : null
                    ]);
                } else {
                    $attendance->update([
                        'time_in' => $timeString,
                        'status'  => $status,
                        'lat_in'  => $request->lat,
                        'long_in' => $request->long,
                        'notes'   => $isLate ? "Terlambat (Limit: $scheduleLimit)" : null
                    ]);
                }

                if ($isLate) {
                    $this->logActivity($student, 'Violation', 'Terlambat Masuk', "Terlambat hadir (Limit: {$scheduleLimit})", -5);
                    
                    // =========================================================
                    // TRIGGER SISTEM E-COUNSELING JIKA TERLAMBAT
                    // =========================================================
                    $student->checkBkThresholds();
                }

                SendWaScanNotificationJob::dispatch($attendance)->afterCommit();

                return $this->successResponse($student, $status, $isLate ? "{$student->name} Terlambat Masuk!" : "{$student->name} Berhasil Absen Masuk", 'Harian', $attendance);
            }

            // --- PULANG ---
            if ($type == 'Pulang') {
                // Jika belum masuk tapi mau pulang
                if (!$attendance || !$attendance->time_in || $attendance->time_in == '00:00:00') {
                    return response()->json(['message' => $student->name . ' belum melakukan absen masuk!'], 422);
                }
                if ($attendance->time_out) {
                    return response()->json(['message' => $student->name . ' sudah absen pulang hari ini!'], 422);
                }

                $startOutTime = Carbon::createFromTimeString($scheduleStartOut);
                $isEarly = $now->lt($startOutTime);
                
                $attendance->update([
                    'time_out' => $timeString,
                    'lat_out'  => $request->lat,
                    'long_out' => $request->long,
                    'notes'    => $isEarly ? ($attendance->notes ? $attendance->notes . " | Pulang Cepat" : "Pulang Cepat") : $attendance->notes
                ]);

                SendWaScanNotificationJob::dispatch($attendance)->afterCommit();

                return $this->successResponse($student, $isEarly ? 'Pulang Cepat' : 'Pulang', "{$student->name} Berhasil Absen Pulang", 'Harian', $attendance);
            }
        });
    }

    private function processReligious($student, $type, $today)
    {
        return DB::transaction(function () use ($student, $type, $today) {
            $exists = AttendanceSiswa::where('student_id', $student->id)
                ->where('attendance_date', $today->toDateString())
                ->where('type', 'Keagamaan')
                ->where('activity', $type)
                ->lockForUpdate()
                ->exists();

            if ($exists) return response()->json(['message' => $student->name . " sudah absen shalat {$type} hari ini!"], 422);

            $att = AttendanceSiswa::create([
                'student_id' => $student->id, 'attendance_date' => $today->toDateString(),
                'type' => 'Keagamaan', 'activity' => $type, 'status' => 'Hadir',
                'time_in' => now()->format('H:i:s'), 'notes' => 'Scan QR Mandiri'
            ]);

            $this->logActivity($student, 'Religious', "Shalat $type", "Melaksanakan shalat $type berjamaah", 5);
            
            // Update Habit
            $colName = ($type == 'Dhuha') ? 'prayer_dhuha' : 'prayer_dzuhur';
            StudentHabit::updateOrCreate(
                ['student_id' => $student->id, 'report_date' => $today->toDateString()],
                [$colName => true]
            );

            // =========================================================
            // TRIGGER SISTEM E-COUNSELING (POIN KEBAIKAN IBADAH)
            // =========================================================
            $student->checkBkThresholds();

            return $this->successResponse($student, 'Selesai', "Shalat {$type} {$student->name} Tercatat (+5 Poin)", $type, $att);
        });
    }

    private function processMeal($student, $today)
    {
        return DB::transaction(function () use ($student, $today) {
            $existing = AttendanceSiswa::where('student_id', $student->id)
                ->where('attendance_date', $today->toDateString())
                ->where('type', 'Meal')
                ->lockForUpdate()
                ->first();

            if ($existing) return response()->json(['message' => $student->name . " SUDAH mengambil makan siang!"], 422);

            $att = AttendanceSiswa::create([
                'student_id' => $student->id, 'attendance_date' => $today->toDateString(),
                'type' => 'Meal', 'status' => 'Hadir',
                'time_in' => now()->format('H:i:s'), 'activity' => 'Makan Siang'
            ]);

            $this->logActivity($student, 'Meal', 'Makan Bergizi', "Mengambil jatah makan siang", 2);

            StudentHabit::updateOrCreate(
                ['student_id' => $student->id, 'report_date' => $today->toDateString()],
                ['habit_5' => true, 'habit_5_menu' => 'Menu Sekolah (MBG)']
            );

            $count = AttendanceSiswa::whereDate('attendance_date', $today)->where('type', 'Meal')->count();

            return $this->successResponse($student, 'Ambil Makan', "{$student->name} Berhasil Ambil Makan", 'Makan', $att, ['taken' => $count]);
        });
    }

    private function processExtra($student, $extraId, $today)
    {
        if (!$extraId) return response()->json(['message' => 'Pilih kegiatan ekstrakurikuler dulu!'], 422);
        
        $extra = Extracurricular::find($extraId);
        if (!$extra) return response()->json(['message' => 'Data Ekskul tidak valid'], 422);

        return DB::transaction(function () use ($student, $extra, $extraId, $today) {
            // Catat di tabel Absensi Utama
            $mainAtt = AttendanceSiswa::updateOrCreate(
                ['student_id' => $student->id, 'attendance_date' => $today->toDateString(), 'type' => 'Extracurricular'],
                ['status' => 'Hadir', 'time_in' => now()->format('H:i:s'), 'activity' => $extra->name]
            );

            // Catat di tabel Detail Ekskul
            $detailAtt = ExtracurricularAttendance::firstOrCreate(
                ['extracurricular_id' => $extraId, 'student_id' => $student->id, 'date' => $today->toDateString()],
                ['status' => 'Hadir', 'time_in' => now()]
            );

            $msg = "{$student->name} sudah absen {$extra->name} sebelumnya.";
            
            // Logika Evaluasi Poin Ekskul (BUG FIX)
            if ($detailAtt->wasRecentlyCreated) {
                
                // Cek apakah siswa ini anggota resmi
                $isMember = ExtracurricularMember::where('student_id', $student->id)
                            ->where('extracurricular_id', $extraId)
                            ->exists();

                if ($isMember) {
                    // Anggota Resmi: Dapat +5 Poin
                    $this->logActivity($student, 'Extracurricular', $extra->name, "Hadir kegiatan {$extra->name}", 5);
                    $msg = "{$student->name} Hadir {$extra->name} (+5 Poin)";
                } else {
                    // Tamu (Bukan Anggota): Tercatat hadir tapi tidak dapat poin (0 Poin)
                    $this->logActivity($student, 'Extracurricular', $extra->name, "Hadir kegiatan {$extra->name} (Tamu)", 0);
                    $msg = "{$student->name} Hadir {$extra->name} (Tamu)";
                }
            }

            // Inject nama ekskul untuk frontend response
            $mainAtt->setAttribute('extra_name', $extra->name);

            return $this->successResponse($student, 'Hadir Ekskul', $msg, 'Extracurricular', $mainAtt);
        });
    }

    // --- HELPER FUNCTIONS ---

    private function logActivity($student, $type, $name, $desc, $points) {
        ActivityLog::create([
            'student_id' => $student->id, 'type' => $type, 'activity_name' => $name,
            'description' => $desc, 'point_earned' => $points
        ]);
        if($points != 0) $student->increment('score', $points);
    }

    private function successResponse($student, $status, $message, $type, $attModel, $stats = []) {
        $scanData = $this->formatScanData($attModel);
        // Override status agar feedback di UI sesuai konteks (misal: "Hadir Ekskul")
        $scanData['status'] = $status;
        if(isset($attModel->extra_name)) $scanData['ekskul_name'] = $attModel->extra_name;

        return response()->json([
            'message' => $message,
            'scan'    => $scanData,
            'stats'   => $stats
        ]);
    }

    private function formatScanData($item) {
        $studentName = $item->student->name ?? 'Siswa Tidak Dikenal';
        $studentId = $item->student->student_id ?? ($item->student->nisn ?? '-');
        $act = $item->activity;

        return [
            'student_name' => $studentName,
            'student_id'   => $studentId,
            'time_in'      => $item->time_in ? Carbon::parse($item->time_in)->format('H:i') : null,
            'time_out'     => $item->time_out ? Carbon::parse($item->time_out)->format('H:i') : null,
            'status'       => $item->status,
            'type_raw'     => $item->type,
            
            // Flags untuk Frontend Filtering
            'data_harian' => in_array($item->type, ['Harian', 'Masuk', 'Pulang']),
            'data_makan'  => in_array($item->type, ['Meal', 'Makan']),
            'data_dhuha'  => ($item->type == 'Keagamaan' && $act == 'Dhuha'),
            'data_dhuhur' => ($item->type == 'Keagamaan' && $act == 'Dhuhur'),
            'data_ekskul' => ($item->type == 'Extracurricular'),
            
            'ekskul_name' => ($item->type == 'Extracurricular') ? $act : '-',
            // Helper spesifik agar kolom tabel terisi benar
            'makan_time'  => ($item->type == 'Meal') ? $item->time_in : null,
            'dhuha_time'  => ($item->type == 'Keagamaan' && $act == 'Dhuha') ? $item->time_in : null,
            'dhuhur_time' => ($item->type == 'Keagamaan' && $act == 'Dhuhur') ? $item->time_in : null,
            'ekskul_time' => ($item->type == 'Extracurricular') ? $item->time_in : null,
        ];
    }

    private function getTodaySchedule($date)
    {
        $schedule = ScheduleSpecial::where('date', $date->toDateString())->first();
        if (!$schedule) {
            $dayName = $date->locale('id')->isoFormat('dddd'); 
            // Fallback manual jika server locale bahasa inggris
            $enToId = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
            if(isset($enToId[$date->format('l')])) $dayName = $enToId[$date->format('l')];

            $schedule = ScheduleRegular::where('day_name', $dayName == 'Jumat' ? 'Jumat' : 'Biasa')->first();
        }
        return $schedule;
    }
}