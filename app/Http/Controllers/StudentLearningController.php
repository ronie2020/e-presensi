<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;
use App\Models\LmsMaterial;
use App\Models\LmsAssignment;
use App\Models\LmsSubmission;
use App\Models\LmsMaterialLog;
use App\Models\LmsDiscussion;

class StudentLearningController extends Controller
{
    public function play($subjectId)
    {
        $student = Auth::guard('student')->user();
        $classId = $student->class_id ?? $student->school_class_id;

        $subject = Subject::findOrFail($subjectId);

        // 1. Ambil Semua Materi & Tugas DENGAN RELASI TOPIC (Bab)
        $materials = LmsMaterial::with(['attachments', 'topic'])
            ->where('subject_id', $subjectId)
            ->where('class_id', $classId)
            ->get();

        $assignments = LmsAssignment::with(['questions', 'topic'])
            ->where('subject_id', $subjectId)
            ->where('class_id', $classId)
            ->where(function($q) {
                $q->whereNull('description')
                  ->orWhere('description', 'NOT LIKE', '%CBT%');
            })
            ->where(function($q) {
                $q->where('assignment_type', '!=', 'quiz')
                  ->orWhereHas('questions');
            })
            ->get();
            
       // 2. Gabungkan dan urutkan
        $combined = $materials->concat($assignments)->sortBy(function($item) {
            // Urutan 1: Nomor Bab (Topic Order)
            $topicOrder = $item->topic ? $item->topic->order_number : 999;
            
            // Urutan 2: Tipe Item (0 untuk Material, 1 untuk Assignment) agar Materi selalu di atas Tugas
            $itemTypeScore = ($item instanceof \App\Models\LmsMaterial) ? 0 : 1;
            
            // Urutan 3: Waktu pembuatan (Created At)
            return sprintf('%05d-%d-%s', $topicOrder, $itemTypeScore, $item->created_at->timestamp);
        })->values();

        $syllabus = [];

        foreach ($combined as $item) {
            $groupTitle = $item->topic ? 'BAB ' . $item->topic->order_number . ': ' . $item->topic->title : 'Materi Umum / Tanpa Bab';

            // ==========================================
            // JIKA TIPE MATERI (Pecah Teks dan Lampiran untuk alur klik Next)
            // ==========================================
            if ($item instanceof \App\Models\LmsMaterial) {
                
                $isCompleted = LmsMaterialLog::where('material_id', $item->id)
                                             ->where('student_id', $student->id)
                                             ->exists();

                // A. Item Teks Pengantar (Halaman Pertama)
                $syllabus[] = [
                    'id' => 'm_' . $item->id . '_intro',
                    'db_id' => $item->id,
                    'group_title' => $groupTitle,
                    'title' => $item->title, 
                    'type' => 'text',
                    'content' => !empty($item->resume) ? $item->resume : 'Silakan klik tombol Lanjut (Next) di bawah untuk membuka lampiran materi ini.',
                    'completed' => $isCompleted,
                    'locked' => false,
                ];

                // B. Item Lampiran (Halaman Kedua, Ketiga, dst setelah klik Next)
                foreach ($item->attachments as $att) {
                    $type = 'file'; 
                    $cleanPath = str_replace(['public\\', 'public/'], '', $att->file_path);
                    
                    $attachmentUrl = str_starts_with($cleanPath, 'http') ? $cleanPath : asset('storage/' . $cleanPath);

                    if ($att->file_type == 'video' || str_contains($att->file_path, 'youtube') || str_contains($att->file_path, 'youtu.be')) {
                        $type = 'video';
                        $attachmentUrl = $att->file_path; 
                    } elseif ($att->file_type == 'link') {
                        $type = 'link';
                        $attachmentUrl = $att->file_path; 
                    }

                    $syllabus[] = [
                        'id' => 'm_' . $item->id . '_att_' . $att->id,
                        'db_id' => $item->id, 
                        'group_title' => $groupTitle,
                        'title' => $att->file_name ?? 'Lampiran ' . strtoupper($type),
                        'type' => $type,
                        'file_url' => $attachmentUrl,
                        'completed' => $isCompleted, 
                        'locked' => false,
                    ];
                }            
            

                // C. Fallback: Jika tidak ada resume dan tidak ada lampiran
                if (empty($item->resume) && $item->attachments->isEmpty()) {
                    $syllabus[] = [
                        'id' => 'm_' . $item->id . '_empty',
                        'db_id' => $item->id,
                        'group_title' => $groupTitle,
                        'title' => $item->title,
                        'type' => 'text',
                        'content' => 'Materi ini belum memiliki konten.',
                        'completed' => $isCompleted,
                        'locked' => false,
                    ];
                }
            } 
            // ==========================================
            // JIKA TIPE TUGAS / KUIS
            // ==========================================
            else {
                $submission = LmsSubmission::where('assignment_id', $item->id)
                                           ->where('student_id', $student->id)
                                           ->first();
                $isCompleted = $submission ? true : false;
                $questionsCount = $item->questions ? $item->questions->count() : 0;
                $isCbt = str_contains(strtolower($item->description ?? ''), 'cbt') || 
                         str_contains(strtolower($item->title ?? ''), 'cbt') || 
                         ($item->assignment_type === 'quiz' && $questionsCount === 0);

                $syllabus[] = [
                    'id' => 'a_' . $item->id,
                    'db_id' => $item->id,
                    'group_title' => $groupTitle, 
                    'title' => 'Tugas: ' . $item->title,
                    'type' => 'assignment', 
                    'assignment_type' => $item->assignment_type,
                    'content' => $item->description,
                    'duration' => $item->duration_minutes ?? 0,
                    'link_url' => $item->link_url,
                    'grade' => $submission->grade ?? null,
                    'completed' => $isCompleted,
                    'locked' => false,
                    'questions' => $item->questions, 
                    'questions_count' => $questionsCount,
                    'is_cbt' => $isCbt,
                ];
            }
        }

        // 3. TERAPKAN LOGIKA PENGUNCIAN (PREREQUISITE)
        // Suatu item hanya terkunci jika materi/tugas sebelumnya belum diselesaikan oleh siswa.
        $isPreviousCompleted = true;
        $previousDbId = null;
        $previousType = null;

        foreach ($syllabus as $key => $item) {
            if ($key === 0) {
                $syllabus[$key]['locked'] = false; 
            } else {
                // Jika item ini adalah bagian dari materi yang sama (intro & attachments)
                if ($item['type'] !== 'assignment' && $previousType !== 'assignment' && $item['db_id'] === $previousDbId) {
                    $syllabus[$key]['locked'] = $syllabus[$key - 1]['locked'];
                } else {
                    $syllabus[$key]['locked'] = !$isPreviousCompleted; 
                }
            }

            // Update status kelengkapan untuk item pada materi/tugas berikutnya
            $isNextDifferent = !isset($syllabus[$key + 1]) || $syllabus[$key + 1]['db_id'] !== $item['db_id'] || $syllabus[$key + 1]['type'] !== $item['type'];
            if ($isNextDifferent) {
                $isPreviousCompleted = $item['completed'];
            }

            $previousDbId = $item['db_id'];
            $previousType = $item['type'];
        }

        return view('students.lms.learning-player', [
            'subject' => $subject,
            'syllabusJson' => json_encode($syllabus)
        ]);
    }

