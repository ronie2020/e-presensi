<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CbtExam;
use App\Models\Student;
use Illuminate\Support\Str;

class CbtMonitoringController extends Controller
{
    /**
     * Tampilkan Halaman Monitoring Ujian
     */
    public function monitoring($id)
    {
        $exam = CbtExam::withCount('questions')->findOrFail($id);
        $data = $this->getMonitoringDataInternal($id);

        return view('cbt.monitoring', [
            'exam' => $exam,
            'monitoringData' => $data['monitoringData'],
            'stats' => $data['stats']
        ]);
    }

    /**
     * Endpoint API/AJAX untuk Auto-Refresh Data Monitoring
     */
    public function getMonitoringData($id)
    {
        $data = $this->getMonitoringDataInternal($id);
        return response()->json($data['monitoringData']);
    }

    /**
     * Fitur Keamanan: Ganti Token Ujian Otomatis (via AJAX)
     */
    public function autoRotateToken($id)
    {
        try {
            $exam = CbtExam::findOrFail($id);
            $newToken = strtoupper(Str::random(5));
            $exam->update(['token' => $newToken]);
            
            return response()->json(['status' => 'success', 'token' => $newToken]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Tampilkan Foto Tangkapan Layar Siswa (Proctoring)
     */
    public function getStudentPhotos($exam_id, $student_id)
    {
        $session = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->first();

        if (!$session) {
            return response()->json([]);
        }

        $photos = DB::table('cbt_exam_photos')
            ->where('cbt_student_exam_id', $session->id)
            ->orderBy('captured_at', 'desc')
            ->get()
            ->map(function($p) {
                return [
                    'url' => asset('storage/' . $p->photo_path),
                    'time' => \Carbon\Carbon::parse($p->captured_at)->format('H:i:s'),
                    'ago' => \Carbon\Carbon::parse($p->captured_at)->diffForHumans()
                ];
            });

        return response()->json($photos);
    }

    /**
     * Reset Status Ujian Siswa (Hapus Sesi)
     */
    public function resetExam($exam_id, $student_id)
    {
        DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->delete(); 
            
        return back()->with('success', 'Status ujian siswa berhasil di-reset.');
    }

    /**
     * Izinkan Siswa Mengulang Ujian (Retake)
     */
    public function allowRetake($exam_id, $student_id)
    {
        $session = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->first();

        if ($session) {
            // Hapus jawaban dan foto lama
            DB::table('cbt_student_answers')->where('cbt_student_exam_id', $session->id)->delete();
            DB::table('cbt_exam_photos')->where('cbt_student_exam_id', $session->id)->delete();

            $updateData = [
                'status' => 'ongoing',       
                'total_score' => null,       
                'created_at' => now(),       
                'updated_at' => now(),
            ];

            $attempt = 2; 
            if (\Illuminate\Support\Facades\Schema::hasColumn('cbt_student_exams', 'attempt_count')) {
                $attempt = isset($session->attempt_count) ? $session->attempt_count + 1 : 2;
                $updateData['attempt_count'] = $attempt;
            }

            DB::table('cbt_student_exams')->where('id', $session->id)->update($updateData);

            return back()->with('success', 'Siswa diizinkan untuk mengerjakan ulang. Ujian ini menjadi percobaan ke-' . $attempt . '.');
        }

        return back()->with('error', 'Data ujian siswa tidak ditemukan.');
    }

    /**
     * Logika Internal Pengambilan Data Monitoring (Private)
     */
    private function getMonitoringDataInternal($id)
    {
        $exam = CbtExam::findOrFail($id);
        $students = Student::with('schoolClass')
            ->whereHas('schoolClass', function($query) use ($exam) {               
                $query->where('name', 'like', $exam->class_level . '%');
            })
            ->orderBy('name')
            ->get();

        $sessions = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $id)
            ->get()
            ->keyBy('student_id');

        $monitoringData = $students->map(function($student) use ($sessions) {
            $session = $sessions->get($student->id);
            $status = 'Belum Mengerjakan';
            $startTime = '-';
            $score = '-'; 
            $isActive = false;
            $isSeb = false;
            $deviceType = '-';

            if ($session) {
                $startTime = \Carbon\Carbon::parse($session->created_at)->format('H:i');
                // Deteksi SEB
                if (Str::contains($session->user_agent, 'SEB') || Str::contains($session->user_agent, 'SafeExamBrowser')) {
                    $isSeb = true;
                }
                // Deteksi Device
                if (Str::contains(strtolower($session->user_agent), ['mobile', 'android', 'iphone'])) {
                    $deviceType = 'Mobile';
                } else {
                    $deviceType = 'Desktop';
                }

                if ($session->status == 'finished') {
                    $status = 'Selesai';
                    $score = isset($session->total_score) ? (int)$session->total_score : 0;
                } else {
                    $status = 'Sedang Mengerjakan';
                    $isActive = true; 
                    $score = '-';
                }
            }

            return [
                'id' => $student->id,
                'name' => $student->name,
                'class' => $student->schoolClass->name ?? '-',
                'status' => $status,
                'start_time' => $startTime,
                'score' => $score,
                'is_active' => $isActive,
                'is_seb' => $isSeb,
                'device' => $deviceType,
            ];
        })->values();

        $stats = [
            'total_students' => $students->count(),
            'working' => $monitoringData->where('status', 'Sedang Mengerjakan')->count(),
            'finished' => $monitoringData->where('status', 'Selesai')->count(),
            'not_started' => $monitoringData->where('status', 'Belum Mengerjakan')->count(),
        ];

        return ['monitoringData' => $monitoringData, 'stats' => $stats];
    }
}