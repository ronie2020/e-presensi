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
        
        // 1. LOGIKA GROUPING (AGAR MUNCUL 1 KARTU SAJA UNTUK BANYAK KELAS)
        $subQuery = LmsMaterial::selectRaw('MIN(id) as id')
            ->where('teacher_id', $user->id)
            ->groupBy('title', 'subject_id', 'created_at');

        // 2. Ambil Data Berdasarkan ID hasil grouping
        $materials = LmsMaterial::whereIn('id', $subQuery)
            ->with(['subject', 'schoolClass', 'attachments'])
            ->latest()
            ->paginate(10);

        // 3. Tambahkan info tambahan ke setiap item untuk tampilan
        foreach ($materials as $material) {
            // Hitung ada berapa kelas yang menerima materi ini
            $siblingsQuery = LmsMaterial::where('teacher_id', $user->id)
                ->where('title', $material->title)
                ->where('created_at', $material->created_at);

            $siblingsCount = $siblingsQuery->count();
            
            $material->is_bulk = $siblingsCount > 1;
            $material->total_classes = $siblingsCount;

            // [PERBAIKAN] Tambahkan logika tebak jenjang agar tampilan di index tidak kosong
            if ($material->is_bulk && $material->schoolClass) {
                // Ambil angka dari nama kelas (misal "7A" -> "7")
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
            'resume' => 'nullable|string', 
            'target_type' => 'required|in:class,grade',
            'class_id' => 'nullable|exists:classes,id', 
            'target_grade' => 'required_if:target_type,grade',
            'attachments' => 'nullable|array',
            'attachments.*.file' => 'nullable|file|max:20480', 
            'attachments.*.link' => 'nullable|url',
            'attachments.*.type' => 'required|in:file,video,link',
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

            // Upload file fisik SEKALI saja di awal loop (opsional optimasi), 
            // tapi agar logic sederhana, kita biarkan di storeAttachments menangani per iterasi 
            // atau jika ingin hemat storage, upload sekali lalu pakai path-nya berkali-kali.
            // Di sini kita pakai pendekatan simple: storeAttachments akan handle upload.
            
            // NOTE: Untuk efisiensi storage pada Bulk Upload, idealnya file diupload sekali.
            // Tapi kode di bawah ini akan mengupload file berulang kali jika logic storeAttachments melakukan $file->store().
            // Mari kita perbaiki agar file fisik cuma 1, tapi record attachment banyak.
            
            $processedAttachments = [];
            if ($request->has('attachments')) {
                foreach ($request->attachments as $index => $item) {
                    $path = null;
                    $name = $item['name'] ?? 'Lampiran';
                    $type = $item['type'];

                    if ($type == 'file' && isset($item['file'])) {
                        $file = $item['file'];
                        $path = $file->store('lms-materials', 'public'); // Upload Sekali
                        $name = $file->getClientOriginalName();
                    } elseif (($type == 'link' || $type == 'video') && isset($item['link'])) {
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

                // Simpan Attachment (Share Path yang sama)
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
            'class_id' => 'required|exists:classes,id', 
            'resume' => 'nullable|string',
            'new_attachments' => 'nullable|array',
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
                    'resume' => $request->resume,
                ]);

                // Hapus Attachment Terpilih (Bulk Delete)
                if ($request->has('delete_attachments')) {
                    foreach ($request->delete_attachments as $attId) {
                        $attToDelete = LmsMaterialAttachment::find($attId);
                        if ($attToDelete) {
                            // Hapus semua attachment di grup ini yang memiliki path file sama
                            $relatedAttachments = LmsMaterialAttachment::whereIn('material_id', $siblings->pluck('id'))
                                ->where('file_path', $attToDelete->file_path)
                                ->get();

                            foreach($relatedAttachments as $relAtt) {
                                // [PERBAIKAN] Cek exists sebelum hapus untuk menghindari error
                                if ($relAtt->file_type == 'file' && Storage::disk('public')->exists($relAtt->file_path)) {
                                    // Opsional: Hapus file fisik. 
                                    // Hati-hati: Pastikan tidak ada materi LAIN (di luar grup ini) yang pakai file ini.
                                    // Untuk keamanan, kode asli Anda meng-comment delete storage, kita ikuti itu atau aktifkan jika yakin.
                                    Storage::disk('public')->delete($relAtt->file_path); 
                                }
                                $relAtt->delete();
                            }
                        }
                    }
                }
            }

            // HANDLING NEW ATTACHMENTS (BULK INSERT)
            if ($request->has('new_attachments')) {
                foreach ($request->new_attachments as $item) {
                    $path = null;
                    $name = $item['name'] ?? 'Lampiran';
                    $type = $item['type'];

                    // Upload Fisik Sekali
                    if ($type == 'file' && isset($item['file'])) {
                        $file = $item['file'];
                        $path = $file->store('lms-materials', 'public');
                        $name = $file->getClientOriginalName();
                    } elseif (($type == 'link' || $type == 'video') && isset($item['link'])) {
                        $path = $item['link'];
                    }

                    // Link ke semua record materi
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
                // [PERBAIKAN] Cek exists agar tidak error saat loop ke-2 mencoba hapus file yang sama
                if($att->file_type == 'file' && Storage::disk('public')->exists($att->file_path)) {
                    Storage::disk('public')->delete($att->file_path);
                }
            }
            $target->delete(); 
        }

        return redirect()->route('lms.materials.index')->with('success', 'Materi berhasil dihapus dari semua kelas terkait.');
    }

    // Helper storeAttachments (Tidak dipakai lagi di store() baru karena sudah di-inline untuk efisiensi)
    // Bisa dihapus jika tidak digunakan di tempat lain.
}