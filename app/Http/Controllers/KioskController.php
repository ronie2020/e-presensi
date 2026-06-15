<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendWaScanNotificationJob; 
use App\Services\AttendanceService;
use App\Models\Extracurricular;

class KioskController extends Controller
{
   public function showKiosk()
    {
        $extracurriculars = Extracurricular::all(); 
        
        return view('kiosk.index', compact('extracurriculars'));
    }

    /**
     * Helper Function: Catat Aktivitas Kedisiplinan
     */
    public function processKioskScan(Request $request, AttendanceService $attendanceService)
    {
        $request->validate([
            'student_id' => 'required|string', 
            'type'       => 'nullable|string', 
            'extra_id'   => 'nullable',
            'lat'        => 'nullable', 
            'long'       => 'nullable',
            'time'       => 'nullable|date' // Mendukung waktu spesifik dari mode offline
        ]);

        $scanType = $request->type ?? 'Harian'; 
        
        // FIX TIMEZONE: Pastikan waktu dari Kiosk dikonversi ke zona waktu lokal (Asia/Jakarta)
        $scanTime = $request->time ? Carbon::parse($request->time)->setTimezone(config('app.timezone', 'Asia/Jakarta')) : Carbon::now();
        
        // 1. Cari Siswa
        $student = Student::where('student_id', $request->student_id)
                            ->orWhere('rfid_id', $request->student_id)
                            ->orWhere('nisn', $request->student_id)
                            ->first();
        
        if (!$student) return response()->json(['status' => 'error', 'message' => 'Siswa Tidak Ditemukan', 'student_name' => 'N/A'], 404);
        if ($student->status !== 'active') return response()->json(['status' => 'error', 'message' => 'Status siswa tidak aktif!', 'student_name' => $student->name, 'photo_path' => $student->photo_path ?? null], 403);

        // 2. Ambil Jadwal via Service
        $schedule = $attendanceService->getTodaySchedule($scanTime);
        if (!$schedule && $scanType == 'Harian') return response()->json(['status' => 'error', 'message' => 'Hari Libur / Tidak Ada Jadwal', 'student_name' => $student->name, 'photo_path' => $student->photo_path ?? null], 400); 

        try {
            // 3. Eksekusi Berdasarkan Tipe
            if ($scanType === 'Makan') {
                $res = $attendanceService->processMeal($student, $scanTime);
            } elseif (in_array($scanType, ['Dhuha', 'Dhuhur'])) {
                $res = $attendanceService->processReligious($student, $scanType, $scanTime);
            } elseif ($scanType === 'Ekstrakurikuler') {
                $res = $attendanceService->processExtra($student, $request->extra_id, $scanTime);
            } else {
                // PERBAIKAN: Beritahu sistem bahwa admin mem-Bypass secara manual (Pilih Masuk atau Pulang secara paksa)
                // Jika dari aplikasi kiosk tidak didefinisikan secara khusus (karena Auto), tetap serahkan ke 'kiosk'
                $modeForService = in_array($scanType, ['Masuk', 'Pulang']) ? $scanType : 'kiosk';
                $res = $attendanceService->processDailyScan($student, $scanTime, $request->lat, $request->long, $schedule, $modeForService);
            }

            if (!$res['success']) {
                return response()->json([
                    'status' => 'error', 
                    'message' => $res['message'], 
                    'student_name' => $student->name,
                    'photo_path' => $student->photo_path ?? null
                ], $res['code']);
            }

            // Notifikasi WA di-catch agar tidak merusak response JSON Kiosk
            try { SendWaScanNotificationJob::dispatch($res['model']); } catch (\Exception $e) { Log::warning("WA Failed: " . $e->getMessage()); }

            return response()->json([
                'status' => 'success',
                'message' => $res['message'],
                'student_name' => $student->name,
                'photo_path' => $student->photo_path ?? null,
                'time' => $scanTime->format('H:i'),
                'note' => $res['note'] ?? '',
                'scan' => $res['model'] 
            ], 200);

        } catch (\Exception $e) {
            Log::error('Kiosk Server Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'DB ERROR: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Endpoint API Khusus: Sinkronisasi Antrean Saat Offline (Background Sync)
     */
    public function syncBatch(Request $request, AttendanceService $attendanceService)
    {
        $scans = $request->input('scans', []);
        if (empty($scans)) return response()->json(['status' => 'success', 'message' => 'Tidak ada data'], 200);

        // Ambil semua ID unik dari request untuk menghindari N+1 Query
        $scanIds = collect($scans)->pluck('student_id')->unique()->toArray();
        
        // Tarik semua siswa yang relevan hanya dengan SATU query ke database
        $students = Student::whereIn('student_id', $scanIds)
                    ->orWhereIn('rfid_id', $scanIds)
                    ->orWhereIn('nisn', $scanIds)
                    ->where('status', 'active')
                    ->get();

        $processed = 0;

        foreach ($scans as $scan) {
            try {
                // Pencarian data siswa di memori (sangat cepat dibanding query database)
                $scannedId = $scan['student_id'];
                $student = $students->first(function($s) use ($scannedId) {
                    return $s->student_id == $scannedId || $s->rfid_id == $scannedId || $s->nisn == $scannedId;
                });

                if (!$student) continue;

                // FIX TIMEZONE: Konversi ke zona waktu lokal untuk data offline sinkronisasi
                $scanTime = Carbon::parse($scan['time'])->setTimezone(config('app.timezone', 'Asia/Jakarta'));
                $schedule = $attendanceService->getTodaySchedule($scanTime);
                $scanType = $scan['type'] ?? 'Harian';

                // Eksekusi tanpa melempar output agar loop tidak berhenti
                if ($scanType === 'Makan') {
                    $attendanceService->processMeal($student, $scanTime);
                } elseif (in_array($scanType, ['Dhuha', 'Dhuhur'])) {
                    $attendanceService->processReligious($student, $scanType, $scanTime);
                } elseif ($scanType === 'Ekstrakurikuler') {
                    $attendanceService->processExtra($student, $scan['extra_id'] ?? null, $scanTime);
                } else {
                    // Terapkan Logika Bypass yang sama di Offline mode
                    $modeForService = in_array($scanType, ['Masuk', 'Pulang']) ? $scanType : 'kiosk';
                    $attendanceService->processDailyScan($student, $scanTime, null, null, $schedule, $modeForService);
                }
                $processed++;
            } catch (\Exception $e) {
                Log::error("Offline Sync Error pada siswa {$scan['student_id']}: " . $e->getMessage());
            }
        }

        return response()->json(['status' => 'success', 'message' => "Tersinkron {$processed} data dari offline mode."], 200);
    }
}