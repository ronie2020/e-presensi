<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSiswa;
use App\Models\Student;
use App\Models\Extracurricular;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Jobs\SendWaScanNotificationJob;
use App\Services\AttendanceService;

class AttendanceSiswaController extends Controller
{
    // Konfigurasi Jam 
    private $timeConfig = [
        'dhuha_start' => '07:30', 'dhuha_end' => '08:00',
        'makan_start' => '09:00', 'makan_end' => '10:00',
        'dhuhur_start' => '11:45', 'dhuhur_end' => '13:30',
    ];

    public function showScanner(AttendanceService $attendanceService)
    {
        $today = Carbon::today();
        $schedule = $attendanceService->getTodaySchedule($today);

        $defaultSchedule = [
            'start_in' => '06:00:00', 'end_in' => '07:00:00',
            'start_out'=> '14:00:00', 'end_out'=> '17:00:00'
        ];

        $scheduleConfig = array_merge($this->timeConfig, [
            'type'        => $schedule ? (get_class($schedule) == 'App\Models\ScheduleSpecial' ? 'Special' : 'Regular') : 'Regular',
            'is_holiday'  => $schedule ? ($schedule->is_holiday ?? false) : false,
            'description' => $schedule ? ($schedule->description ?? 'KBM Normal') : 'KBM Normal',
            'start_in'    => $schedule->start_in ?? $defaultSchedule['start_in'],
            'end_in'      => $schedule->end_in ?? $defaultSchedule['end_in'],
            'start_out'   => $schedule->start_out ?? $defaultSchedule['start_out'],
            'end_out'     => $schedule->end_out ?? $defaultSchedule['end_out'],
        ]);

        $statsConfig = [
            'total_target'  => Student::where('status', 'active')->count(),
            'current_taken' => AttendanceSiswa::whereDate('attendance_date', $today)->where('type', 'Meal')->count()
        ];

        $latestScans = AttendanceSiswa::with(['student:id,name,student_id,nisn'])
            ->whereDate('attendance_date', $today)->latest('updated_at')->limit(10)->get();

        $recentScans = $latestScans->map(fn($item) => $this->formatScanData($item));
        $extracurriculars = Extracurricular::orderBy('name')->get();

        return view('scan.index', compact('scheduleConfig', 'statsConfig', 'recentScans', 'extracurriculars'));
    }

    public function processScan(Request $request, AttendanceService $attendanceService)
    {
        $request->validate([
            'student_id' => 'required|string|max:50',
            'type'       => 'required|in:Masuk,Pulang,Dhuha,Dhuhur,Ekstrakurikuler,Makan', 
            'extra_id'   => 'nullable|exists:extracurriculars,id',
            'lat' => 'nullable', 'long' => 'nullable',
        ]);

        $student = Student::where('student_id', $request->student_id)->orWhere('nisn', $request->student_id)->first();
        if (!$student) return response()->json(['message' => 'Data siswa tidak ditemukan di database!'], 404);
        if ($student->status !== 'active') return response()->json(['message' => 'Status siswa tidak aktif!'], 403);

        $scanTime = Carbon::now();
        $schedule = $attendanceService->getTodaySchedule($scanTime);

        try {
            switch ($request->type) {
                case 'Masuk':
                case 'Pulang':
                    // Panggil mode 'guru' (Mengizinkan pulang awal / masuk telat tanpa pemblokiran layar merah)
                    $res = $attendanceService->processDailyScan($student, $scanTime, $request->lat, $request->long, $schedule, 'guru');
                    break;
                case 'Dhuha':
                case 'Dhuhur':
                    $res = $attendanceService->processReligious($student, $request->type, $scanTime);
                    break;
                case 'Makan': 
                    $res = $attendanceService->processMeal($student, $scanTime);
                    break;
                case 'Ekstrakurikuler':
                    $res = $attendanceService->processExtra($student, $request->extra_id, $scanTime);
                    break;
                default:
                    return response()->json(['message' => 'Tipe scan tidak valid'], 400);
            }

             if (!$res['success']) {
                return response()->json(['message' => $res['message']], $res['code']);
            }

            // Notif WA (Menambahkan Log::error agar pesan kegagalan tercatat, bukan disembunyikan)
            try { 
                SendWaScanNotificationJob::dispatch($res['model'])->afterCommit(); 
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('WA Notification Failed via Guru: ' . $e->getMessage());
            }

            return $this->successResponse($student, $res['status_text'], $student->name . ' - ' . $res['message'], $request->type, $res['model'], ['taken' => $res['taken'] ?? 0]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }

    // --- HELPER FUNCTIONS ---
    private function logActivity($student, $type, $name, $desc, $points) {
        ActivityLog::create([
            'student_id' => $student->id, 
            'activity_type' => $type, 
            'activity_name' => $name,
            'description' => $desc, 
            'point_earned' => $points
        ]);
        if($points != 0) $student->increment('score', $points);
    }

    private function successResponse($student, $status, $message, $type, $attModel, $stats = []) {
        $scanData = $this->formatScanData($attModel);
        $scanData['status'] = $status;
        if(isset($attModel->extra_name)) $scanData['ekskul_name'] = $attModel->extra_name;

        return response()->json(['message' => $message, 'scan' => $scanData, 'stats' => $stats]);
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
            'data_harian'  => in_array($item->type, ['Harian', 'Masuk', 'Pulang']),
            'data_makan'   => in_array($item->type, ['Meal', 'Makan']),
            'data_dhuha'   => ($item->type == 'Keagamaan' && $act == 'Dhuha'),
            'data_dhuhur'  => ($item->type == 'Keagamaan' && $act == 'Dhuhur'),
            'data_ekskul'  => ($item->type == 'Extracurricular'),
            'ekskul_name'  => ($item->type == 'Extracurricular') ? $act : '-',
            'makan_time'   => ($item->type == 'Meal') ? $item->time_in : null,
            'dhuha_time'   => ($item->type == 'Keagamaan' && $act == 'Dhuha') ? $item->time_in : null,
            'dhuhur_time'  => ($item->type == 'Keagamaan' && $act == 'Dhuhur') ? $item->time_in : null,
            'ekskul_time'  => ($item->type == 'Extracurricular') ? $item->time_in : null,
        ];
    }
}