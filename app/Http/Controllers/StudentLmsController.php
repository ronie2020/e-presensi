<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\LmsMaterial;
use App\Models\LmsAssignment;
use App\Models\LmsSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentLmsController extends Controller
{
    /**
     * Dashboard Belajar
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        $allSubjects = Subject::orderBy('name')->get();
        $prioritySubjects = collect();

        foreach ($allSubjects as $subject) {
            // Cek Tugas Aktif
            $activeTasksCount = LmsAssignment::where('subject_id', $subject->id)
                ->where('class_id', $student->class_id)
                ->whereDoesntHave('submissions', function($q) use ($student) {
                    $q->where('student_id', $student->id);
                })
                ->where(function($q) {
                    $q->where('deadline', '>', now())->orWhere('allow_late_submission', true);
                })->count();

            // Cek Materi Baru
            $newMaterialsCount = LmsMaterial::where('subject_id', $subject->id)
                ->where(function($q) use ($student) {
                    $q->where('class_id', $student->class_id)->orWhereNull('class_id');
                })->where('created_at', '>=', now()->subDays(7))->count();

            if ($activeTasksCount > 0 || $newMaterialsCount > 0) {
                $subject->active_tasks_count = $activeTasksCount;
                $subject->new_materials_count = $newMaterialsCount;
                $prioritySubjects->push($subject);
            }
        }

        // PENTING: View harus ada di resources/views/students/lms/index.blade.php
        return view('students.lms.index', compact('student', 'allSubjects', 'prioritySubjects'));
    }

    /**
     * Halaman Detail Mapel
     */
    public function showSubject($subjectId)
    {
        $student = Auth::guard('student')->user();
        $subject = Subject::findOrFail($subjectId);

        $materials = LmsMaterial::where('subject_id', $subjectId)
            ->where(function($q) use ($student) {
                $q->where('class_id', $student->class_id)->orWhereNull('class_id');
            })->latest()->get();

        $assignments = LmsAssignment::with(['questions', 'submissions' => function($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
            ->where('subject_id', $subjectId)
            ->where('class_id', $student->class_id)
            ->latest()
            ->get();

        // PENTING: View harus ada di resources/views/students/lms/show.blade.php
        return view('students.lms.show', compact('subject', 'materials', 'assignments'));
    }

    /**
     * Download Materi
     */
    public function downloadMaterial($id)
    {
        $material = LmsMaterial::findOrFail($id);
        
        // Pengecekan akses
        $student = Auth::guard('student')->user();
        if ($material->class_id && $material->class_id != $student->class_id) {
            abort(403);
        }

        return Storage::disk('public')->download($material->file_path);
    }

    /**
     * Upload Tugas Biasa & Link
     */
    public function submitAssignment(Request $request, $assignmentId)
    {
        $student = Auth::guard('student')->user();
        $assignment = LmsAssignment::findOrFail($assignmentId);

        // Jika Tipe Link
        if ($assignment->assignment_type == 'link') {
            LmsSubmission::updateOrCreate(
                ['assignment_id' => $assignmentId, 'student_id' => $student->id],
                ['submitted_at' => now(), 'student_note' => 'Diselesaikan via Link Eksternal', 'grade' => null]
            );
            return back()->with('success', 'Tugas berhasil ditandai selesai!');
        }

        // Cek Deadline
        if (!$assignment->allow_late_submission && now() > $assignment->deadline) {
            return back()->with('error', 'Maaf, batas waktu pengumpulan sudah habis.');
        }

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'student_note' => 'nullable|string|max:255',
        ]);

        $path = $request->file('file')->store('lms-submissions', 'public');

        LmsSubmission::updateOrCreate(
            ['assignment_id' => $assignmentId, 'student_id' => $student->id],
            ['file_path' => $path, 'student_note' => $request->student_note, 'submitted_at' => now()]
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }

    // ===> FUNGSI MULAI KUIS <===
    public function startQuiz($id)
    {
        $student = Auth::guard('student')->user();
        $assignment = LmsAssignment::with('questions')->findOrFail($id);

        // Cek apakah sudah mengerjakan?
        if ($assignment->isSubmittedBy($student->id)) {
            return redirect()->route('students.learning.subject.show', $assignment->subject_id)
                ->with('error', 'Anda sudah mengerjakan kuis ini.');
        }

        // Cek Deadline
        if (now() > $assignment->deadline && !$assignment->allow_late_submission) {
            return back()->with('error', 'Waktu pengerjaan kuis sudah habis.');
        }

        // PENTING: View harus ada di resources/views/students/lms/quiz.blade.php
        return view('students.lms.quiz', compact('assignment'));
    }

    // ===> FUNGSI SUBMIT KUIS <===
    public function submitQuiz(Request $request, $id)
    {
        $student = Auth::guard('student')->user();
        $assignment = LmsAssignment::with('questions')->findOrFail($id);

        $totalScore = 0;
        $maxScore = 0;
        
        foreach ($assignment->questions as $question) {
            $userAnswer = $request->input('answers.' . $question->id);
            
            if ($question->question_type == 'multiple_choice') {
                if ($userAnswer == $question->correct_answer) {
                    $totalScore += $question->points;
                }
                $maxScore += $question->points;
            } else {
                // Essay sementara 0, maxScore tetap bertambah
                $maxScore += $question->points;
            }
        }

        $finalGrade = ($maxScore > 0) ? round(($totalScore / $maxScore) * 100) : 0;

        LmsSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'grade' => $finalGrade,
            'submitted_at' => now(),
            'student_note' => 'Kuis Online (Auto-graded)',
            'teacher_feedback' => 'Nilai otomatis dari sistem.'
        ]);

        return redirect()->route('students.learning.subject.show', $assignment->subject_id)
            ->with('success', 'Kuis selesai! Nilai Anda: ' . $finalGrade);
    }
}