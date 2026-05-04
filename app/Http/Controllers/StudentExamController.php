<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; 
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

    // FUNGSI HELPER BARU: Memastikan "7A" cocok ke "7", tapi "10A" BUKAN "1"
    private function checkClassMatch($className, $examLevel) {
        if (empty($examLevel)) return true; // Jika level dikosongkan = Ujian Umum
        
        // Regex: Harus diawali (^) level ujian, dan karakter berikutnya BUKAN angka (?![0-9])
        $pattern = '/^' . preg_quote(trim($examLevel), '/') . '(?![0-9])/i';
        return (bool) preg_match($pattern, trim($className));
    }

    public function index()
    {
        $student = Auth::guard('student')->user();
        $className = $student->schoolClass->name ?? '';

        $allExams = CbtExam::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $exams = $allExams->filter(function($exam) use ($className) {
            return $this->checkClassMatch($className, $exam->class_level);
        });

        $now = Carbon::now(); // Ambil waktu sekarang

        foreach($exams as $exam) {
            $session = CbtStudentExam::where('student_id', $this->getStudentId())
                ->where('cbt_exam_id', $exam->id)
                ->first();
            
            // LOGIKA WAKTU DITAMBAHKAN DI SINI
            $startTime = Carbon::parse($exam->start_time);
            $endTime = $exam->end_time ? Carbon::parse($exam->end_time) : null;

            if ($session) {
                $exam->student_status = $session->status;
            } else {
                // Jika waktu sekarang masih kurang dari waktu mulai
                if ($now->lessThan($startTime)) {
                    $exam->student_status = 'upcoming';
                } 
                // Jika waktu sekarang sudah melewati waktu selesai (opsional, jika ada end_time)
                elseif ($endTime && $now->greaterThan($endTime)) {
                    $exam->student_status = 'finished'; // Atau bisa buat status baru 'expired'
                } 
                // Jika sudah masuk rentang waktu ujian
                else {
                    $exam->student_status = 'open';
                }
            }
            
                $exam->session_id = $session ? $session->id : null;
                $exam->student_score = $session ? $session->total_score : 0;
            }

        return view('cbt.student.index', compact('exams'));
    }

