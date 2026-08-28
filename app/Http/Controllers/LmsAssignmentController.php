<?php

namespace App\Http\Controllers;

use App\Models\LmsAssignment;
use App\Models\LmsQuizQuestion;
use App\Models\LmsSubmission;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LmsAssignmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. LOGIKA GROUPING
        $subQuery = LmsAssignment::selectRaw('MIN(id) as id')
            ->groupBy('title', 'subject_id', 'deadline', 'created_at');

        if ($user->role !== 'admin') {
            $subQuery->where('teacher_id', $user->id);
        }

        // 2. Ambil Data
        $assignments = LmsAssignment::whereIn('id', $subQuery)
            ->with(['subject', 'schoolClass'])
            ->withCount('submissions')
            ->latest()
            ->paginate(10);

        // 3. Tambahkan info bulk
        foreach ($assignments as $assignment) {
            $siblingsQuery = LmsAssignment::where('title', $assignment->title)
                ->where('created_at', $assignment->created_at)
                ->where('deadline', $assignment->deadline);
                
            if ($user->role !== 'admin') {
                $siblingsQuery->where('teacher_id', $user->id);
            }

            $siblingsCount = $siblingsQuery->count();
            
            // Hitung total pengumpulan global
            $allIds = $siblingsQuery->pluck('id');
            $globalSubmissions = LmsSubmission::whereIn('assignment_id', $allIds)->count();

            $assignment->is_bulk = $siblingsCount > 1;
            $assignment->total_classes = $siblingsCount;
            $assignment->global_submissions_count = $globalSubmissions;
            
            if ($assignment->is_bulk && $assignment->schoolClass) {
                 preg_match('/\d+/', $assignment->schoolClass->name, $matches);
                 $assignment->target_grade = $matches[0] ?? ''; 
            }
        }

        $subjects = Subject::all();
        $classes = SchoolClass::orderBy('name', 'asc')->get();

        return view('lms.assignments.index', compact('assignments', 'subjects', 'classes'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        return view('lms.assignments.create', compact('subjects', 'classes'));
    }

    public function store(Request $request)
    {
        // Tetapkan deskripsi berdasarkan tipe tugas
        $description = null;
        $description = null;
        if ($request->assignment_type == 'file_upload') {
            $description = $request->description_file;
        } elseif ($request->assignment_type == 'quiz') {
            $description = $request->description_quiz;
        } elseif ($request->assignment_type == 'link') {
            $description = $request->description_link;
        } elseif ($request->assignment_type == 'interactive_video') {
            $description = "Tugas Video Interaktif. Silakan tonton video ini dengan saksama dan jawab pertanyaan yang muncul secara otomatis di tengah video.";
        }

        $request->merge(['description' => $description]);

        // Validasi diperbarui untuk mendukung 'interactive_video'
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'required|exists:topics,id', // <--- WAJIB ADA BAB
            'deadline' => 'required',
            'description' => 'required|string',
            'assignment_type' => 'required|in:file_upload,quiz,link,interactive_video',
            'target_type' => 'required|in:grade,class',
            'class_id' => 'required_if:target_type,class|nullable|exists:classes,id',
            'link_url' => 'nullable|required_if:assignment_type,link|url',
            'youtube_url' => 'nullable|required_if:assignment_type,interactive_video|url',
            'duration_minutes' => 'nullable|required_if:assignment_type,quiz|integer|min:1',
            'questions' => 'nullable|required_if:assignment_type,quiz|array',
            'interactive_questions' => 'nullable|required_if:assignment_type,interactive_video|array',
        ]);

        $teacherId = Auth::id();
        $now = now(); 

        try {
            $deadline = \Carbon\Carbon::parse($request->deadline)->format('Y-m-d H:i:s');

            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $teacherId, $description, $deadline, $now) {
                
                $targetClassIds = [];
                if ($request->target_type == 'class') {
                    $targetClassIds[] = $request->class_id;
                } elseif ($request->target_type == 'grade') {
                    $classes = \App\Models\SchoolClass::where('name', 'like', $request->target_grade . '%')->get();
                    foreach ($classes as $c) {
                        $targetClassIds[] = $c->id;
                    }
                }

                if (empty($targetClassIds)) {
                    throw new \Exception("Tidak ditemukan kelas untuk jenjang " . $request->target_grade);
                }

                foreach ($targetClassIds as $classId) {
                    
                    $finalLinkUrl = null;
                    if ($request->assignment_type == 'link') {
                        $finalLinkUrl = $request->link_url;
                    } elseif ($request->assignment_type == 'interactive_video') {
                        $finalLinkUrl = $request->youtube_url;
                    }

                    $assignment = \App\Models\LmsAssignment::create([
                        'teacher_id' => $teacherId,
                        'subject_id' => $request->subject_id,
                        'topic_id' => $request->topic_id, // <--- SIMPAN KE DATABASE
                        'class_id' => $classId,
                        'title' => $request->title,
                        'description' => $description,
                        'deadline' => $deadline,
                        'assignment_type' => $request->assignment_type,
                        'link_url' => $finalLinkUrl,
                        'duration_minutes' => $request->assignment_type == 'quiz' ? $request->duration_minutes : null,
                        'allow_late_submission' => $request->has('allow_late_submission'),
                        'created_at' => $now, 
                        'updated_at' => $now,
                    ]);

                    // ... (Logika simpan pertanyaan Kuis & Video Interaktif tetap sama)
                    // PROSES PENYIMPANAN SOAL KUIS BIASA
                    if ($request->assignment_type == 'quiz' && $request->has('questions')) {
                        foreach ($request->questions as $q) {
                            \App\Models\LmsQuizQuestion::create([
                                'assignment_id' => $assignment->id,
                                'question_text' => $q['text'] ?? '',
                                'question_type' => $q['type'] ?? 'multiple_choice',
                                'options' => $q['options'] ?? null, 
                                'correct_answer' => $q['correct'] ?? null,
                                'points' => $q['points'] ?? 10,
                            ]);
                        }
                    }

                    // PROSES PENYIMPANAN SOAL VIDEO INTERAKTIF
                    if ($request->assignment_type == 'interactive_video' && $request->has('interactive_questions')) {
                        foreach ($request->interactive_questions as $iq) {
                            $totalSeconds = ((int)($iq['minute'] ?? 0) * 60) + (int)($iq['second'] ?? 0);
                            $options = $iq['options'] ?? [];
                            $options['time_trigger'] = $totalSeconds;

                            \App\Models\LmsQuizQuestion::create([
                                'assignment_id' => $assignment->id,
                                'question_text' => $iq['text'] ?? '',
                                'question_type' => 'multiple_choice', 
                                'options' => $options, 
                                'correct_answer' => $iq['correct'] ?? null,
                                'points' => 10,
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

    public function edit($id)
    {
        $assignment = LmsAssignment::findOrFail($id);
        
        if (Auth::user()->role !== 'admin' && $assignment->teacher_id !== Auth::id()) {
            abort(403);
        }

        $siblingsCount = LmsAssignment::where('teacher_id', $assignment->teacher_id)
            ->where('title', $assignment->title)
            ->where('created_at', $assignment->created_at)
            ->count();

        $isBulk = $siblingsCount > 1;

        $subjects = Subject::all();
        $classes = SchoolClass::orderBy('name', 'asc')->get();

        return view('lms.assignments.edit', compact('assignment', 'subjects', 'classes', 'isBulk'));
    }

    public function update(Request $request, $id)
    {
        $assignment = LmsAssignment::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $assignment->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'required|exists:topics,id',
            'deadline' => 'required',
            'description' => 'required|string',
            'link_url' => 'nullable|url',
        ]);

        try {
            $deadline = Carbon::parse($request->deadline)->format('Y-m-d H:i:s');

            DB::transaction(function () use ($request, $assignment, $deadline) {
                $siblings = LmsAssignment::where('teacher_id', $assignment->teacher_id)
                    ->where('title', $assignment->title)
                    ->where('created_at', $assignment->created_at)
                    ->get();

                if ($siblings->isEmpty()) $siblings = collect([$assignment]);

                foreach ($siblings as $target) {
                    $target->update([
                        'title' => $request->title,
                        'subject_id' => $request->subject_id,
                        'deadline' => $deadline,
                        'description' => $request->description,
                        'link_url' => $request->link_url, 
                        'duration_minutes' => $request->duration_minutes,
                        'allow_late_submission' => $request->has('allow_late_submission'),
                    ]);
                }
            });

            return redirect()->route('lms.assignments.index')->with('success', 'Tugas berhasil diperbarui untuk semua kelas terkait.');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal update: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $assignment = LmsAssignment::findOrFail($id);
        
        if ($user->role !== 'admin' && $assignment->teacher_id !== $user->id) abort(403);
        
        $siblings = LmsAssignment::where('teacher_id', $assignment->teacher_id)
            ->where('title', $assignment->title)
            ->where('created_at', $assignment->created_at)
            ->get();

        foreach ($siblings as $target) {
            $target->delete();
        }

        return redirect()->route('lms.assignments.index')->with('success', 'Tugas dihapus dari semua kelas.');
    }

    public function submissions(LmsAssignment $assignment)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $assignment->teacher_id !== $user->id) abort(403);

        // 1. CARI SAUDARA (Assignment di kelas lain yang satu paket)
        $siblings = LmsAssignment::where('teacher_id', $assignment->teacher_id)
            ->where('title', $assignment->title)
            ->where('created_at', $assignment->created_at)
            ->get();

        // 2. Kumpulkan ID Assignment & ID Kelas
        $assignmentIds = $siblings->pluck('id');
        $classIds = $siblings->pluck('class_id');

        // 3. Ambil Submission DENGAN RELASI ANSWER        
        $submissions = LmsSubmission::with(['student.schoolClass', 'answers.question']) 
            ->whereIn('assignment_id', $assignmentIds)
            ->get()
            ->keyBy('student_id'); 
        
        // 4. Ambil Siswa dari SEMUA kelas terkait
        $allStudents = Student::with('schoolClass')
                        ->whereIn('class_id', $classIds)
                        ->orderBy('class_id')
                        ->orderBy('name')
                        ->get();

        // 5. Inject properti tambahan untuk View
        $assignment->is_bulk = $siblings->count() > 1;
        
        if ($assignment->is_bulk && $assignment->schoolClass) {
             preg_match('/\d+/', $assignment->schoolClass->name, $matches);
             $assignment->target_grade = $matches[0] ?? ''; 
        }

        return view('lms.assignments.submissions', compact('assignment', 'submissions', 'allStudents'));
    }

     /**
     * TAMBAHAN: Melihat Detail Jawaban Kuis Siswa
     */
    public function showSubmissionDetail(LmsSubmission $submission)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $submission->assignment->teacher_id !== $user->id) {
            abort(403);
        }

        // Load relasi ke tugas, siswa, dan jawaban detailnya
        $submission->load(['assignment', 'student.schoolClass', 'answers.question']);

        return view('lms.assignments.submission_detail', compact('submission'));
    }

    public function grade(Request $request, LmsSubmission $submission)
    {
        $user = Auth::user();
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

    public function destroySubmission($id)
    {
        $submission = LmsSubmission::findOrFail($id);
        
        if (Auth::user()->role !== 'admin' && $submission->assignment->teacher_id !== Auth::id()) {
            abort(403);
        }

        $submission->delete();

        return back()->with('success', 'Data jawaban siswa berhasil dihapus. Siswa dapat mengerjakan ulang.');
    }
}