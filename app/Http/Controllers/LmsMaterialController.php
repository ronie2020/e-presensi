<?php

namespace App\Http\Controllers;

use App\Models\LmsMaterial;
use App\Models\LmsMaterialAttachment; 
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LmsMaterialController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $subQuery = LmsMaterial::selectRaw('MIN(id) as id')
            ->where('teacher_id', $user->id)
            ->groupBy('title', 'subject_id', 'created_at');

        if ($request->filled('search')) {
            $subQuery->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('subject')) {
            $subQuery->where('subject_id', $request->subject);
        }        

        $subQuery->groupBy('title', 'subject_id', 'created_at');

        $materials = LmsMaterial::whereIn('id', $subQuery)
            ->with(['subject', 'schoolClass', 'attachments']) 
            ->latest()
            ->paginate(10)
            ->withQueryString(); 

        foreach ($materials as $material) {
            $siblingsQuery = LmsMaterial::where('teacher_id', $user->id)
                ->where('title', $material->title)
                ->where('created_at', $material->created_at);

            $siblingsCount = $siblingsQuery->count();
            
            $material->is_bulk = $siblingsCount > 1;
            $material->total_classes = $siblingsCount;

            if ($material->is_bulk && $material->schoolClass) {
                preg_match('/\d+/', $material->schoolClass->name, $matches);
                $material->target_grade = $matches[0] ?? ''; 
            }
        }

        $subjects = Subject::all();
        $classes = SchoolClass::orderBy('name', 'asc')->get();

        return view('lms.materials.index', compact('materials', 'subjects', 'classes'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        return view('lms.materials.create', compact('subjects', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'required|exists:topics,id',
            'resume' => 'nullable|string', 
            'target_type' => 'required|in:class,grade',
            'class_id' => 'nullable|exists:classes,id', 
            'target_grade' => 'required_if:target_type,grade',
            'new_attachments' => 'nullable|array',
            'new_attachments.*.file' => 'nullable|file|max:20480', 
            'new_attachments.*.link' => 'nullable|url',
            'new_attachments.*.type' => 'required_with:new_attachments|in:file,video,link',
        ]);

        $teacherId = Auth::id();
        $now = now(); 

        DB::transaction(function () use ($request, $teacherId, $now) {
            $targetClassIds = [];
            
            if ($request->target_type == 'class') {
                if ($request->class_id) $targetClassIds[] = $request->class_id;
            } elseif ($request->target_type == 'grade') {
                $classes = SchoolClass::where('name', 'like', $request->target_grade . '%')->get();
                foreach ($classes as $c) $targetClassIds[] = $c->id;
            }

            if (empty($targetClassIds)) return; 

            $processedAttachments = [];
            if ($request->has('new_attachments')) {
                foreach ($request->new_attachments as $index => $item) {
                    $path = null;
                    // Pastikan 'name' tidak null jika kosong dari frontend
                    $name = !empty($item['name']) ? $item['name'] : 'Lampiran';
                    $type = $item['type'] ?? 'file';

                    if ($type == 'file' && isset($item['file'])) {
                        $file = $item['file'];
                        $path = $file->store('lms-materials', 'public');
                        // Gunakan nama file asli jika 'name' dari input kosong/default
                        if ($name == 'Lampiran') {
                            $name = $file->getClientOriginalName();
                        }
                    } elseif (($type == 'link' || $type == 'video') && isset($item['link'])) {
                        // Pastikan key 'link' dibaca
                        $path = $item['link'];
                    }

                    if ($path) {
                        $processedAttachments[] = [
                            'path' => $path,
                            'name' => $name,
                            'type' => $type
                        ];
                    }
                }
            }
            foreach ($targetClassIds as $classId) {
                $material = LmsMaterial::create([
                    'teacher_id' => $teacherId,
                    'subject_id' => $request->subject_id,
                    'class_id' => $classId,
                    'title' => $request->title,
                    'resume' => $request->resume,           
                    'type' => 'document', 
                    'created_at' => $now, 
                    'updated_at' => $now,
                ]);

                foreach ($processedAttachments as $att) {
                    LmsMaterialAttachment::create([
                        'material_id' => $material->id,
                        'file_path' => $att['path'],
                        'file_name' => $att['name'],
                        'file_type' => $att['type']
                    ]);
                }
            }
        });

        return redirect()->route('lms.materials.index')->with('success', 'Materi berhasil diterbitkan!');
    }

    public function edit($id)
    {
        $material = LmsMaterial::with('attachments')->findOrFail($id);
        
        if (Auth::user()->role !== 'admin' && $material->teacher_id !== Auth::id()) {
            abort(403);
        }

        $siblingsCount = LmsMaterial::where('teacher_id', $material->teacher_id)
            ->where('title', $material->title)
            ->where('created_at', $material->created_at)
            ->count();

        $isBulk = $siblingsCount > 1;

        $subjects = Subject::all();
        $classes = SchoolClass::orderBy('name', 'asc')->get();

        return view('lms.materials.edit', compact('material', 'subjects', 'classes', 'isBulk'));
    }

    public function update(Request $request, $id)
    {
        $material = LmsMaterial::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $material->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'resume' => 'nullable|string',
            'new_attachments' => 'nullable|array',
            'topic_id' => 'required|exists:topics,id',
        ]);

        DB::transaction(function () use ($request, $material) {
            
            $siblings = LmsMaterial::where('teacher_id', $material->teacher_id)
                ->where('title', $material->title)
                ->where('created_at', $material->created_at)
                ->get();

            if ($siblings->isEmpty()) $siblings = collect([$material]);

            foreach ($siblings as $targetMaterial) {
                $targetMaterial->update([
                    'title' => $request->title,
                    'subject_id' => $request->subject_id,
                    'topic_id' => $request->topic_id,
                    'resume' => $request->resume,
                ]);

                if ($request->has('delete_attachments')) {
                    foreach ($request->delete_attachments as $attId) {
                        $attToDelete = LmsMaterialAttachment::find($attId);
                        if ($attToDelete) {
                            $relatedAttachments = LmsMaterialAttachment::whereIn('material_id', $siblings->pluck('id'))
                                ->where('file_path', $attToDelete->file_path)
                                ->get();

                            foreach($relatedAttachments as $relAtt) {
                                if ($relAtt->file_type == 'file' && Storage::disk('public')->exists($relAtt->file_path)) {
                                    Storage::disk('public')->delete($relAtt->file_path); 
                                }
                                $relAtt->delete();
                            }
                        }
                    }
                }
            }

            if ($request->has('new_attachments')) {
                foreach ($request->new_attachments as $item) {
                    $path = null;
                    $name = !empty($item['name']) ? $item['name'] : 'Lampiran';
                    $type = $item['type'] ?? 'file';

                    if ($type == 'file' && isset($item['file'])) {
                        $file = $item['file'];
                        $path = $file->store('lms-materials', 'public');
                        if ($name == 'Lampiran') {
                            $name = $file->getClientOriginalName();
                        }
                    } elseif (($type == 'link' || $type == 'video') && isset($item['link'])) {
                        $path = $item['link'];
                    }

                    if ($path) {
                        foreach ($siblings as $targetMaterial) {
                            LmsMaterialAttachment::create([
                                'material_id' => $targetMaterial->id,
                                'file_path' => $path,
                                'file_name' => $name,
                                'file_type' => $type
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->route('lms.materials.index')->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $material = LmsMaterial::findOrFail($id);

        if ($user->role !== 'admin' && $material->teacher_id !== $user->id) abort(403);
        
        $siblings = LmsMaterial::where('teacher_id', $material->teacher_id)
            ->where('title', $material->title)
            ->where('created_at', $material->created_at)
            ->get();

        foreach ($siblings as $target) {
            foreach($target->attachments as $att) {
                if($att->file_type == 'file' && Storage::disk('public')->exists($att->file_path)) {
                    Storage::disk('public')->delete($att->file_path);
                }
            }
            $target->delete(); 
        }

        return redirect()->route('lms.materials.index')->with('success', 'Materi berhasil dihapus.');
    }

    public function download($id)
    {
        $material = LmsMaterial::with('attachments')->findOrFail($id);
        $attachment = $material->attachments->where('file_type', 'file')->first();

        if ($attachment && Storage::disk('public')->exists($attachment->file_path)) {
            return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
        }

        return back()->with('error', 'File tidak ditemukan.');
    }
  
    public function readers($id)
    {
        $user = Auth::user();
        $material = LmsMaterial::with('subject')->findOrFail($id);

        if ($user->role !== 'admin' && $material->teacher_id !== $user->id) {
            abort(403);
        }

        $siblings = LmsMaterial::where('teacher_id', $material->teacher_id)
            ->where('title', $material->title)
            ->where('created_at', $material->created_at)
            ->pluck('id');

        $logs = \App\Models\LmsMaterialLog::with('student.schoolClass')
            ->whereIn('material_id', $siblings)
            ->get()
            ->sortBy(function($log) {
                $className = $log->student?->schoolClass?->name ?? '';
                $studentName = $log->student?->name ?? '';
                return $className . '-' . $studentName;
            });

        return view('lms.materials.readers', compact('material', 'logs'));
    }

    public function previewPlayer($subjectId, $classId)
    {
        $subject = \App\Models\Subject::findOrFail($subjectId);
        
        $materials = \App\Models\LmsMaterial::with(['attachments', 'topic'])
            ->where('subject_id', $subjectId)
            ->where('class_id', $classId)
            ->get();

        $assignments = \App\Models\LmsAssignment::with(['questions', 'topic'])
            ->where('subject_id', $subjectId)
            ->where('class_id', $classId)
            ->get();
            
        $combined = $materials->concat($assignments)->sortBy(function($item) {
            $topicOrder = $item->topic ? $item->topic->order_number : 999;
            return sprintf('%05d-%s', $topicOrder, $item->created_at->timestamp);
        })->values();

        $syllabus = [];

        foreach ($combined as $item) {
            $groupTitle = $item->topic ? 'BAB ' . $item->topic->order_number . ': ' . $item->topic->title : 'Materi Umum / Tanpa Bab';

            if ($item instanceof \App\Models\LmsMaterial) {
                
                if (!empty($item->resume)) {
                    $syllabus[] = [
                        'id' => 'm_' . $item->id . '_intro',
                        'db_id' => $item->id,
                        'group_title' => $groupTitle,
                        'title' => $item->title,
                        'type' => 'text',
                        'content' => $item->resume,
                        'completed' => true,
                        'locked' => false,
                    ];
                }

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
                        'completed' => true,
                        'locked' => false,
                    ];
                }

                if (empty($item->resume) && $item->attachments->isEmpty()) {
                    $syllabus[] = [
                        'id' => 'm_' . $item->id . '_empty',
                        'db_id' => $item->id,
                        'group_title' => $groupTitle,
                        'title' => $item->title,
                        'type' => 'text',
                        'content' => 'Materi ini belum memiliki konten.',
                        'completed' => true,
                        'locked' => false,
                    ];
                }
            } 
            else {
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
                    'grade' => null,
                    'completed' => true,
                    'locked' => false,
                    'questions' => clone $item->questions,
                ];
            }
        }

        return view('students.lms.learning-player', [
            'subject' => $subject,
            'syllabusJson' => json_encode($syllabus),
            'isPreview' => true 
        ]);
    }
}