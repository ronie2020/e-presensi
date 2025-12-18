<?php

namespace App\Http\Controllers;

use App\Models\LmsAssignment;
use App\Models\LmsQuizQuestion;
use App\Models\LmsSubmission;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Student; // Pastikan Model Student diimport
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function store(Request $request)
    {
        // 1. Tentukan Deskripsi
        $description = null;
        if ($request->assignment_type == 'file_upload') {
            $description = $request->description_file;
        } elseif ($request->assignment_type == 'quiz') {
            $description = $request->description_quiz;
        } elseif ($request->assignment_type == 'link') {
            $description = $request->description_link;
        }

        $request->merge(['description' => $description]);

        // 2. Validasi
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:' . Subject::class . ',id',
            'deadline' => 'required',
            'description' => 'required|string',
            'assignment_type' => 'required|in:file_upload,quiz,link',
            'target_type' => 'required|in:grade,class',
            'class_id' => 'required_if:target_type,class|nullable|exists:' . SchoolClass::class . ',id',
            'link_url' => 'nullable|required_if:assignment_type,link|url',
            'duration_minutes' => 'nullable|required_if:assignment_type,quiz|integer|min:1',
            'questions' => 'nullable|required_if:assignment_type,quiz|array',
        ]);

        $teacherId = Auth::id();

        try {
            $deadline = Carbon::parse($request->deadline)->format('Y-m-d H:i:s');

            DB::transaction(function () use ($request, $teacherId, $description, $deadline) {
                
                // 3. Cari Kelas Target
                $targetClassIds = [];
                if ($request->target_type == 'class') {
                    $targetClassIds[] = $request->class_id;
                } elseif ($request->target_type == 'grade') {
                    $classes = SchoolClass::where('name', 'like', $request->target_grade . '%')->get();
                    foreach ($classes as $c) {
                        $targetClassIds[] = $c->id;
                    }
                }

                if (empty($targetClassIds)) {
                    throw new \Exception("Tidak ditemukan kelas untuk jenjang " . $request->target_grade);
                }

                // 4. Simpan Tugas
                foreach ($targetClassIds as $classId) {
                    $assignment = LmsAssignment::create([
                        'teacher_id' => $teacherId,
                        'subject_id' => $request->subject_id,
                        'class_id' => $classId,
                        'title' => $request->title,
                        'description' => $description,
                        'deadline' => $deadline,
                        'assignment_type' => $request->assignment_type,
                        'link_url' => $request->assignment_type == 'link' ? $request->link_url : null,
                        'duration_minutes' => $request->assignment_type == 'quiz' ? $request->duration_minutes : null,
                        'allow_late_submission' => $request->has('allow_late_submission'),
                    ]);

                    // 5. Simpan Soal Kuis
                    if ($request->assignment_type == 'quiz' && $request->has('questions')) {
                        foreach ($request->questions as $q) {
                            LmsQuizQuestion::create([
                                'assignment_id' => $assignment->id,
                                'question_text' => $q['text'] ?? '',
                                'question_type' => $q['type'] ?? 'multiple_choice',
                                'options' => $q['options'] ?? null, 
                                'correct_answer' => $q['correct'] ?? null,
                                'points' => $q['points'] ?? 10,
                            ]);
                        }
                    }
                }
            });

            return redirect()->route('lms.assignments.index')->with('success', 'Tugas berhasil diterbitkan!');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $assignment = LmsAssignment::findOrFail($id);
        if ($user->role !== 'admin' && $assignment->teacher_id !== $user->id) abort(403);
        $assignment->delete();
        return redirect()->route('lms.assignments.index')->with('success', 'Tugas dihapus.');
    }

    // ===> PERBAIKAN DI SINI <===
    public function submissions(LmsAssignment $assignment)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $assignment->teacher_id !== $user->id) abort(403);

        // Ambil data pengumpulan
        $submissions = LmsSubmission::with('student')->where('assignment_id', $assignment->id)->get();
        
        // FIX: Ambil daftar semua siswa di kelas target untuk ditampilkan di tabel
        $allStudents = Student::where('class_id', $assignment->class_id)
                        ->orderBy('name')
                        ->get();

        // Kirim variable $allStudents ke view
        return view('lms.assignments.submissions', compact('assignment', 'submissions', 'allStudents'));
    }

    public function grade(Request $request, LmsSubmission $submission)
    {
        $user = Auth::user();
        // Validasi kepemilikan
        if ($user->role !== 'admin' && $submission->assignment->teacher_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'grade' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:255'
        ]);

        $submission->update([
            'grade' => $request->grade,
            'teacher_feedback' => $request->feedback
        ]);

        return back()->with('success', 'Nilai berhasil disimpan.');
    }
}