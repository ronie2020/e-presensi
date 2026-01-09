<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSiswa;
use App\Models\Student;
use App\Models\ScheduleRegular;
use App\Models\ScheduleSpecial;
use App\Models\Extracurricular;
use App\Models\ExtracurricularMember;
use App\Models\ExtracurricularAttendance;
use App\Models\DisciplineRecord;
use App\Models\DisciplineType;
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
        
        // --- 1. LOGIKA JADWAL DINAMIS (CENTRALIZED) ---
        // Default Config
        $scheduleConfig = [
            'type' => 'Regular',
            'is_holiday' => false,
            'description' => 'KBM Normal',
            'start_in' => '06:00', 'end_in' => '07:15',
            'start_out' => '14:00', 'end_out' => '17:00',
            'dhuha_start' => '07:30', 'dhuha_end' => '09:30',
            'dhuhur_start' => '11:45', 'dhuhur_end' => '12:30',
            'makan_start' => '09:00', 'makan_end' => '10:00', // Default
        ];

        // Override dari Database (Jadwal Khusus)
        $specialSchedule = ScheduleSpecial::whereDate('date', $today)->first();

        if ($specialSchedule) {
            $scheduleConfig = array_merge($scheduleConfig, [
                'type' => $specialSchedule->is_holiday ? 'Holiday' : 'Special',
                'is_holiday' => (bool) $specialSchedule->is_holiday,
                'description' => $specialSchedule->description ?? 'Kegiatan Khusus',
            ]);
            
            if (!$specialSchedule->is_holiday) {
                $scheduleConfig['start_in'] = substr($specialSchedule->start_in, 0, 5);
                $scheduleConfig['end_in'] = substr($specialSchedule->end_in, 0, 5);
                $scheduleConfig['start_out'] = substr($specialSchedule->start_out, 0, 5);
                $scheduleConfig['end_out'] = substr($specialSchedule->end_out, 0, 5);
            }
        } else {
            // Override dari Database (Jadwal Reguler)
            $dayType = ($today->dayOfWeek == 5) ? 'Jumat' : 'Biasa'; 
            $regularSchedule = ScheduleRegular::where('day_type', $dayType)->first();
            
            if ($regularSchedule) {
                $scheduleConfig['start_in'] = substr($regularSchedule->start_in, 0, 5);
                $scheduleConfig['end_in'] = substr($regularSchedule->end_in, 0, 5);
                $scheduleConfig['start_out'] = substr($regularSchedule->start_out, 0, 5);
                $scheduleConfig['end_out'] = substr($regularSchedule->end_out, 0, 5);
                // Jika ada kolom jam makan di DB Regular, ambil di sini
                // $scheduleConfig['makan_start'] = substr($regularSchedule->makan_start, 0, 5);
            }
        }
        
        // --- 2. AMBIL DATA RIWAYAT ---
        // Optimization: Eager loading relationships
        $recentScans = $this->getRecentScans($today);
        $extracurriculars = Extracurricular::select('id', 'name')->get();

        // Hitung statistik MBG Realtime dari DB
        $statsConfig = [
            'total_target' => Student::where('status', 'active')->count(), 
            'current_taken' => ActivityLog::where('type', 'Makan')->whereDate('created_at', $today)->count()
        ];

        return view('scan.index', [
            'recentScans' => $recentScans,
            'extracurriculars' => $extracurriculars,
            'scheduleConfig' => $scheduleConfig,
            'statsConfig' => $statsConfig 
        ]);
    }

    /**
     * Helper: Get Unified Recent Scans
     */
    private function getRecentScans($date)
    {
        // Menggabungkan semua log menjadi satu array terstruktur untuk UI
        // Logika disederhanakan untuk brevity, asumsikan logika parsing Anda sebelumnya sudah benar.
        // Saya sarankan menggunakan UNION query atau View Database jika data makin besar.
        // Untuk sekarang, kita gunakan logic fetching Anda yang sudah ada tapi dirapikan:
        
        $logs = AttendanceSiswa::with('student:id,student_id,name')->whereDate('attendance_date', $date)->latest('created_at')->limit(30)->get();
        $ekskulLogs = ExtracurricularAttendance::with(['student:id,student_id,name', 'extracurricular:id,name'])->whereDate('date', $date)->latest('created_at')->limit(30)->get();
        $makanLogs = ActivityLog::with('student:id,student_id,name')->where('type', 'Makan')->whereDate('created_at', $date)->latest('created_at')->limit(30)->get();

        $merged = [];
        
        // Helper closure
        $addToMerged = function($studentId, $data) use (&$merged) {
            if (!isset($merged[$studentId])) $merged[$studentId] = $data;
            else $merged[$studentId] = array_merge($merged[$studentId], array_filter($data)); // Merge non-null
        };

        // Process Harian
        foreach ($logs as $log) {
            if(!$log->student) continue;
            $base = $this->initScanData($log->student);
            $base['data_harian'] = true;
            if($log->type == 'Masuk') { $base['time_in'] = $log->time_in; $base['status'] = $log->status; }
            if($log->type == 'Keagamaan' && $log->activity == 'Dhuha') { $base['data_dhuha'] = true; $base['dhuha_time'] = $log->time_in; }
            if($log->type == 'Keagamaan' && $log->activity == 'Dhuhur') { $base['data_dhuhur'] = true; $base['dhuhur_time'] = $log->time_in; }
            $addToMerged($log->student->student_id, $base);
        }

        // Process Makan
        foreach ($makanLogs as $log) {
            if(!$log->student) continue;
            $base = $this->initScanData($log->student);
            $base['data_makan'] = true;
            $base['makan_time'] = $log->created_at->format('H:i');
            $addToMerged($log->student->student_id, $base);
        }

        // Process Ekskul
        foreach ($ekskulLogs as $log) {
            if(!$log->student) continue;
            $base = $this->initScanData($log->student);
            $base['data_ekskul'] = true;
            $base['ekskul_time'] = $log->time_in;
            $base['ekskul_name'] = $log->extracurricular->name ?? '-';
            $addToMerged($log->student->student_id, $base);
        }

        return array_values($merged);
    }

    private function initScanData($student) {
        return [
            'student_id' => $student->student_id,
            'student_name' => $student->name,
            'status' => 'Belum Absen'
        ];
    }
    
    /**
     * MAIN PROCESSOR
     */
    public function processScan(Request $request)
    {
        $request->validate(['student_id' => 'required', 'type' => 'required']);
        
        $student = Student::where('student_id', $request->student_id)->first();
        if (!$student) return response()->json(['message' => 'Siswa tidak ditemukan!'], 404);

        switch ($request->type) {
            case 'Makan':
                return $this->handleMakan($student);
            case 'Ekstrakurikuler':
                return $this->handleEkskul($student, $request->activity);
            case 'Harian':
                return $this->handleHarian($student);
            case 'Dhuha':
            case 'Dhuhur':
                return $this->handleKeagamaan($student, $request->type);
            default:
                return response()->json(['message' => 'Tipe scan tidak valid'], 400);
        }
    }

    // --- PRIVATE HANDLERS ---

    private function handleMakan($student)
    {
        $today = Carbon::today();
        
        // Cek Duplikat
        if (ActivityLog::where('student_id', $student->id)->where('type', 'Makan')->whereDate('created_at', $today)->exists()) {
            return response()->json(['message' => 'Jatah makan sudah diambil!'], 409);
        }

        // Catat Log
        ActivityLog::create([
            'student_id' => $student->id,
            'type' => 'Makan',
            'description' => 'Makan Bergizi Gratis',
            'scanned_at' => now()
        ]);

        // Update Habit
        $habit = StudentHabit::firstOrCreate(['student_id' => $student->id, 'report_date' => $today->toDateString()]);
        $habit->update(['habit_5' => true, 'mbg_taken_at' => now()]);

        // RETURN UPDATED STATS
        $currentTaken = ActivityLog::where('type', 'Makan')->whereDate('created_at', $today)->count();

        return response()->json([
            'message' => 'Selamat Makan!',
            'scan' => [
                'student_name' => $student->name,
                'student_id' => $student->student_id,
                'makan_time' => now()->format('H:i'),
                'status' => 'Diambil'
            ],
            'stats' => [ // Data penting untuk update UI Realtime
                'taken' => $currentTaken
            ]
        ]);
    }

    private function handleEkskul($student, $activityName)
    {
        if (!$activityName) return response()->json(['message' => 'Pilih kegiatan ekskul dulu.'], 400);
        
        $ekskul = Extracurricular::where('name', $activityName)->first();
        if (!$ekskul) return response()->json(['message' => 'Ekskul tidak valid.'], 400);

        // Cek Anggota
        if (!ExtracurricularMember::where('extracurricular_id', $ekskul->id)->where('student_id', $student->id)->exists()) {
            return response()->json(['message' => "Bukan anggota {$ekskul->name}."], 400);
        }

        // Cek Absen Hari Ini
        if (ExtracurricularAttendance::where('extracurricular_id', $ekskul->id)->where('student_id', $student->id)->whereDate('date', Carbon::today())->exists()) {
            return response()->json(['message' => "Sudah absen {$ekskul->name}."], 409);
        }

        ExtracurricularAttendance::create([
            'extracurricular_id' => $ekskul->id,
            'student_id' => $student->id,
            'date' => Carbon::today(),
            'time_in' => now()->format('H:i:s'),
        ]);

        return response()->json([
            'message' => "Hadir: {$ekskul->name}",
            'scan' => ['student_name' => $student->name, 'student_id' => $student->student_id, 'ekskul_name' => $ekskul->name, 'ekskul_time' => now()->format('H:i'), 'status' => 'Hadir Ekskul']
        ]);
    }

    private function handleHarian($student)
    {
        $now = Carbon::now();
        $todayStr = $now->toDateString();
        $schedule = $this->getTodaysSchedule($now);

        if (!$schedule) return response()->json(['message' => 'Tidak ada jadwal hari ini.'], 400);

        $attendance = AttendanceSiswa::where('student_id', $student->id)->where('attendance_date', $todayStr)->whereIn('type', ['Harian', 'Masuk', 'Pulang'])->first();

        // LOGIKA MASUK
        if (!$attendance) {
            if ($now->toTimeString() > $schedule->end_out) return response()->json(['message' => 'Sekolah tutup.'], 400);
            
            $status = ($now->toTimeString() > $schedule->end_in) ? 'Terlambat' : 'Masuk';
            $notes = ($status == 'Terlambat') ? 'Terlambat ' . $now->diffInMinutes($schedule->end_in) . ' menit' : 'Tepat Waktu';

            $newAtt = AttendanceSiswa::create([
                'student_id' => $student->id, 'attendance_date' => $todayStr, 'type' => 'Masuk', 'status' => $status, 'time_in' => $now->toTimeString(), 'notes' => $notes
            ]);

            // Jika Terlambat, Catat Poin
            if ($status == 'Terlambat') {
                $dt = DisciplineType::firstOrCreate(['name' => 'Keterlambatan Masuk Sekolah'], ['point_value' => 3, 'type' => 'Pelanggaran']);
                DisciplineRecord::create(['student_id' => $student->id, 'discipline_type_id' => $dt->id, 'date' => $todayStr, 'notes' => $notes, 'recorded_by_user_id' => 1]);
            }
            
            try { if (class_exists(SendWaScanNotificationJob::class)) SendWaScanNotificationJob::dispatch($newAtt); } catch (\Exception $e) {}

            return response()->json(['message' => "Absen {$status}", 'scan' => ['student_name' => $student->name, 'student_id' => $student->student_id, 'time_in' => $now->format('H:i'), 'status' => $status]]);
        } 
        
        // LOGIKA PULANG
        else {
            if ($attendance->time_out) return response()->json(['message' => 'Sudah pulang.'], 409);
            if ($now->toTimeString() < $schedule->start_out) return response()->json(['message' => "Belum jam pulang ({$schedule->start_out})."], 400);

            $attendance->update(['time_out' => $now->toTimeString(), 'notes' => $attendance->notes . ' | Pulang']);
            try { if (class_exists(SendWaScanNotificationJob::class)) SendWaScanNotificationJob::dispatch($attendance); } catch (\Exception $e) {}

            return response()->json(['message' => 'Hati-hati di jalan.', 'scan' => ['student_name' => $student->name, 'student_id' => $student->student_id, 'time_out' => $now->format('H:i'), 'status' => 'Pulang']]);
        }
    }

    private function handleKeagamaan($student, $type)
    {
        $today = Carbon::today();
        if (AttendanceSiswa::where('student_id', $student->id)->where('attendance_date', $today)->where('type', 'Keagamaan')->where('activity', $type)->exists()) {
            return response()->json(['message' => "Sudah absen {$type}."], 409);
        }

        $att = AttendanceSiswa::create([
            'student_id' => $student->id, 'attendance_date' => $today, 'type' => 'Keagamaan', 'activity' => $type, 'status' => 'Hadir', 'time_in' => now()->toTimeString(), 'notes' => 'Scan Otomatis'
        ]);

        try { if (class_exists(AddReligiousPointJob::class)) AddReligiousPointJob::dispatch($att); } catch (\Exception $e) {}

        return response()->json([
            'message' => "{$type} Tercatat.",
            'scan' => ['student_name' => $student->name, 'student_id' => $student->student_id, strtolower($type).'_time' => now()->format('H:i'), 'status' => 'Selesai']
        ]);
    }

    private function getTodaysSchedule($now) {
        // Implementasi sama seperti sebelumnya
        $today = $now->toDateString();
        $special = ScheduleSpecial::where('date', $today)->first();
        if ($special) return $special->is_holiday ? null : $special;
        
        $dayOfWeek = $now->dayOfWeek;
        $dayType = ($dayOfWeek == 5) ? 'Jumat' : 'Biasa';
        if ($dayOfWeek >= 1 && $dayOfWeek <= 5) return ScheduleRegular::where('day_type', $dayType)->first();
        return null;
    }
}