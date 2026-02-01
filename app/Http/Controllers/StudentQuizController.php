<?php

namespace App\Http\Controllers;

use App\Models\LmsAssignment;
use App\Models\LmsSubmission;
use App\Models\LmsSubmissionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentQuizController extends Controller
{
    public function submit(Request $request, $assignmentId)
    {
        $assignment = LmsAssignment::with('questions')->findOrFail($assignmentId);
        
        // Ambil user yang sedang login
        $user = Auth::user();
        
        // [PERBAIKAN LOGIKA IDENTIFIKASI SISWA]
        // Karena route menggunakan middleware 'auth:student', maka $user ADALAH Student.
        // Jadi kita tidak perlu memanggil $user->student lagi.
        $studentId = $user->id; 

        // Validasi tambahan (opsional): Pastikan ID valid
        if(!$studentId) {
             return back()->with('error', 'Gagal identifikasi akun siswa. Silakan login ulang.');
        }

        // 2. Cek Double Submit
        $existing = LmsSubmission::where('assignment_id', $assignmentId)
            ->where('student_id', $studentId)
            ->first();

        if ($existing) {
            return redirect()->route('students.learning.index')->with('error', 'Anda sudah mengerjakan tugas ini sebelumnya.');
        }

        // Cek Soal
        if ($assignment->questions->isEmpty()) {
            return back()->with('error', 'Gagal: Soal tidak ditemukan di sistem. Hubungi guru.');
        }

        $questions = $assignment->questions;
        $studentAnswers = $request->input('answers', []);
        
        $earnedScore = 0;
        
        DB::beginTransaction();
        try {
            // 3. Buat Header Submission
            $submission = LmsSubmission::create([
                'assignment_id' => $assignment->id,
                'student_id' => $studentId,
                'submitted_at' => now(),
                'grade' => 0, 
                'status' => 'submitted',
                'teacher_feedback' => null
            ]);

            // 4. Simpan Detail Jawaban
            foreach ($questions as $q) {
                $ansText = $studentAnswers[$q->id] ?? null;
                $isCorrect = false; 
                $points = 0;

                if ($q->question_type == 'multiple_choice') {
                    // Penilaian Otomatis PG
                    if ($ansText && $ansText == $q->correct_answer) {
                        $isCorrect = true;
                        $points = $q->points;
                        $earnedScore += $points;
                    }
                } elseif ($q->question_type == 'essay') {
                    // Essai: Nilai 0, Status NULL (Perlu Review Guru)
                    $isCorrect = null; 
                    $points = 0; 
                }

                LmsSubmissionAnswer::create([
                    'submission_id' => $submission->id,
                    'question_id' => $q->id,
                    'answer_text' => $ansText,
                    'points' => $points,
                    'is_correct' => $isCorrect
                ]);
            }

            // 5. Update Nilai Total
            $submission->update(['grade' => $earnedScore]);

            DB::commit();
            
            // Redirect SUKSES ke halaman daftar materi (Bukan kembali ke soal)
            return redirect()->route('students.learning.index')
                ->with('success', 'Jawaban berhasil dikirim! Nilai Pilihan Ganda: ' . $earnedScore);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim jawaban: ' . $e->getMessage());
        }
    }
}