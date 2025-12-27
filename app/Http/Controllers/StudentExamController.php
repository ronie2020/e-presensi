<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CbtExam;
use App\Models\CbtStudentExam;
use App\Models\CbtStudentAnswer;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class StudentExamController extends Controller
{
    /**
     * Helper untuk mendapatkan ID Siswa yang sedang login
     */
    private function getStudentId() {
        return Auth::guard('student')->id();
    }

    public function index()
    {
        $exams = CbtExam::where('is_active', true)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->orderBy('start_time', 'desc')
            ->get();

        // [PATH SESUAI]: cbt/student/index.blade.php
        return view('cbt.student.index', compact('exams'));
    }

    public function showStart($exam_id)
    {
        $exam = CbtExam::findOrFail($exam_id);
        
        $existingSession = CbtStudentExam::where('cbt_exam_id', $exam->id)
            ->where('student_id', $this->getStudentId()) 
            ->first();

        if ($existingSession) {
            if ($existingSession->status == 'finished') {
                return redirect()->back()->with('error', 'Anda sudah menyelesaikan ujian ini.');
            }
            return redirect()->route('student.exam.run', $exam->id);
        }

        // [PATH SESUAI]: cbt/student/start_confirmation.blade.php
        return view('cbt.student.start_confirmation', compact('exam')); 
    }

    public function start(Request $request, $exam_id)
    {
        $exam = CbtExam::findOrFail($exam_id);
        
        if ($exam->token) {
            if (!$request->token) {
                return back()->withInput()->withErrors(['token' => 'Token wajib diisi.']);
            }
            if (strtoupper($request->token) !== strtoupper($exam->token)) {
                return back()->withInput()->withErrors(['token' => 'Token ujian salah!']);
            }
        }

        CbtStudentExam::firstOrCreate(
            [
                'cbt_exam_id' => $exam->id, 
                'student_id' => $this->getStudentId() 
            ],
            [
                'started_at' => now(),
                'status' => 'ongoing',
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent')
            ]
        );

        return redirect()->route('student.exam.run', $exam->id);
    }

    public function run($exam_id)
    {
        // TRY-CATCH BLOCK: Mencegah Layar Putih (White Screen of Death)
        try {
            // [FIX RELASI]: Pastikan meload relasi 'subject' atau 'mapel' jika di view memanggilnya.
            // Jika nama relasi di model CbtExam bukan 'subject', ganti dengan yang benar (misal: 'lesson', 'mapel').
            // Jika error "Call to undefined relationship", hapus 'subject' dari array with()
            
            // Cek dulu apakah model CbtExam punya method subject(). Jika ragu, kita load questions saja.
            $exam = CbtExam::with(['questions' => function($q) {
                $q->select('id', 'cbt_exam_id', 'question_text', 'question_image', 'options'); 
            }])->findOrFail($exam_id);

            $session = CbtStudentExam::where('cbt_exam_id', $exam_id)
                ->where('student_id', $this->getStudentId()) 
                ->firstOrFail();

            if ($session->status === 'finished') {
                return redirect()->route('student.exam.index')->with('error', 'Ujian telah selesai.');
            }

            $endTime = Carbon::parse($session->started_at)->addMinutes($exam->duration_minutes);
            if ($exam->end_time && $endTime > $exam->end_time) {
                $endTime = $exam->end_time;
            }
            
            $timeLeft = now()->diffInSeconds($endTime, false);

            if ($timeLeft <= 0) return $this->finishProcess($session);

            $existingAnswers = CbtStudentAnswer::where('cbt_student_exam_id', $session->id)
                ->pluck('answer', 'cbt_question_id');

            $questionsData = $exam->questions->map(function($q) use ($existingAnswers) {
                $options = $q->options;
                if (is_string($options)) {
                    $decoded = json_decode($options, true);
                    $options = $decoded ?? []; 
                }

                return [
                    'id' => $q->id,
                    'text' => $q->question_text,
                    'image' => $q->question_image ? asset('storage/'.$q->question_image) : null,
                    'options' => $options, 
                    'saved_answer' => $existingAnswers[$q->id] ?? null
                ];
            });

            // [FIX PATH DISINI]: Sesuai info Anda: view/cbt/student/exam_runner.blade.php
            // Maka pemanggilannya adalah: cbt.student.exam_runner
            
            $viewPath = 'cbt.student.exam_runner';

            if (!View::exists($viewPath)) {
                // Tampilkan pesan error jelas jika file tidak ketemu
                return response()->make("
                    <div style='background:red; color:white; padding:20px; font-family:sans-serif;'>
                        <h1>ERROR: View Tidak Ditemukan!</h1>
                        <p>Sistem mencari file di: <code>resources/views/cbt/student/exam_runner.blade.php</code></p>
                        <p>Pastikan nama file dan foldernya sudah benar (case-sensitive di hosting linux).</p>
                    </div>
                ", 500);
            }

            return view($viewPath, [
                'exam' => $exam,
                'questions' => $questionsData,
                'timeLeft' => $timeLeft,
                'sessionId' => $session->id
            ]);

        } catch (\Exception $e) {
            // DEBUG DARURAT: Tampilkan error apapun ke layar
            return response()->make("
                <div style='font-family:monospace; background:#fff0f0; color:#d8000c; padding:20px; border:1px solid #d8000c; margin:20px;'>
                    <h3>TERJADI ERROR PROGRAM</h3>
                    <p><strong>Pesan:</strong> {$e->getMessage()}</p>
                    <p><strong>File:</strong> {$e->getFile()} baris {$e->getLine()}</p>
                    <hr>
                    <p>Silakan kirim foto ini ke pengembang aplikasi.</p>
                </div>
            ", 500);
        }
    }

    public function saveAnswer(Request $request)
    {
        try {
            $request->validate([
                'session_id' => 'required',
                'question_id' => 'required',
                'answer' => 'required'
            ]);

            $session = CbtStudentExam::where('id', $request->session_id)
                ->where('student_id', $this->getStudentId()) 
                ->firstOrFail();

            if ($session->status !== 'ongoing') {
                return response()->json(['status' => 'error', 'message' => 'Ujian selesai'], 403);
            }

            CbtStudentAnswer::updateOrCreate(
                [
                    'cbt_student_exam_id' => $session->id,
                    'cbt_question_id' => $request->question_id
                ],
                ['answer' => $request->answer]
            );

            return response()->json(['status' => 'saved']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function finish($exam_id)
    {
        $session = CbtStudentExam::where('cbt_exam_id', $exam_id)
            ->where('student_id', $this->getStudentId()) 
            ->firstOrFail();

        return $this->finishProcess($session);
    }

    private function finishProcess($session)
    {
        if ($session->status == 'finished') {
            return redirect()->route('student.exam.index')->with('success', 'Ujian sudah selesai.');
        }

        $studentAnswers = CbtStudentAnswer::where('cbt_student_exam_id', $session->id)
            ->with('question') 
            ->get();

        $totalScore = 0;

        foreach ($studentAnswers as $ans) {
            if ($ans->question) {
                $isCorrect = strtoupper(trim($ans->answer)) === strtoupper(trim($ans->question->correct_answer));
                $ans->is_correct = $isCorrect;
                $ans->save();
                if ($isCorrect) $totalScore += $ans->question->score_weight;
            }
        }

        $session->update([
            'finished_at' => now(),
            'total_score' => $totalScore,
            'status' => 'finished'
        ]);

        return redirect()->route('student.exam.index')->with('success', 'Ujian selesai! Nilai Anda: ' . $totalScore);
    }
}