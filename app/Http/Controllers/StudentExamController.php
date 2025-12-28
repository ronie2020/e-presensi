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

    /**
     * DASHBOARD SISWA (Daftar Ujian)
     * Perbaikan: Menghapus ->with('subject') karena kolom di DB adalah subject_name (string)
     */
    public function index()
    {
        // Pastikan exam aktif
        $exams = CbtExam::where('is_active', true)
            ->withCount('questions') // Hitung jumlah soal
            // ->with('subject')  <-- HAPUS INI (Penyebab Error)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->orderBy('start_time', 'desc')
            ->get();

        return view('cbt.student.index', compact('exams'));
    }

    /**
     * HALAMAN KONFIRMASI (Sebelum Masuk)
     */
    public function showStart($exam_id)
    {
        $exam = CbtExam::withCount('questions')->findOrFail($exam_id);
        
        $existingSession = CbtStudentExam::where('cbt_exam_id', $exam->id)
            ->where('student_id', $this->getStudentId()) 
            ->first();

        if ($existingSession) {
            if ($existingSession->status == 'finished') {
                return redirect()->back()->with('error', 'Anda sudah menyelesaikan ujian ini.');
            }
            // Jika status masih ongoing, langsung lempar ke halaman ujian
            return redirect()->route('student.exam.run', $exam->id);
        }

        return view('cbt.student.start_confirmation', compact('exam')); 
    }

    /**
     * PROSES MULAI (Validasi Token & Buat Sesi)
     */
    public function start(Request $request, $exam_id)
    {
        $exam = CbtExam::findOrFail($exam_id);
        
        // Cek Token
        if ($exam->token) {
            if (!$request->token) {
                return back()->withInput()->withErrors(['token' => 'Token wajib diisi.']);
            }
            if (strtoupper($request->token) !== strtoupper($exam->token)) {
                return back()->withInput()->withErrors(['token' => 'Token ujian salah! Silakan tanya pengawas.']);
            }
        }

        // Buat atau Ambil Sesi Ujian
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

    /**
     * HALAMAN PENGERJAAN SOAL (Runner)
     */
    public function run($exam_id)
    {
        // Ambil Data Ujian beserta Soal-soalnya
        $exam = CbtExam::with(['questions' => function($q) {
            $q->select('id', 'cbt_exam_id', 'question_text', 'question_image', 'options'); 
        }])->findOrFail($exam_id);

        // Cek Sesi Siswa
        $session = CbtStudentExam::where('cbt_exam_id', $exam_id)
            ->where('student_id', $this->getStudentId()) 
            ->firstOrFail();

        // Jika sudah selesai, tolak akses
        if ($session->status === 'finished') {
            return redirect()->route('student.exam.index')->with('error', 'Ujian telah selesai.');
        }

        // Hitung Sisa Waktu
        $endTime = Carbon::parse($session->started_at)->addMinutes($exam->duration_minutes);
        
        // Jangan melebihi waktu akhir global ujian
        if ($endTime > $exam->end_time) $endTime = $exam->end_time;
        
        $timeLeft = now()->diffInSeconds($endTime, false);

        // Jika waktu habis, paksa selesai
        if ($timeLeft <= 0) return $this->finishProcess($session);

        // Ambil jawaban yang sudah tersimpan (agar saat refresh jawaban tidak hilang)
        $existingAnswers = CbtStudentAnswer::where('cbt_student_exam_id', $session->id)
            ->pluck('answer', 'cbt_question_id');

        // Format data soal untuk Frontend (Vue/AlpineJS)
        $questionsData = $exam->questions->map(function($q) use ($existingAnswers) {
            // Decode JSON options jika perlu
            $options = is_string($q->options) ? json_decode($q->options, true) : $q->options;
            
            return [
                'id' => $q->id,
                'text' => $q->question_text,
                'image' => $q->question_image ? asset('storage/'.$q->question_image) : null,
                'options' => $options, 
                'saved_answer' => $existingAnswers[$q->id] ?? null
            ];
        });

        return view('cbt.student.exam_runner', [
            'exam' => $exam,
            'questions' => $questionsData,
            'timeLeft' => $timeLeft,
            'sessionId' => $session->id
        ]);
    }

    /**
     * SIMPAN JAWABAN PER NOMOR (AJAX)
     */
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

    /**
     * SELESAIKAN UJIAN
     */
    public function finish($exam_id)
    {
        $session = CbtStudentExam::where('cbt_exam_id', $exam_id)
            ->where('student_id', $this->getStudentId()) 
            ->firstOrFail();

        return $this->finishProcess($session);
    }

    /**
     * LOGIKA HITUNG NILAI AKHIR
     */
    private function finishProcess($session)
    {
        if ($session->status == 'finished') {
            return redirect()->route('student.exam.index')->with('success', 'Ujian sudah selesai.');
        }

        // Ambil semua jawaban siswa + Kunci Jawaban Soal
        $studentAnswers = CbtStudentAnswer::where('cbt_student_exam_id', $session->id)
            ->with('question') 
            ->get();

        $totalScore = 0;

        foreach ($studentAnswers as $ans) {
            if ($ans->question) {
                // Bandingkan Jawaban (Case Insensitive)
                $isCorrect = strtoupper($ans->answer) === strtoupper($ans->question->correct_answer);
                
                // Simpan status benar/salah ke DB (opsional tapi berguna)
                $ans->is_correct = $isCorrect;
                $ans->save();

                // Tambahkan Bobot Nilai
                if ($isCorrect) {
                    $totalScore += $ans->question->score_weight;
                }
            }
        }

        // Update Sesi Ujian menjadi Selesai
        $session->update([
            'finished_at' => now(),
            'total_score' => $totalScore,
            'status' => 'finished'
        ]);

        return redirect()->route('student.exam.index')->with('success', 'Ujian selesai! Nilai Anda: ' . $totalScore);
    }
}