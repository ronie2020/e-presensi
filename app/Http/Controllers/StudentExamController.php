<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
     */
    public function index()
    {
        $student = Auth::guard('student')->user();

        // 1. DETEKSI KELAS SISWA (PENTING)
        // Agar siswa kelas 9 tidak melihat soal kelas 7, dan sebaliknya.
        // Asumsi: Nama kelas formatnya "9A", "9-B", dll. Kita ambil angkanya saja.
        $className = $student->schoolClass->name ?? '';
        $classLevel = preg_replace('/[^0-9]/', '', $className); 

        // 2. QUERY UJIAN (PERBAIKAN LOGIKA WAKTU)
        $exams = CbtExam::where('is_active', true)
            // Filter Kelas (Jika class_level kosong/null di DB, anggap untuk semua kelas)
            ->where(function($q) use ($classLevel) {
                if (!empty($classLevel)) {
                    $q->where('class_level', $classLevel);
                }
            })
            // Tampilkan ujian yang:
            // - Waktu SELESAI-nya belum lewat (masih bisa dikerjakan)
            // - ATAU Waktu MULAI-nya hari ini (status upcoming)
            ->where(function($query) {
                $query->where('end_time', '>', Carbon::now())
                      ->orWhereDate('start_time', Carbon::today());
            })
            ->withCount('questions')
            ->orderBy('start_time', 'asc')
            ->get();

        // 3. CEK STATUS PENGERJAAN PER SISWA (PENTING UNTUK TAMPILAN BUTTON)
        // Tanpa ini, tombol di View tidak akan berubah jadi "Selesai" atau "Lanjutkan"
        $exams->transform(function ($exam) {
            $studentExam = CbtStudentExam::where('cbt_exam_id', $exam->id)
                ->where('student_id', $this->getStudentId())
                ->first();

            // Default values untuk View
            $exam->student_status = 'open'; // status: upcoming, open, ongoing, finished
            $exam->student_score = 0;

            if ($studentExam) {
                if ($studentExam->status == 'finished') {
                    $exam->student_status = 'finished';
                    $exam->student_score = $studentExam->total_score;
                } else {
                    $exam->student_status = 'ongoing';
                }
            } else {
                // Jika belum ada data pengerjaan, cek apakah waktu belum mulai
                if (Carbon::now() < $exam->start_time) {
                    $exam->student_status = 'upcoming';
                }
            }

            return $exam;
        });

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

        // Cek Status Pengerjaan
        if ($existingSession) {
            if ($existingSession->status == 'finished') {
                return redirect()->route('student.exam.index')->with('error', 'Anda sudah menyelesaikan ujian ini.');
            }
            // Jika status masih ongoing (misal refresh/mati lampu), langsung lempar ke halaman ujian (Runner)
            return redirect()->route('student.exam.run', $exam->id);
        }

        // Cek Waktu (Hard Limit)
        if (Carbon::now() > $exam->end_time) {
            return redirect()->route('student.exam.index')->with('error', 'Waktu ujian telah berakhir.');
        }

        if (Carbon::now() < $exam->start_time) {
            return redirect()->route('student.exam.index')->with('error', 'Ujian belum dimulai.');
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
                'start_time' => now(), // Pastikan nama kolom di DB 'start_time' atau 'started_at' (sesuaikan)
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
            $q->select('id', 'cbt_exam_id', 'question_text', 'question_image', 'options', 'score_weight'); 
        }])->findOrFail($exam_id);

        // Cek Sesi Siswa
        $session = CbtStudentExam::where('cbt_exam_id', $exam_id)
            ->where('student_id', $this->getStudentId()) 
            ->firstOrFail();

        // Jika sudah selesai, tolak akses
        if ($session->status === 'finished') {
            return redirect()->route('student.exam.index')->with('error', 'Ujian telah selesai.');
        }

        // --- LOGIKA HITUNG SISA WAKTU (PENTING) ---
        // Waktu selesai = Waktu mulai siswa + Durasi Ujian
        // Tapi tidak boleh melebihi Jadwal Selesai Global Ujian (Hard Limit)
        // Perhatikan kolom di DB: 'start_time' atau 'started_at' (disini saya handle keduanya)
        $sessionStart = $session->start_time ?? $session->started_at;
        
        $examEndTimeGlobal = Carbon::parse($exam->end_time);
        $studentEndTime = Carbon::parse($sessionStart)->addMinutes($exam->duration_minutes);
        
        // Ambil waktu mana yang lebih dulu habis
        $finalEndTime = $examEndTimeGlobal->lt($studentEndTime) ? $examEndTimeGlobal : $studentEndTime;
        
        $timeLeft = now()->diffInSeconds($finalEndTime, false);

        // Jika waktu habis, paksa selesai
        if ($timeLeft <= 0) return $this->finishProcess($session, $exam);

        // Ambil jawaban yang sudah tersimpan (agar saat refresh jawaban tidak hilang)
        $existingAnswers = CbtStudentAnswer::where('cbt_student_exam_id', $session->id)
            ->pluck('answer', 'cbt_question_id');

        // Format data soal untuk Frontend (AlpineJS)
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
            'sessionId' => $session->id,
            'examId' => $exam->id
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
     * SELESAIKAN UJIAN (Action dari Tombol Selesai)
     */
    public function finish($exam_id)
    {
        $exam = CbtExam::with('questions')->findOrFail($exam_id);
        
        $session = CbtStudentExam::where('cbt_exam_id', $exam_id)
            ->where('student_id', $this->getStudentId()) 
            ->firstOrFail();

        return $this->finishProcess($session, $exam);
    }

    /**
     * LOGIKA HITUNG NILAI AKHIR (Private Helper)
     */
    private function finishProcess($session, $exam = null)
    {
        if ($session->status == 'finished') {
            return redirect()->route('student.exam.index')->with('success', 'Ujian sudah selesai.');
        }

        // Jika exam object belum diload, load dulu beserta soalnya
        if (!$exam) {
            $exam = CbtExam::with('questions')->find($session->cbt_exam_id);
        }

        // Ambil semua jawaban siswa
        $studentAnswers = CbtStudentAnswer::where('cbt_student_exam_id', $session->id)->get();
        $questions = $exam->questions->keyBy('id');

        $totalScore = 0;

        foreach ($studentAnswers as $ans) {
            if (isset($questions[$ans->cbt_question_id])) {
                $q = $questions[$ans->cbt_question_id];
                
                // Bandingkan Jawaban (Case Insensitive)
                $isCorrect = strtoupper($ans->answer) === strtoupper($q->correct_answer);
                
                // Simpan status ke DB (opsional)
                $ans->is_correct = $isCorrect;
                $ans->save();

                // Tambahkan Bobot Nilai jika benar
                if ($isCorrect) {
                    $totalScore += $q->score_weight;
                }
            }
        }

        // Update Sesi Ujian menjadi Selesai
        $session->update([
            'end_time' => now(), // Sesuaikan kolom DB: 'end_time' atau 'finished_at'
            'total_score' => $totalScore,
            'status' => 'finished'
        ]);

        return redirect()->route('student.exam.index')->with('success', 'Ujian selesai! Jawaban berhasil dikirim.');
    }
}