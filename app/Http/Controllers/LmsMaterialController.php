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
    public function index()
    {
        $user = Auth::user();
        $query = LmsMaterial::with(['subject', 'schoolClass', 'attachments']); 

        if ($user->role !== 'admin') {
            $query->where('teacher_id', $user->id);
        }

        $materials = $query->latest()->paginate(10);

        // Data pendukung untuk filter view (mencegah error undefined variable)
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
        // 1. Validasi Input
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'resume' => 'nullable|string', 
            'target_type' => 'required|in:class,grade', // Hapus 'all' jika tidak dipakai, atau biarkan jika ada opsi 'semua'
            
            // Validasi Bersyarat (PENTING AGAR TIDAK ERROR NULL)
            'class_id' => 'required_if:target_type,class', 
            'target_grade' => 'required_if:target_type,grade',

            // Validasi Attachments
            'attachments' => 'nullable|array',
            'attachments.*.file' => 'nullable|file|max:20480', // Max 20MB
            'attachments.*.link' => 'nullable|url',
            'attachments.*.type' => 'required|in:file,video,link',
        ]);

        $teacherId = Auth::id();

        DB::transaction(function () use ($request, $teacherId) {
            
            // 2. Tentukan Array Kelas Target
            $targetClassIds = [];
            
            if ($request->target_type == 'class') {
                // Pastikan class_id ada sebelum dimasukkan
                if ($request->class_id) {
                    $targetClassIds[] = $request->class_id;
                }
            } elseif ($request->target_type == 'grade') {
                // Cari semua kelas berdasarkan jenjang (misal "7", "8", "9")
                $classes = SchoolClass::where('name', 'like', $request->target_grade . '%')->get();
                foreach ($classes as $c) $targetClassIds[] = $c->id;
            }

            // Jika tidak ada kelas target (misal salah input), lempar error/abaikan
            if (empty($targetClassIds)) {
                // Opsional: throw validation exception atau biarkan (tapi materi tidak akan terbuat)
                return; 
            }

            // 3. Loop Simpan Materi ke Setiap Kelas
            foreach ($targetClassIds as $classId) {
                
                // A. Header Materi
                $material = LmsMaterial::create([
                    'teacher_id' => $teacherId,
                    'subject_id' => $request->subject_id,
                    'class_id' => $classId,
                    'title' => $request->title,
                    'resume' => $request->resume,           
                    'type' => 'document', 
                ]);

                // B. Attachments
                if ($request->has('attachments')) {
                    foreach ($request->attachments as $item) {
                        
                        $path = null;
                        $name = $item['name'] ?? 'Lampiran';
                        $type = $item['type'];

                        // Upload File
                        if ($type == 'file' && isset($item['file'])) {
                            $file = $item['file'];
                            // Simpan file fisik
                            $path = $file->store('lms-materials', 'public');
                            $name = $file->getClientOriginalName();
                        } 
                        // Simpan Link/Video
                        elseif (($type == 'link' || $type == 'video') && isset($item['link'])) {
                            $path = $item['link'];
                        }

                        // Create Record Attachment jika path ada
                        if ($path) {
                            LmsMaterialAttachment::create([
                                'material_id' => $material->id,
                                'file_path' => $path,
                                'file_name' => $name,
                                'file_type' => $type
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
        
        foreach($material->attachments as $att) {
            if($att->file_type == 'file') {
                Storage::disk('public')->delete($att->file_path);
            }
        }

        $material->delete(); 
        return redirect()->route('lms.materials.index')->with('success', 'Materi dihapus.');
    }
}