<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;
use App\Models\LmsMaterial;
use App\Models\LmsAssignment;
use App\Models\LmsSubmission;
use App\Models\LmsMaterialLog;

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

                $syllabus[] = [
                    'id' => 'a_' . $item->id,
                    'db_id' => $item->id,
                    'group_title' => $groupTitle, 
                    'title' => 'Tugas: ' . $item->title,
                    'type' => 'assignment', 
                    'assignment_type' => $item->assignment_type,
                    'content' => $item->description,
                    'duration' => $item->duration_minutes,
                    'link_url' => $item->link_url,
                    'grade' => $submission->grade ?? null,
                    'completed' => $isCompleted,
                    'locked' => false,
                    'questions' => $item->questions, 
                ];
            }
        }

        // 3. TERAPKAN LOGIKA PENGUNCIAN (PREREQUISITE)
        $isPreviousCompleted = true;
        foreach ($syllabus as $key => $item) {
            if ($key === 0) {
                $syllabus[$key]['locked'] = false; 
            } else {
                $syllabus[$key]['locked'] = !$isPreviousCompleted; 
            }
            $isPreviousCompleted = $syllabus[$key]['completed'];
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
   
}