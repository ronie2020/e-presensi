<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CbtExam;
use App\Models\Student;
use App\Models\CbtMessage;
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
        $exam = CbtExam::find($id);
        $data = $this->getMonitoringDataInternal($id);
        return response()->json([
            'monitoringData' => $data['monitoringData'],
            'stats' => $data['stats'],
            'exam' => ['token' => $exam?->token]
        ]);
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
                'violations' => $session ? (int)($session->violation_count ?? 0) : 0,
                'latency_ms' => $session ? $session->latency_ms : null,
                'last_seen' => $session && $session->last_seen_at ? \Carbon\Carbon::parse($session->last_seen_at)->diffForHumans() : '-',
                'extra_time' => $session ? (int)($session->extra_time_minutes ?? 0) : 0,
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

    /**
     * Berikan Tambahan Waktu Ujian ke Siswa Tertentu (Menit)
     */
    public function addExtraTime(Request $request, $exam_id, $student_id)
    {
        $request->validate([
            'minutes' => 'required|integer|min:1|max:180'
        ]);

        $session = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->where('status', '!=', 'finished')
            ->first();

        if (!$session) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa tidak sedang aktif mengerjakan ujian.'
            ], 404);
        }

        $newExtra = (int)($session->extra_time_minutes ?? 0) + (int)$request->minutes;
        DB::table('cbt_student_exams')
            ->where('id', $session->id)
            ->update([
                'extra_time_minutes' => $newExtra,
                'updated_at' => now()
            ]);

        return response()->json([
            'status' => 'success',
            'message' => "Berhasil menambahkan waktu +{$request->minutes} menit! Total tambahan: {$newExtra} menit.",
            'total_extra' => $newExtra
        ]);
    }

    /**
     * Paksa Kumpulkan Ujian untuk Satu Siswa Tertentu
     */
    public function forceSubmitStudent(Request $request, $exam_id, $student_id)
    {
        $exam = CbtExam::findOrFail($exam_id);
        $session = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->where('status', '!=', 'finished')
            ->first();

        if (!$session) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Siswa tidak sedang aktif dalam ujian.'], 404);
            }
            return back()->with('error', 'Siswa tidak sedang aktif dalam pengerjaan ujian.');
        }

        $this->gradeAndFinishSession($session, $exam);

        if ($request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Ujian siswa berhasil diselesaikan dan dinilai.']);
        }

        return back()->with('success', 'Ujian siswa berhasil diselesaikan dan dinilai.');
    }

    /**
     * Paksa Kumpulkan Ujian untuk Semua Siswa yang Masih Mengerjakan
     */
    public function forceSubmitAll(Request $request, $exam_id)
    {
        $exam = CbtExam::findOrFail($exam_id);
        $sessions = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $exam_id)
            ->where('status', '!=', 'finished')
            ->get();

        if ($sessions->isEmpty()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'info', 'message' => 'Tidak ada siswa yang sedang berstatus aktif mengerjakan.'], 200);
            }
            return back()->with('info', 'Tidak ada siswa yang sedang berstatus aktif mengerjakan.');
        }

        $count = 0;
        foreach ($sessions as $session) {
            $this->gradeAndFinishSession($session, $exam);
            $count++;
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => "Berhasil menyelesaikan ujian untuk {$count} siswa secara massal."]);
        }

        return back()->with('success', "Berhasil menyelesaikan ujian untuk {$count} siswa secara massal.");
    }

    /**
     * Helper privat untuk mengkalkulasi skor dan menyelesaikan sesi ujian
     */
    private function gradeAndFinishSession($session, $exam)
    {
        $studentAnswers = DB::table('cbt_student_answers')->where('cbt_student_exam_id', $session->id)->get();
        $questions = DB::table('cbt_questions')->where('cbt_exam_id', $exam->id)->get()->keyBy('id');

        $totalScore = 0;

        foreach ($studentAnswers as $ans) {
            if (isset($questions[$ans->cbt_question_id])) {
                $q = $questions[$ans->cbt_question_id];
                $type = $q->question_type ?? 'choice';
                $isCorrect = false;

                $studentAns = trim((string)$ans->answer);
                $correctAns = trim((string)$q->correct_answer);

                if ($type === 'choice' || $type === 'true_false') {
                    if (strcasecmp($studentAns, $correctAns) == 0) {
                        $isCorrect = true;
                    }
                } elseif ($type === 'complex' || $type === 'multiple_choice' || $type === 'multiple_select') {
                    $studentDec = is_array($ans->answer) ? $ans->answer : json_decode($ans->answer, true);
                    $correctDec = is_array($q->correct_answer) ? $q->correct_answer : json_decode($q->correct_answer, true);

                    if (!is_array($studentDec)) {
                        $studentDec = !empty($ans->answer) ? [trim((string)$ans->answer)] : [];
                    }
                    if (!is_array($correctDec)) {
                        $correctDec = !empty($q->correct_answer) ? [trim((string)$q->correct_answer)] : [];
                    }

                    $totalKeys = count($correctDec);
                    if ($totalKeys > 0) {
                        $correctSelected = count(array_intersect($studentDec, $correctDec));
                        $wrongSelected = count(array_diff($studentDec, $correctDec));
                        $fraction = max(0, ($correctSelected - $wrongSelected) / $totalKeys);
                        $earnedScore = round($q->score_weight * $fraction, 2);

                        if ($earnedScore > 0) {
                            $totalScore += $earnedScore;
                            DB::table('cbt_student_answers')->where('id', $ans->id)->update([
                                'score' => $earnedScore,
                                'is_correct' => ($fraction == 1.0)
                            ]);
                        } else {
                            DB::table('cbt_student_answers')->where('id', $ans->id)->update([
                                'score' => 0,
                                'is_correct' => false
                            ]);
                        }
                        continue;
                    }
                } elseif ($type === 'matching') {
                    $keyMap = json_decode($correctAns, true) ?? [];
                    $studentMap = json_decode($studentAns, true) ?? [];
                    if (is_array($keyMap)) ksort($keyMap);
                    if (is_array($studentMap)) ksort($studentMap);
                    if (!empty($keyMap) && $keyMap == $studentMap) {
                        $isCorrect = true;
                    }
                } elseif ($type === 'essay') {
                    if (!empty($correctAns) && strcasecmp($studentAns, $correctAns) == 0) {
                        $isCorrect = true;
                    }
                }

                if ($isCorrect) {
                    $totalScore += $q->score_weight;
                    DB::table('cbt_student_answers')->where('id', $ans->id)->update([
                        'score' => $q->score_weight,
                        'is_correct' => true
                    ]);
                } else {
                    DB::table('cbt_student_answers')->where('id', $ans->id)->update([
                        'score' => 0,
                        'is_correct' => false
                    ]);
                }
            }
        }

        DB::table('cbt_student_exams')->where('id', $session->id)->update([
            'status' => 'finished',
            'total_score' => $totalScore,
            'finished_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Kirim Pesan Siaran / Teguran Proktor ke Layar Siswa
     */
    public function sendMessage(Request $request, $exam_id)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'type' => 'required|in:info,warning,urgent',
            'student_id' => 'nullable|integer'
        ]);

        CbtMessage::create([
            'cbt_exam_id' => $exam_id,
            'student_id' => $request->student_id ?: null,
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'type' => $request->type,
            'is_read' => false
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pesan berhasil disiarkan ke layar siswa!'
        ]);
    }
}