public function showStart($exam_id)
{
    $exam = CbtExam::findOrFail($exam_id);
    $student = Auth::guard('student')->user();
    $className = $student->schoolClass->name ?? '';
    
    if (!$this->checkClassMatch($className, $exam->class_level)) {
        return redirect()->route('student.exam.index')->with('error', 'Akses Ditolak: Ujian ini bukan untuk tingkat kelas Anda.');
    }

    // PENGECEKAN WAKTU
    $now = Carbon::now();
    $startTime = Carbon::parse($exam->start_time);
    $endTime = $exam->end_time ? Carbon::parse($exam->end_time) : null;

    if ($now->lessThan($startTime)) {
        return redirect()->route('student.exam.index')->with('error', 'Akses Ditolak: Waktu ujian belum dimulai.');
    }
    if ($endTime && $now->greaterThan($endTime)) {
        return redirect()->route('student.exam.index')->with('error', 'Akses Ditolak: Waktu ujian sudah berakhir.');
    }

    $existingSession = CbtStudentExam::where('student_id', $this->getStudentId())
        ->where('cbt_exam_id', $exam_id)
        ->first();

        if ($existingSession && $existingSession->status == 'finished') {
            return redirect()->route('student.exam.index')->with('error', 'Anda sudah menyelesaikan ujian ini.');
        }

        if ($existingSession && $existingSession->status == 'ongoing') {
            return redirect()->route('student.exam.run', $exam_id);
        }

        return view('cbt.student.start_confirmation', compact('exam'));
    }

    public function start(Request $request, $exam_id)
    {
        $exam = CbtExam::findOrFail($exam_id);
        $student = Auth::guard('student')->user();
        $className = $student->schoolClass->name ?? '';

        if (!$this->checkClassMatch($className, $exam->class_level)) {
            return redirect()->route('student.exam.index')->with('error', 'Akses Ditolak: Anda tidak diizinkan memulai ujian ini.');
        }

        // PENGECEKAN WAKTU (Double Security)
        $now = Carbon::now();
        if ($now->lessThan(Carbon::parse($exam->start_time))) {
            return redirect()->route('student.exam.index')->with('error', 'Ujian belum dimulai. Silakan tunggu jadwalnya.');
        }

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
        $student = Auth::guard('student')->user();

        $session = CbtStudentExam::where('student_id', $studentId)
            ->where('cbt_exam_id', $exam_id)
            ->first();

        if (!$session || $session->status == 'finished') {
            return redirect()->route('student.exam.index');
        }

        // Logika Hitung Waktu
        $startTime = Carbon::parse($session->created_at);
        $endTimeByDuration = $startTime->copy()->addMinutes($exam->duration_minutes);
        $endTimeBySchedule = Carbon::parse($exam->end_time);
        
        $finalEndTime = $endTimeByDuration->lessThan($endTimeBySchedule) ? $endTimeByDuration : $endTimeBySchedule;
        $timeLeft = Carbon::now()->diffInSeconds($finalEndTime, false);

        if ($timeLeft <= 0) {
            return $this->finishProcess($session, $exam);
        }

        // Ambil soal dan acak        
        $questions = CbtQuestion::where('cbt_exam_id', $exam_id)
            ->select('id', 'question_text', 'question_image', 'options', 'question_type') 
            ->inRandomOrder($session->id) 
            ->get();

        $savedAnswers = CbtStudentAnswer::where('cbt_student_exam_id', $session->id)
            ->pluck('answer', 'cbt_question_id');

        $questions->transform(function ($q) use ($savedAnswers) {
            $opts = is_string($q->options) ? json_decode($q->options, true) : $q->options;
            $q->options = $opts; 
                        
            $saved = $savedAnswers[$q->id] ?? null;
                      
            if ($q->question_type === 'matching' && $saved && is_string($saved)) {
                $decoded = json_decode($saved, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $saved = $decoded;
                }
            }
            
            $q->saved_answer = $saved;
            return $q;
        });       

        $sessionId = $session->id;

        // Query dasar mengambil soal
        $query = CbtQuestion::where('cbt_exam_id', $exam_id)
            ->select('id', 'question_text', 'question_image', 'options', 'question_type') 
            ->inRandomOrder($session->id); // <--- Kunci utama, acak berdasarkan ID sesi

        // LOGIKA ANDA: Jika guru menyetel limit (misal 40), maka potong soalnya!
        if (isset($exam->question_limit) && $exam->question_limit > 0) {
            $query->take($exam->question_limit);
        }

        // Eksekusi query
        $questions = $query->get();
        
        if (view()->exists('cbt.student.exam_runner')) {
             return view('cbt.student.exam_runner', compact('exam', 'questions', 'timeLeft', 'sessionId', 'student'));
        } else {
             return view('cbt.exam_runner', compact('exam', 'questions', 'timeLeft', 'sessionId', 'student'));
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
        
        $answerToSave = is_array($request->answer) ? json_encode($request->answer) : $request->answer;
      
        CbtStudentAnswer::updateOrInsert(
            [
                'cbt_student_exam_id' => $session->id,
                'cbt_question_id' => $request->question_id
            ],
            [
                'answer' => $answerToSave,                
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
            
            // --- LOGIKA 75% WAKTU ---
            $startTime = Carbon::parse($session->created_at);
            $now = Carbon::now();
            
            // Hitung sudah berapa lama siswa mengerjakan (dalam menit)
            $minutesElapsed = $startTime->diffInMinutes($now);
            
            // Hitung 75% dari total durasi ujian
            $minimumMinutesRequired = $exam->duration_minutes * 0.75;

            // Jika waktu pengerjaan masih di bawah 75%
            if ($minutesElapsed < $minimumMinutesRequired) {
                // Jangan diselesaikan, kembalikan dengan error
                return redirect()->route('student.exam.run', $exam_id)
                    ->with('error', 'Anda baru bisa menyelesaikan ujian setelah melewati 75% waktu (' . ceil($minimumMinutesRequired) . ' menit). Sisa waktu tunggu: ' . ceil($minimumMinutesRequired - $minutesElapsed) . ' menit lagi.');
            }
            // --- AKHIR LOGIKA 75% ---

            return $this->finishProcess($session, $exam);
        }

        return redirect()->route('student.exam.index');
    }

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
        // Mengambil data soal lengkap termasuk question_type
        $questions = CbtQuestion::where('cbt_exam_id', $session->cbt_exam_id)->get()->keyBy('id');

        $totalScore = 0;

        foreach ($studentAnswers as $ans) {
            if (isset($questions[$ans->cbt_question_id])) {
                $q = $questions[$ans->cbt_question_id];
                                
                $type = $q->question_type ?? 'choice'; 
                $isCorrect = false;

                $studentAns = trim($ans->answer);
                $correctAns = trim($q->correct_answer);

                if ($type === 'choice' || $type === 'true_false') {        // pilihan ganda            
                    if (strcasecmp($studentAns, $correctAns) == 0) {
                        $isCorrect = true;
                    }
                } 
                elseif ($type === 'matching') {                         // matching
                    $keyMap = json_decode($correctAns, true) ?? [];
                    $studentMap = json_decode($studentAns, true) ?? [];
                    
                    if (is_array($keyMap)) ksort($keyMap);
                    if (is_array($studentMap)) ksort($studentMap);

                    if (!empty($keyMap) && $keyMap == $studentMap) {
                        $isCorrect = true;
                    }
                }
                elseif ($type === 'essay') {                            // essai    
                    if (!empty($correctAns) && strcasecmp($studentAns, $correctAns) == 0) {
                        $isCorrect = true;
                    }
                }

                if ($isCorrect) {
                    $totalScore += $q->score_weight;
                    // status benar/salah per butir soal (opsional, untuk analisis)
                    $ans->is_correct = true;
                    $ans->save();
                } else {
                    $ans->is_correct = false;
                    $ans->save();
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