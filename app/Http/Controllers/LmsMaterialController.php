<?php

namespace App\Http\Controllers;

use App\Models\LmsMaterial;
use App\Models\LmsMaterialAttachment; // Import Model Baru
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LmsMaterialController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = LmsMaterial::with(['subject', 'schoolClass', 'attachments']); // Eager load attachments

        if ($user->role !== 'admin') {
            $query->where('teacher_id', $user->id);
        }

        $materials = $query->latest()->paginate(10);
        return view('lms.materials.index', compact('materials'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        return view('lms.materials.create', compact('subjects', 'classes'));
    }

    // ===> FUNGSI STORE YANG DIROMBAK TOTAL <===
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'resume' => 'nullable|string', // Validasi Resume
            'target_type' => 'required|in:all,grade,class',
            // Validasi Array Attachments
            'attachments' => 'nullable|array',
            'attachments.*.file' => 'nullable|file|max:20480', // Max 20MB
            'attachments.*.link' => 'nullable|url',
            'attachments.*.type' => 'required|in:file,video,link',
        ]);

        $teacherId = Auth::id();

        DB::transaction(function () use ($request, $teacherId) {
            
            // 1. Tentukan Kelas Target (Bisa banyak ID jika per jenjang)
            $targetClassIds = [];
            
            if ($request->target_type == 'all') {
                $targetClassIds[] = null; // null = semua kelas
            } elseif ($request->target_type == 'class') {
                $targetClassIds[] = $request->class_id;
            } elseif ($request->target_type == 'grade') {
                $classes = SchoolClass::where('name', 'like', $request->target_grade . '%')->get();
                foreach ($classes as $c) $targetClassIds[] = $c->id;
            }

            // 2. Loop Setiap Kelas Target -> Buat Materi
            foreach ($targetClassIds as $classId) {
                
                // A. Buat Header Materi
                $material = LmsMaterial::create([
                    'teacher_id' => $teacherId,
                    'subject_id' => $request->subject_id,
                    'class_id' => $classId,
                    'title' => $request->title,
                    'description' => $request->description, // Deskripsi singkat
                    'resume' => $request->resume,           // Resume Lengkap (Materi Teks)
                    'type' => 'document', // Default type (bisa diabaikan now)
                ]);

                // B. Simpan Attachments (Multiple)
                if ($request->has('attachments')) {
                    foreach ($request->attachments as $item) {
                        
                        $path = null;
                        $name = $item['name'] ?? 'Lampiran';

                        // Jika Tipe File (Upload)
                        if ($item['type'] == 'file' && isset($item['file'])) {
                            $file = $item['file'];
                            $path = $file->store('lms-materials', 'public');
                            $name = $file->getClientOriginalName();
                        } 
                        // Jika Tipe Link/Video (URL)
                        elseif (($item['type'] == 'link' || $item['type'] == 'video') && isset($item['link'])) {
                            $path = $item['link'];
                        }

                        if ($path) {
                            LmsMaterialAttachment::create([
                                'material_id' => $material->id,
                                'file_path' => $path,
                                'file_name' => $name,
                                'file_type' => $item['type']
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->route('lms.materials.index')->with('success', 'Materi berhasil diterbitkan!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $material = LmsMaterial::findOrFail($id);

        if ($user->role !== 'admin' && $material->teacher_id !== $user->id) abort(403);
        
        // Hapus fisik file attachments
        foreach($material->attachments as $att) {
            if($att->file_type == 'file') {
                Storage::disk('public')->delete($att->file_path);
            }
        }

        $material->delete(); // Cascade delete akan menghapus record attachments di DB

        return redirect()->route('lms.materials.index')->with('success', 'Materi dihapus.');
    }
}