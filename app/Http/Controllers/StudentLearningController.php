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

        // 1. Ambil Semua Materi & Tugas
        $materials = LmsMaterial::with('attachments')
            ->where('subject_id', $subjectId)
            ->where('class_id', $classId)
            ->get();

        $assignments = LmsAssignment::where('subject_id', $subjectId)
            ->where('class_id', $classId)
            ->get();
            
        // 2. Gabungkan dan urutkan berdasarkan waktu pembuatan
        $combined = $materials->concat($assignments)->sortBy('created_at')->values();

        $syllabus = [];

        foreach ($combined as $item) {
            // ==========================================
            // JIKA TIPE MATERI (Dipecah menjadi sub-item)
            // ==========================================
            if ($item instanceof \App\Models\LmsMaterial) {
                $groupTitle = $item->title; // Judul Induk (Group)

                // Cek status penyelesaian materi di Database
                $isCompleted = LmsMaterialLog::where('material_id', $item->id)
                                             ->where('student_id', $student->id)
                                             ->exists();

                // A. Item ke-1: Pengantar Materi (Jika guru mengisi teks resume)
                if (!empty($item->resume)) {
                    $syllabus[] = [
                        'id' => 'm_' . $item->id . '_intro',
                        'db_id' => $item->id,
                        'group_title' => $groupTitle,
                        'title' => 'Pengantar Materi',
                        'type' => 'text',
                        'content' => $item->resume,
                        'completed' => $isCompleted,
                        'locked' => false, // Akan dihitung ulang di bawah
                    ];
                }

                // B. Item ke-2 dst: Lampiran (Video, PDF, Link)
                foreach ($item->attachments as $att) {
                    $type = 'file';
                    
                    // Format URL Lampiran agar bisa diakses
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
                        'locked' => false, // Akan dihitung ulang di bawah
                    ];
                }

                // C. Fallback: Jika materi benar-benar kosong
                if (empty($item->resume) && $item->attachments->isEmpty()) {
                    $syllabus[] = [
                        'id' => 'm_' . $item->id . '_empty',
                        'db_id' => $item->id,
                        'group_title' => $groupTitle,
                        'title' => 'Materi Kosong',
                        'type' => 'text',
                        'content' => 'Tidak ada konten pembelajaran pada materi ini.',
                        'completed' => $isCompleted,
                        'locked' => false,
                    ];
                }
            } 
            // ==========================================
            // JIKA TIPE TUGAS / KUIS
            // ==========================================
            else {
                // Cek apakah siswa sudah mengumpulkan tugas ini
                $submission = LmsSubmission::where('assignment_id', $item->id)
                                           ->where('student_id', $student->id)
                                           ->first();
                $isCompleted = $submission ? true : false;

                $syllabus[] = [
                    'id' => 'a_' . $item->id,
                    'db_id' => $item->id,
                    'group_title' => 'Tugas: ' . $item->title,
                    'title' => 'Kerjakan Latihan',
                    'type' => 'assignment', 
                    'assignment_type' => $item->assignment_type,
                    'content' => $item->description,
                    'duration' => $item->duration_minutes,
                    'link_url' => $item->link_url,
                    'grade' => $submission->grade ?? null,
                    'completed' => $isCompleted,
                    'locked' => false,
                ];
            }
        }

        // 3. TERAPKAN LOGIKA PENGUNCIAN (PREREQUISITE)
        // Kunci materi jika item sebelumnya belum diselesaikan oleh siswa
        $isPreviousCompleted = true;
        foreach ($syllabus as $key => $item) {
            if ($key === 0) {
                $syllabus[$key]['locked'] = false; // Item urutan pertama selalu terbuka
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

    /**
     * Menyimpan log bahwa materi telah dibaca/diselesaikan
     */
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