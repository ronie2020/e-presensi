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
        // Kita ambil ID pertama dari setiap grup materi yang memiliki Judul, Mapel, dan Waktu Buat yang sama.
        $subQuery = LmsMaterial::selectRaw('MIN(id) as id')
            ->where('teacher_id', $user->id)
            ->groupBy('title', 'subject_id', 'created_at'); // Grouping key

        // 2. Ambil Data Berdasarkan ID hasil grouping
        $materials = LmsMaterial::whereIn('id', $subQuery)
            ->with(['subject', 'schoolClass', 'attachments'])
            ->latest()
            ->paginate(10);

        // 3. Tambahkan info tambahan ke setiap item untuk tampilan
        foreach ($materials as $material) {
            // Hitung ada berapa kelas yang menerima materi ini
            $siblingsCount = LmsMaterial::where('teacher_id', $user->id)
                ->where('title', $material->title)
                ->where('created_at', $material->created_at)
                ->count();
            
            // Simpan info ini sementara ke objek material untuk dipakai di View
            $material->is_bulk = $siblingsCount > 1;
            $material->total_classes = $siblingsCount;
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
        
        // TIMESTAMP SAMA PERSIS UNTUK GROUPING
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

            foreach ($targetClassIds as $classId) {
                $material = LmsMaterial::create([
                    'teacher_id' => $teacherId,
                    'subject_id' => $request->subject_id,
                    'class_id' => $classId,
                    'title' => $request->title,
                    'resume' => $request->resume,           
                    'type' => 'document',
                    'created_at' => $now, // PENTING: Waktu harus sama persis agar terdeteksi satu grup
                    'updated_at' => $now,
                ]);

                if ($request->has('attachments')) {
                    $this->storeAttachments($material->id, $request->attachments);
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

        // Cek apakah ini materi massal (untuk jenjang)
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
            // Jika bulk update, class_id bisa diabaikan atau tetap divalidasi tapi tidak diupdate
            'class_id' => 'required|exists:classes,id', 
            'resume' => 'nullable|string',
            'new_attachments' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $material) {
            
            // 1. CARI SAUDARA (BULK UPDATE)
            // Cari semua materi yang punya judul, mapel, dan waktu buat yang sama (Group yang sama)
            // PENTING: Kita cari berdasarkan data LAMA sebelum diupdate
            $siblings = LmsMaterial::where('teacher_id', $material->teacher_id)
                ->where('title', $material->title) // Judul lama
                ->where('created_at', $material->created_at) // Waktu buat sama
                ->get();

            if ($siblings->isEmpty()) {
                $siblings = collect([$material]); // Fallback jika tidak ada saudara
            }

            // 2. LOOP UPDATE UNTUK SEMUA KELAS TERKAIT
            foreach ($siblings as $targetMaterial) {
                
                // Update Data Dasar
                $targetMaterial->update([
                    'title' => $request->title,
                    'subject_id' => $request->subject_id,
                    'resume' => $request->resume,
                    // Kita TIDAK mengupdate class_id agar materi tetap berada di kelas masing-masing
                    // Kecuali user secara eksplisit mengubah target kelas (fitur advance, skip dulu biar aman)
                ]);

                // Hapus Attachment Terpilih
                if ($request->has('delete_attachments')) {
                    foreach ($request->delete_attachments as $attId) {
                        // Cek apakah attachment ini milik salah satu material dalam grup (agak tricky)
                        // Simplifikasi: Hapus berdasarkan nama file yang sama di materi terkait?
                        // Karena attachment punya ID unik per materi, kita hapus by ID yang dikirim form (milik materi utama yg diedit)
                        // TAPI: Saudara-saudaranya punya attachment dengan ID beda tapi file sama.
                        // SOLUSI ROBUS: Hapus attachment di semua saudara yang punya file_path sama.
                        
                        $attToDelete = LmsMaterialAttachment::find($attId);
                        if ($attToDelete) {
                            // Cari attachment serupa di saudara-saudaranya
                            $relatedAttachments = LmsMaterialAttachment::whereIn('material_id', $siblings->pluck('id'))
                                ->where('file_path', $attToDelete->file_path)
                                ->get();

                            foreach($relatedAttachments as $relAtt) {
                                // Hapus fisik file (sekali saja jika path sama)
                                if ($relAtt->file_type == 'file' && Storage::disk('public')->exists($relAtt->file_path)) {
                                    // Cek apakah masih dipakai materi lain di luar grup ini? (Asumsi tidak, hapus saja)
                                    // Agar aman, jangan hapus fisik file jika ragu, hapus record DB saja.
                                    // Storage::disk('public')->delete($relAtt->file_path); 
                                }
                                $relAtt->delete();
                            }
                        }
                    }
                }

                // Tambah Attachment Baru (Copy ke semua saudara)
                if ($request->has('new_attachments')) {
                    // Kita harus hati-hati agar file tidak di-upload berulang kali
                    // Helper storeAttachments di bawah perlu disesuaikan
                }
            }

            // HANDLING NEW ATTACHMENTS (SPECIAL CASE FOR BULK)
            // Upload sekali, lalu link-kan ke semua saudara
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

                    // Buat Record DB untuk SETIAP materi dalam grup
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

        return redirect()->route('lms.materials.index')->with('success', 'Materi (dan seluruh salinannya di kelas lain) berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $material = LmsMaterial::findOrFail($id);

        if ($user->role !== 'admin' && $material->teacher_id !== $user->id) abort(403);
        
        // Cari saudara-saudaranya (Batch Delete)
        $siblings = LmsMaterial::where('teacher_id', $material->teacher_id)
            ->where('title', $material->title)
            ->where('created_at', $material->created_at)
            ->get();

        foreach ($siblings as $target) {
            foreach($target->attachments as $att) {
                if($att->file_type == 'file') {
                    Storage::disk('public')->delete($att->file_path);
                }
            }
            $target->delete(); 
        }

        return redirect()->route('lms.materials.index')->with('success', 'Materi berhasil dihapus dari semua kelas terkait.');
    }

    // Helper (Dipakai di Store saja)
    private function storeAttachments($materialId, $attachments)
    {
        foreach ($attachments as $item) {
            $path = null;
            $name = $item['name'] ?? 'Lampiran';
            $type = $item['type'];

            if ($type == 'file' && isset($item['file'])) {
                $file = $item['file'];
                $path = $file->store('lms-materials', 'public');
                $name = $file->getClientOriginalName();
            } elseif (($type == 'link' || $type == 'video') && isset($item['link'])) {
                $path = $item['link'];
            }

            if ($path) {
                LmsMaterialAttachment::create([
                    'material_id' => $materialId,
                    'file_path' => $path,
                    'file_name' => $name,
                    'file_type' => $type
                ]);
            }
        }
    }
}