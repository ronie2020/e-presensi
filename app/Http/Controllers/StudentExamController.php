<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CbtExam;
use App\Models\CbtStudentExam;
use App\Models\CbtStudentAnswer;
use Carbon\Carbon;

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
        // Pastikan exam aktif
        $exams = CbtExam::where('is_active', true)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->orderBy('start_time', 'desc')
            ->get();

        // Path ini sudah benar (cbt.student.index)
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

        // [PERBAIKAN 1]: Mengarahkan ke folder yang benar (cbt/student/start_confirmation)
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
                return back()->withInput()->withErrors(['token' => 'Token ujian salah! Silakan tanya pengawas.']);
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
        if ($endTime > $exam->end_time) $endTime = $exam->end_time;
        
        $timeLeft = now()->diffInSeconds($endTime, false);

        if ($timeLeft <= 0) return $this->finishProcess($session);

        $existingAnswers = CbtStudentAnswer::where('cbt_student_exam_id', $session->id)
            ->pluck('answer', 'cbt_question_id');

        $questionsData = $exam->questions->map(function($q) use ($existingAnswers) {
            $options = is_string($q->options) ? json_decode($q->options, true) : $q->options;
            return [
                'id' => $q->id,
                'text' => $q->question_text,
                'image' => $q->question_image ? asset('storage/'.$q->question_image) : null,
                'options' => $options, 
                'saved_answer' => $existingAnswers[$q->id] ?? null
            ];
        });

        // [PERBAIKAN 2]: Mengarahkan ke folder yang benar (cbt/student/exam_runner)
        return view('cbt.student.exam_runner', [
            'exam' => $exam,
            'questions' => $questionsData,
            'timeLeft' => $timeLeft,
            'sessionId' => $session->id
        ]);
    }

    public function saveAnswer(Request $request)
    {
        $request->validate([
            'session_id' => 'required',
            'question_id' => 'required',
            'answer' => 'required'
        ]);

        $session = CbtStudentExam::where('id', $request->session_id)
            ->where('student_id', $this->getStudentId()) 
            ->firstOrFail();

        if ($session->status !== 'ongoing') {
            return response()->json(['status' => 'error', 'message' => 'Ujian sudah selesai'], 403);
        }

        CbtStudentAnswer::updateOrCreate(
            [
                'cbt_student_exam_id' => $session->id,
                'cbt_question_id' => $request->question_id
            ],
            ['answer' => $request->answer]
        );

        return response()->json(['status' => 'saved']);
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
                $isCorrect = strtoupper($ans->answer) === strtoupper($ans->question->correct_answer);
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