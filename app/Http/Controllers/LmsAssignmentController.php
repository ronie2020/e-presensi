<?php

namespace App\Http\Controllers;

use App\Models\LmsAssignment;
use App\Models\LmsQuizQuestion; // Import Model Soal
use App\Models\LmsSubmission;
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LmsAssignmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = LmsAssignment::with(['subject', 'schoolClass'])->withCount('submissions');

        if ($user->role !== 'admin') {
            $query->where('teacher_id', $user->id);
        }

        $assignments = $query->latest()->paginate(10);
        return view('lms.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        return view('lms.assignments.create', compact('subjects', 'classes'));
    }

    // ===> FUNGSI STORE YANG DIUPDATE <===
    public function store(Request $request)
    {
        // 1. Validasi Umum
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'deadline' => 'required|date',
            'description' => 'required|string',
            'assignment_type' => 'required|in:file_upload,quiz,link', // Tipe Tugas
            'target_type' => 'required|in:grade,class',
            
            // Validasi Khusus Link
            'link_url' => 'nullable|required_if:assignment_type,link|url',
            
            // Validasi Khusus Quiz
            'duration_minutes' => 'nullable|required_if:assignment_type,quiz|integer|min:5',
            'questions' => 'nullable|required_if:assignment_type,quiz|array|min:1',
        ]);

        $teacherId = Auth::id();

        DB::transaction(function () use ($request, $teacherId) {
            
            // Tentukan Kelas Target
            $targetClassIds = [];
            if ($request->target_type == 'class') {
                $targetClassIds[] = $request->class_id;
            } elseif ($request->target_type == 'grade') {
                $classes = SchoolClass::where('name', 'like', $request->target_grade . '%')->get();
                foreach ($classes as $c) $targetClassIds[] = $c->id;
            }

            // Loop Simpan ke Setiap Kelas
            foreach ($targetClassIds as $classId) {
                
                // A. Simpan Header Assignment
                $assignment = LmsAssignment::create([
                    'teacher_id' => $teacherId,
                    'subject_id' => $request->subject_id,
                    'class_id' => $classId,
                    'title' => $request->title,
                    'description' => $request->description,
                    'deadline' => $request->deadline,
                    'assignment_type' => $request->assignment_type, // file_upload, quiz, link
                    'link_url' => $request->assignment_type == 'link' ? $request->link_url : null,
                    'duration_minutes' => $request->assignment_type == 'quiz' ? $request->duration_minutes : null,
                    'allow_late_submission' => $request->has('allow_late_submission'),
                ]);

                // B. Simpan Soal (Jika Tipe Quiz)
                if ($request->assignment_type == 'quiz' && $request->has('questions')) {
                    foreach ($request->questions as $q) {
                        LmsQuizQuestion::create([
                            'assignment_id' => $assignment->id,
                            'question_text' => $q['text'],
                            'question_type' => $q['type'], // multiple_choice atau essay
                            'options' => isset($q['options']) ? $q['options'] : null, // JSON Array
                            'correct_answer' => isset($q['correct']) ? $q['correct'] : null,
                            'points' => $q['points'] ?? 10,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('lms.assignments.index')->with('success', 'Tugas berhasil diterbitkan!');
    }

    // ... (Fungsi submissions, grade, destroy tetap sama) ...
    public function submissions(LmsAssignment $assignment)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $assignment->teacher_id !== $user->id) abort(403);

        $submissions = LmsSubmission::with('student')->where('assignment_id', $assignment->id)->get();
        $allStudents = $assignment->schoolClass->students;

        return view('lms.assignments.submissions', compact('assignment', 'submissions', 'allStudents'));
    }

    public function grade(Request $request, LmsSubmission $submission)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $submission->assignment->teacher_id !== $user->id) abort(403);

        $request->validate(['grade' => 'required|integer|min:0|max:100']);
        $submission->update(['grade' => $request->grade, 'teacher_feedback' => $request->feedback]);
        return back()->with('success', 'Nilai tersimpan.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $assignment = LmsAssignment::findOrFail($id);
        if ($user->role !== 'admin' && $assignment->teacher_id !== $user->id) abort(403);
        $assignment->delete();
        return redirect()->route('lms.assignments.index')->with('success', 'Tugas dihapus.');
    }
}