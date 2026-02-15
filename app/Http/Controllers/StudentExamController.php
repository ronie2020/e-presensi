<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Penting untuk fitur Upload Foto Proctoring
use App\Models\CbtExam;
use App\Models\CbtStudentExam;
use App\Models\CbtStudentAnswer;
use App\Models\CbtQuestion;
use Carbon\Carbon;

class StudentExamController extends Controller
{
    private function getStudentId() {
        return Auth::guard('student')->id();
    }

    public function index()
    {
        $student = Auth::guard('student')->user();
        $className = $student->schoolClass->name ?? '';
        $classLevel = preg_replace('/[^0-9]/', '', $className); 

        $exams = CbtExam::where('is_active', true)
            ->where(function($q) use ($classLevel) {
                if (!empty($classLevel)) {
                    $q->where('class_level', $classLevel);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        foreach($exams as $exam) {
            $session = CbtStudentExam::where('student_id', $this->getStudentId())
                ->where('cbt_exam_id', $exam->id)
                ->first();
            
            $exam->student_status = $session ? $session->status : 'open'; 
            $exam->session_id = $session ? $session->id : null;
        }

        // [PERBAIKAN PATH VIEW] Sesuai folder resources/views/cbt/student/index.blade.php
        return view('cbt.student.index', compact('exams'));
    }

    public function showStart($exam_id)
    {
        $exam = CbtExam::findOrFail($exam_id);
        
        $existingSession = CbtStudentExam::where('student_id', $this->getStudentId())
            ->where('cbt_exam_id', $exam_id)
            ->first();

        if ($existingSession && $existingSession->status == 'finished') {
            return redirect()->route('student.exam.index')->with('error', 'Anda sudah menyelesaikan ujian ini.');
        }

        if ($existingSession && $existingSession->status == 'ongoing') {
            return redirect()->route('student.exam.run', $exam_id);
        }

        // [PERBAIKAN PATH VIEW] Sesuai folder resources/views/cbt/student/start_confirmation.blade.php
        return view('cbt.student.start_confirmation', compact('exam'));
    }

    public function start(Request $request, $exam_id)
    {
        $exam = CbtExam::findOrFail($exam_id);

        if ($exam->token) {
            $request->validate(['token' => 'required|string']);
            if (strtoupper($request->token) !== strtoupper($exam->token)) {
                return back()->withErrors(['token' => 'Token ujian salah!']);
            }
        }

        CbtStudentExam::updateOrInsert(
            [
                'student_id' => $this->getStudentId(),
                'cbt_exam_id' => $exam->id,
            ],
            [
                'created_at' => now(),
                'status' => 'ongoing',
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip()
            ]
        );

        return redirect()->route('student.exam.run', $exam->id);
    }

    public function run($exam_id)
    {
        $exam = CbtExam::findOrFail($exam_id);
        $studentId = $this->getStudentId();

        $session = CbtStudentExam::where('student_id', $studentId)
            ->where('cbt_exam_id', $exam_id)
            ->first();

        if (!$session || $session->status == 'finished') {
            return redirect()->route('student.exam.index');
        }

        // Logika Hitung Waktu (Server Side) - Logika Asli Tetap Ada
        $startTime = Carbon::parse($session->created_at);
        $endTimeByDuration = $startTime->copy()->addMinutes($exam->duration_minutes);
        $endTimeBySchedule = Carbon::parse($exam->end_time);
        
        $finalEndTime = $endTimeByDuration->lessThan($endTimeBySchedule) ? $endTimeByDuration : $endTimeBySchedule;
        $timeLeft = Carbon::now()->diffInSeconds($finalEndTime, false);

        if ($timeLeft <= 0) {
            return $this->finishProcess($session, $exam);
        }

        $questions = CbtQuestion::where('cbt_exam_id', $exam_id)
            ->select('id', 'question_text', 'question_image', 'options') 
            ->inRandomOrder($session->id) 
            ->get();

        $savedAnswers = CbtStudentAnswer::where('cbt_student_exam_id', $session->id)
            ->pluck('answer', 'cbt_question_id');

        $questions->transform(function ($q) use ($savedAnswers) {
            $opts = is_string($q->options) ? json_decode($q->options, true) : $q->options;
            $q->options = $opts; 
            $q->saved_answer = $savedAnswers[$q->id] ?? null;
            return $q;
        });

        $sessionId = $session->id;

        // Cek keberadaan view di folder student, jika tidak ada fallback ke folder cbt
        if (view()->exists('cbt.student.exam_runner')) {
             return view('cbt.student.exam_runner', compact('exam', 'questions', 'timeLeft', 'sessionId'));
        } else {
             return view('cbt.exam_runner', compact('exam', 'questions', 'timeLeft', 'sessionId'));
        }
    }

    public function saveAnswer(Request $request)
    {
        $request->validate([
            'session_id' => 'required',
            'question_id' => 'required',
            'answer' => 'nullable'
        ]);

        $session = CbtStudentExam::where('id', $request->session_id)
            ->where('student_id', $this->getStudentId())
            ->first();

        if (!$session || $session->status == 'finished') {
            return response()->json(['status' => 'error', 'message' => 'Sesi tidak valid'], 403);
        }

        CbtStudentAnswer::updateOrInsert(
            [
                'cbt_student_exam_id' => $session->id,
                'cbt_question_id' => $request->question_id
            ],
            [
                'answer' => $request->answer,
                'updated_at' => now()
            ]
        );

        return response()->json(['status' => 'success']);
    }

    public function finish($exam_id)
    {
        $session = CbtStudentExam::where('student_id', $this->getStudentId())
            ->where('cbt_exam_id', $exam_id)
            ->where('status', 'ongoing')
            ->first();

        if ($session) {
            $exam = CbtExam::find($exam_id);
            return $this->finishProcess($session, $exam);
        }

        return redirect()->route('student.exam.index');
    }

    // METHOD FOTO PROCTORING (LOGIKA TETAP DIPERTAHANKAN)
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'session_id' => 'required',
            'photo' => 'required|string',
        ]);

        $session = CbtStudentExam::where('id', $request->session_id)
            ->where('student_id', $this->getStudentId())
            ->first();

        if (!$session || $session->status == 'finished') {
            return response()->json(['status' => 'error'], 403);
        }

        try {
            $imageData = $request->photo;
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            
            $fileName = 'proctoring/' . $session->cbt_exam_id . '/' . $session->student_id . '_' . time() . '.jpg';
            
            Storage::disk('public')->put($fileName, base64_decode($imageData));

            DB::table('cbt_exam_photos')->insert([
                'cbt_student_exam_id' => $session->id,
                'photo_path' => $fileName,
                'captured_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function finishProcess($session, $exam)
    {
        if (!$session) return redirect()->route('student.exam.index');

        $studentAnswers = CbtStudentAnswer::where('cbt_student_exam_id', $session->id)->get();
        $questions = CbtQuestion::where('cbt_exam_id', $session->cbt_exam_id)->get()->keyBy('id');

        $totalScore = 0;

        foreach ($studentAnswers as $ans) {
            if (isset($questions[$ans->cbt_question_id])) {
                $q = $questions[$ans->cbt_question_id];
                $isCorrect = strtoupper($ans->answer) === strtoupper($q->correct_answer);
                if ($isCorrect) {
                    $totalScore += $q->score_weight;
                }
            }
        }

        $session->update([
            'status' => 'finished',
            'total_score' => $totalScore,
            'finished_at' => now()
        ]);

        return redirect()->route('student.exam.index')->with('success', 'Ujian selesai dikerjakan.');
    }
}