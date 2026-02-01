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
        // Gunakan eager loading 'questions' untuk memastikan data soal ikut terambil
        $assignment = LmsAssignment::with('questions')->findOrFail($assignmentId);
        $user = Auth::user();
        
        // 1. Validasi Siswa (Logika Lama Tetap Ada)
        $studentId = $user->student->id ?? null;
        if(!$studentId) {
             return back()->with('error', 'Akun ini tidak terdaftar sebagai siswa.');
        }

        // 2. Cek Double Submit (Logika Lama Tetap Ada)
        $existing = LmsSubmission::where('assignment_id', $assignmentId)
            ->where('student_id', $studentId)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah mengerjakan tugas ini.');
        }

        // [LOGIKA BARU - KEAMANAN] Validasi Soal Kosong
        // Mencegah simpan data jika soal tidak terbaca oleh sistem
        if ($assignment->questions->isEmpty()) {
            return back()->with('error', 'Gagal menyimpan: Data soal tidak ditemukan di sistem. Harap lapor ke guru.');
        }

        // [LOGIKA BARU - KEAMANAN] Cek Batas Waktu
        $deadline = Carbon::parse($assignment->deadline);
        if (now()->gt($deadline->addMinutes(2))) { // Toleransi 2 menit
            return back()->with('error', 'Maaf, waktu pengumpulan tugas sudah habis.');
        }

        $questions = $assignment->questions;
        $studentAnswers = $request->input('answers', []);
        
        $earnedScore = 0;
        
        DB::beginTransaction();
        try {
            // 3. Simpan Header Submission
            $submission = LmsSubmission::create([
                'assignment_id' => $assignment->id,
                'student_id' => $studentId,
                'submitted_at' => now(),
                'grade' => 0, 
                'status' => 'submitted',
                'teacher_feedback' => null
            ]);

            // 4. Loop Soal & Simpan Jawaban
            foreach ($questions as $q) {
                $ansText = $studentAnswers[$q->id] ?? null;
                $isCorrect = false; 
                $points = 0;

                if ($q->question_type == 'multiple_choice') {
                    // Logika Penilaian PG
                    if ($ansText && $ansText == $q->correct_answer) {
                        $isCorrect = true;
                        $points = $q->points;
                        $earnedScore += $points;
                    }
                } elseif ($q->question_type == 'essay') {
                    // Logika Essay (Menunggu dinilai guru)
                    $isCorrect = null; 
                    $points = 0; 
                }

                // Simpan ke tabel detail
                LmsSubmissionAnswer::create([
                    'submission_id' => $submission->id,
                    'question_id' => $q->id,
                    'answer_text' => $ansText,
                    'points' => $points,
                    'is_correct' => $isCorrect
                ]);
            }

            // 5. Update Nilai Total PG
            $submission->update(['grade' => $earnedScore]);

            DB::commit();
            return redirect()->route('students.learning.index')->with('success', 'Jawaban berhasil dikirim! Nilai PG: ' . $earnedScore);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
        }
    }
}