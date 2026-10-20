<?php

namespace App\Http\Controllers;

use App\Models\LmsAssignment;
use App\Models\LmsSubmission;
use App\Models\LmsSubmissionAnswer;
use App\Models\LmsQuizDraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentQuizController extends Controller
{
    /**
     * Auto-save jawaban kuis siswa secara real-time
     */
    public function autosave(Request $request, $assignmentId)
    {
        $student = Auth::guard('student')->user() ?? Auth::user();
        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }

        $assignment = LmsAssignment::find($assignmentId);
        if (!$assignment) {
            return response()->json(['status' => 'error', 'message' => 'Tugas kuis tidak ditemukan'], 404);
        }

        // Cek apakah sudah submit
        $alreadySubmitted = LmsSubmission::where('assignment_id', $assignmentId)
            ->where('student_id', $student->id)
            ->exists();

        if ($alreadySubmitted) {
            return response()->json(['status' => 'error', 'message' => 'Kuis sudah dikumpulkan sebelumnya'], 403);
        }

        // Cek deadline jika tidak diizinkan terlambat
        if (now() > $assignment->deadline && !$assignment->allow_late_submission) {
            return response()->json(['status' => 'error', 'message' => 'Waktu pengerjaan kuis sudah habis'], 403);
        }

        $request->validate([
            'question_id' => 'required|exists:lms_quiz_questions,id',
            'answer' => 'nullable'
        ]);

        try {
            LmsQuizDraft::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'assignment_id' => $assignment->id,
                    'question_id' => $request->question_id,
                ],
                [
                    'answer_text' => $request->answer,
                    'updated_at' => now(),
                ]
            );

            return response()->json(['status' => 'success', 'message' => 'Jawaban tersimpan']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function submit(Request $request, $assignmentId)
    {
        $assignment = LmsAssignment::with('questions')->findOrFail($assignmentId);
        
        $user = Auth::guard('student')->user() ?? Auth::user();
        $studentId = $user ? $user->id : null; 

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
        
        // Ambil draft tersimpan sebagai cadangan jika ada jawaban form yang terlewat
        $drafts = LmsQuizDraft::where('student_id', $studentId)
            ->where('assignment_id', $assignment->id)
            ->pluck('answer_text', 'question_id')
            ->toArray();

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
                $ansText = $studentAnswers[$q->id] ?? ($drafts[$q->id] ?? null);
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

            // Bersihkan draft jawaban kuis
            LmsQuizDraft::where('student_id', $studentId)
                ->where('assignment_id', $assignment->id)
                ->delete();

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