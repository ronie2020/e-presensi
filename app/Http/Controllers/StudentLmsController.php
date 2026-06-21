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
     * Dashboard Belajar (Logika Lama Dipertahankan)
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        $allSubjects = Subject::orderBy('name')->get();
        $prioritySubjects = collect();

        foreach ($allSubjects as $subject) {
            // Cek Tugas Aktif
            $activeTasksCount = LmsAssignment::where('subject_id', $subject->id)
                ->where(function($q) use ($student) {
                    $q->where('class_id', $student->class_id)->orWhereNull('class_id');
                })
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

        return view('students.lms.index', compact('student', 'allSubjects', 'prioritySubjects'));
    }

    /**
     * Halaman Detail Mapel (Logika Lama Dipertahankan)
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

        return view('students.lms.show', compact('subject', 'materials', 'assignments'));
    }

    /**
     * Download Materi (Logika Lama Dipertahankan)
     */
    public function downloadMaterial($id)
    {
        $material = LmsMaterial::findOrFail($id);
        $student = Auth::guard('student')->user();
        if ($material->class_id && $material->class_id != $student->class_id) {
            abort(403);
        }
        return Storage::disk('public')->download($material->file_path);
    }

    /**
     * [DIPERBARUI] Upload Tugas (Bisa File atau Link)
     * Menggabungkan logika lama dengan fitur baru submission_type
     */
     public function submitAssignment(Request $request, $assignmentId)
    {
        $student = Auth::guard('student')->user();
        $assignment = LmsAssignment::findOrFail($assignmentId);

        // Jika Tipe Tugas Guru adalah Link (Siswa hanya baca), submission otomatis selesai saat diklik
        if ($assignment->assignment_type == 'link') {
            LmsSubmission::updateOrCreate(
                ['assignment_id' => $assignmentId, 'student_id' => $student->id],
                [
                    'submitted_at' => now(), 
                    'student_note' => 'Diselesaikan via Link Eksternal',
                    'submission_type' => 'link_external',
                    'grade' => null
                ]
            );
            return back()->with('success', 'Tugas berhasil ditandai selesai!');
        }

         // TAMBAHAN: Jika tipe tugas adalah Video Interaktif (Otomatis selesai tanpa validasi form upload)
        if ($request->submission_type == 'interactive_video' || $assignment->assignment_type == 'interactive_video') {
            LmsSubmission::updateOrCreate(
                ['assignment_id' => $assignmentId, 'student_id' => $student->id],
                [
                    'submitted_at' => now(), 
                    'student_note' => 'Telah menonton dan menjawab kuis Video Interaktif',
                    'submission_type' => 'interactive_video',
                    'grade' => 100 // Beri nilai otomatis 100 jika berhasil menjawab semua
                ]
            );
            return back()->with('success', 'Video Interaktif berhasil diselesaikan!');
        }
        
        // Cek Deadline
        if (!$assignment->allow_late_submission && now() > $assignment->deadline) {
            return back()->with('error', 'Maaf, batas waktu pengumpulan sudah habis.');
        }

        // 1. Validasi Input Fleksibel (File atau Link)
        $request->validate([
            'submission_type' => 'required|in:file,link',
            'file' => 'nullable|required_if:submission_type,file|file|mimes:pdf,doc,docx,jpg,jpeg,png,ppt,pptx,xls,xlsx|max:10240', // Max 10MB
            'link_url' => 'nullable|required_if:submission_type,link|active_url', // Validasi URL aktif
            'student_note' => 'nullable|string|max:500',
        ], [
            'file.required_if' => 'Anda memilih opsi File, harap upload file tugas.',
            'link_url.required_if' => 'Anda memilih opsi Link, harap masukkan URL tugas.',
            'link_url.active_url' => 'Link yang dimasukkan tidak valid atau tidak dapat diakses.',
        ]);

        // 2. Cek submission lama (untuk cleanup file jika ganti metode)
        $submission = LmsSubmission::where('assignment_id', $assignmentId)
            ->where('student_id', $student->id)
            ->first();

        $filePath = $submission ? $submission->file_path : null;
        $linkUrl = $submission ? $submission->link_url : null;

        // 3. Proses Simpan File
        if ($request->submission_type == 'file' && $request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file')->store('lms-submissions', 'public');
            $linkUrl = null; // Reset link jika user beralih ke upload file
        }

        // 4. Proses Simpan Link
        if ($request->submission_type == 'link') {
            $linkUrl = $request->link_url;
            
            // Opsi: Hapus file lama jika user beralih ke link (untuk hemat storage)
            // if ($filePath && Storage::disk('public')->exists($filePath)) {
            //    Storage::disk('public')->delete($filePath);
            // }
            $filePath = null; // Reset file path di DB
        }

        // 5. Simpan ke Database
        LmsSubmission::updateOrCreate(
            ['assignment_id' => $assignmentId, 'student_id' => $student->id],
            [
                'file_path' => $filePath,
                'link_url' => $linkUrl,
                'submission_type' => $request->submission_type,
                'student_note' => $request->student_note,
                'submitted_at' => now(),
                // 'grade' => null, // Opsional: Reset nilai jika siswa mengumpulkan ulang
            ]
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }

    /**
     * Mulai Kuis (Logika Lama Dipertahankan)
     */
    public function startQuiz($id)
    {
        $student = Auth::guard('student')->user();
        $assignment = LmsAssignment::with('questions')->findOrFail($id);

        if ($assignment->isSubmittedBy($student->id)) {
            return redirect()->route('students.learning.subject.show', $assignment->subject_id)
                ->with('error', 'Anda sudah mengerjakan kuis ini.');
        }

        if (now() > $assignment->deadline && !$assignment->allow_late_submission) {
            return back()->with('error', 'Waktu pengerjaan kuis sudah habis.');
        }

        return view('students.lms.quiz', compact('assignment'));
    }

    /**
     * Submit Kuis (Logika Lama Dipertahankan)
     */
     public function submitQuiz(Request $request, $id)
    {
        $student = Auth::guard('student')->user();
        $assignment = LmsAssignment::with('questions')->findOrFail($id);

        $totalScore = 0;
        $maxScore = 0;
        
        // 1. Buat Submission (Draft awal)
        $submission = LmsSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'grade' => 0, // Set 0 dulu, akan diupdate nanti
            'submitted_at' => now(),
            'student_note' => 'Kuis Online (Auto-graded)',
            'teacher_feedback' => 'Nilai otomatis dari sistem.'
        ]);

        // 2. Loop setiap pertanyaan dan simpan jawaban spesifiknya
        foreach ($assignment->questions as $question) {
            $userAnswer = $request->input('answers.' . $question->id);
            $isCorrect = false;
            $pointsEarned = 0;
            
            if ($question->question_type == 'multiple_choice') {
                if ($userAnswer == $question->correct_answer) {
                    $isCorrect = true;
                    $pointsEarned = $question->points;
                    $totalScore += $pointsEarned;
                }
                $maxScore += $question->points;
            } else {
                $maxScore += $question->points; // Esai ditambahkan ke maxScore tapi belum dapat points otomatis
            }

            // Simpan detail jawaban siswa ke tabel LmsSubmissionAnswer
            \App\Models\LmsSubmissionAnswer::create([
                'submission_id' => $submission->id,
                'question_id' => $question->id,
                'answer_text' => $userAnswer,
                'points' => $pointsEarned,
                'is_correct' => $isCorrect
            ]);
        }

        // 3. Kalkulasi dan Update Nilai Akhir (Skala 100)
        $finalGrade = ($maxScore > 0) ? round(($totalScore / $maxScore) * 100) : 0;
        $submission->update(['grade' => $finalGrade]);

        return redirect()->route('students.learning.subject.show', $assignment->subject_id)
            ->with('success', 'Kuis selesai! Nilai Anda: ' . $finalGrade);
    }

    /**
     * TAMBAHAN: Method untuk Melacak Durasi Belajar Siswa
     */
    public function logTime(Request $request)
    {
        $request->validate([
            'material_id' => 'required|integer',
            'time_spent' => 'required|integer', // dalam hitungan detik
        ]);

        $log = \App\Models\LmsMaterialLog::firstOrCreate(
            [
                'student_id' => Auth::guard('student')->id(), 
                'material_id' => $request->material_id
            ],
            ['time_spent_seconds' => 0]
        );

        // Akumulasikan detik belajar
        $log->time_spent_seconds += $request->time_spent;
        $log->save();

        return response()->json(['status' => 'success', 'logged_seconds' => $request->time_spent]);
    }
}