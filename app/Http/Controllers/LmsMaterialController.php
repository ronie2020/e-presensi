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
    /**
     * Menampilkan daftar materi dengan grouping (agar tidak duplikat per kelas)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // 1. LOGIKA GROUPING: Ambil 1 ID per judul+mapel+waktu (untuk representasi di UI)
        $subQuery = LmsMaterial::selectRaw('MIN(id) as id')
            ->where('teacher_id', $user->id)
            ->groupBy('title', 'subject_id', 'created_at');

         // --- LOGIKA SEARCH & FILTER ---
        if ($request->filled('search')) {
            $subQuery->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('subject')) {
            $subQuery->where('subject_id', $request->subject);
        }        

        $subQuery->groupBy('title', 'subject_id', 'created_at');

       // 2. Ambil Data Lengkap berdasarkan ID tersebut
        $materials = LmsMaterial::whereIn('id', $subQuery)
            ->with(['subject', 'schoolClass', 'attachments']) // Load attachments
            ->latest()
            ->paginate(10)
            ->withQueryString(); // PENTING: Agar filter tidak hilang saat pindah halaman


        // 3. Inject Info Tambahan (Bulk Info)
        foreach ($materials as $material) {
            // Hitung ada berapa kelas yang menerima materi ini (siblings)
            $siblingsQuery = LmsMaterial::where('teacher_id', $user->id)
                ->where('title', $material->title)
                ->where('created_at', $material->created_at);

            $siblingsCount = $siblingsQuery->count();
            
            $material->is_bulk = $siblingsCount > 1;
            $material->total_classes = $siblingsCount;

            // Tebak jenjang kelas (misal "7A" -> "7")
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

    /**
     * Menyimpan materi baru (Bisa Bulk ke banyak kelas sekaligus)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'resume' => 'nullable|string', 
            'target_type' => 'required|in:class,grade',
            'class_id' => 'nullable|exists:classes,id', 
            'target_grade' => 'required_if:target_type,grade',
            // Validasi Attachments
            'attachments' => 'nullable|array',
            'attachments.*.file' => 'nullable|file|max:20480', // Max 20MB
            'attachments.*.link' => 'nullable|url',
            'attachments.*.type' => 'required|in:file,video,link',
        ]);

        $teacherId = Auth::id();
        $now = now(); 

        DB::transaction(function () use ($request, $teacherId, $now) {
            // 1. Tentukan Target Kelas
            $targetClassIds = [];
            
            if ($request->target_type == 'class') {
                if ($request->class_id) $targetClassIds[] = $request->class_id;
            } elseif ($request->target_type == 'grade') {
                // Ambil semua kelas yang namanya mengandung angka jenjang (misal "7")
                $classes = SchoolClass::where('name', 'like', $request->target_grade . '%')->get();
                foreach ($classes as $c) $targetClassIds[] = $c->id;
            }

            if (empty($targetClassIds)) return; 

            // 2. Proses Upload File (Dilakukan sekali, path dipakai berulang)
            $processedAttachments = [];
            if ($request->has('attachments')) {
                foreach ($request->attachments as $index => $item) {
                    $path = null;
                    $name = $item['name'] ?? 'Lampiran';
                    $type = $item['type'];

                    if ($type == 'file' && isset($item['file'])) {
                        $file = $item['file'];
                        // Simpan ke storage public agar bisa diakses via asset()
                        $path = $file->store('lms-materials', 'public');
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

            // 3. Loop Create Material & Attachments untuk setiap kelas
            foreach ($targetClassIds as $classId) {
                $material = LmsMaterial::create([
                    'teacher_id' => $teacherId,
                    'subject_id' => $request->subject_id,
                    'class_id' => $classId,
                    'title' => $request->title,
                    'resume' => $request->resume,           
                    'type' => 'document', // Default legacy type
                    'created_at' => $now, 
                    'updated_at' => $now,
                ]);

                // Hubungkan Attachment
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

        // Cek apakah materi ini bagian dari bulk upload
        $siblingsCount = LmsMaterial::where('teacher_id', $material->teacher_id)
            ->where('title', $material->title)
            ->where('created_at', $material->created_at)
            ->count();

        $isBulk = $siblingsCount > 1;

        $subjects = Subject::all();
        $classes = SchoolClass::orderBy('name', 'asc')->get();

        return view('lms.materials.edit', compact('material', 'subjects', 'classes', 'isBulk'));
    }

    /**
     * Update Materi (Termasuk update bulk siblings & attachments)
     */
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
        ]);

        DB::transaction(function () use ($request, $material) {
            
            // Cari semua materi kembaran (siblings) untuk diupdate sekaligus
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

                // A. Hapus Attachment yang dipilih
                if ($request->has('delete_attachments')) {
                    foreach ($request->delete_attachments as $attId) {
                        $attToDelete = LmsMaterialAttachment::find($attId);
                        if ($attToDelete) {
                            // Cari attachment serupa di sibling lain agar terhapus juga
                            $relatedAttachments = LmsMaterialAttachment::whereIn('material_id', $siblings->pluck('id'))
                                ->where('file_path', $attToDelete->file_path)
                                ->get();

                            foreach($relatedAttachments as $relAtt) {
                                // Hapus file fisik jika tipe file & ada di storage
                                if ($relAtt->file_type == 'file' && Storage::disk('public')->exists($relAtt->file_path)) {
                                    Storage::disk('public')->delete($relAtt->file_path); 
                                }
                                $relAtt->delete();
                            }
                        }
                    }
                }
            }

            // B. Tambah Attachment Baru (ke semua siblings)
            if ($request->has('new_attachments')) {
                foreach ($request->new_attachments as $item) {
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
        
        // Hapus semua kembaran (siblings)
        $siblings = LmsMaterial::where('teacher_id', $material->teacher_id)
            ->where('title', $material->title)
            ->where('created_at', $material->created_at)
            ->get();

        foreach ($siblings as $target) {
            // Hapus file fisik attachment
            foreach($target->attachments as $att) {
                if($att->file_type == 'file' && Storage::disk('public')->exists($att->file_path)) {
                    Storage::disk('public')->delete($att->file_path);
                }
            }
            $target->delete(); 
        }

        return redirect()->route('lms.materials.index')->with('success', 'Materi berhasil dihapus.');
    }

    /**
     * Download Helper (Opsional, jika tombol download spesifik dibutuhkan)
     */
    public function download($id)
    {
        $material = LmsMaterial::with('attachments')->findOrFail($id);
        $attachment = $material->attachments->where('file_type', 'file')->first();

        if ($attachment && Storage::disk('public')->exists($attachment->file_path)) {
            return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
        }

        return back()->with('error', 'File tidak ditemukan.');
    }
}