    public function markMaterialComplete(Request $request)
    {
        $request->validate(['material_id' => 'required|integer']);

        LmsMaterialLog::firstOrCreate([
            'student_id' => Auth::guard('student')->id(),
            'material_id' => $request->material_id
        ]);

        return response()->json(['status' => 'success']);
    }

    // ==========================================
    // FORUM DISKUSI & TANYA JAWAB PER MATERI
    // ==========================================

    public function getDiscussions($materialId)
    {
        $discussions = LmsDiscussion::with(['replies'])
            ->where('material_id', $materialId)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'discussions' => $discussions
        ]);
    }

    public function storeDiscussion(Request $request, $materialId)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'parent_id' => 'nullable|integer|exists:lms_discussions,id'
        ]);

        $student = Auth::guard('student')->user();
        $user = Auth::user();

        if ($student) {
            $authorName = $student->name;
            $authorRole = 'Siswa';
            $studentId = $student->id;
            $userId = null;
        } elseif ($user) {
            $authorName = $user->name;
            $authorRole = $user->role ?? 'Guru';
            $userId = $user->id;
            $studentId = null;
        } else {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }

        $discussion = LmsDiscussion::create([
            'material_id' => $materialId,
            'user_id' => $userId,
            'student_id' => $studentId,
            'author_name' => $authorName,
            'author_role' => $authorRole,
            'parent_id' => $request->parent_id,
            'comment' => $request->comment,
            'is_verified' => false
        ]);

        $discussion->load('replies');

        return response()->json([
            'status' => 'success',
            'discussion' => $discussion
        ]);
    }

    public function verifyDiscussion($discussionId)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['Admin', 'Superadmin', 'Guru', 'Guru Mata Pelajaran'])) {
            return response()->json(['status' => 'error', 'message' => 'Hanya guru/admin yang dapat memverifikasi jawaban.'], 403);
        }

        $discussion = LmsDiscussion::findOrFail($discussionId);
        $discussion->is_verified = !$discussion->is_verified;
        $discussion->save();

        return response()->json([
            'status' => 'success',
            'is_verified' => $discussion->is_verified
        ]);
    }

    public function destroyDiscussion($discussionId)
    {
        $discussion = LmsDiscussion::findOrFail($discussionId);
        $student = Auth::guard('student')->user();
        $user = Auth::user();

        $canDelete = false;

        if ($user && in_array($user->role, ['Admin', 'Superadmin', 'Guru', 'Guru Mata Pelajaran'])) {
            $canDelete = true;
        } elseif ($user && $user->id == $discussion->user_id) {
            $canDelete = true;
        } elseif ($student && $student->id == $discussion->student_id) {
            $canDelete = true;
        }

        if (!$canDelete) {
            return response()->json(['status' => 'error', 'message' => 'Anda tidak memiliki hak akses untuk menghapus komentar ini.'], 403);
        }

        $discussion->delete();

        return response()->json(['status' => 'success']);
    }
}