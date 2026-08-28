<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Subject;

class LmsTopicController extends Controller
{
    // Menampilkan halaman kelola Bab
    public function index(Request $request)
    {
        $subjects = Subject::orderBy('name', 'asc')->get();
        
        $query = Topic::with('subject');
        
        // Filter berdasarkan mapel jika ada
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Urutkan berdasarkan mapel, lalu berdasarkan urutan Bab
        $topics = $query->orderBy('subject_id', 'asc')
                        ->orderBy('order_number', 'asc')
                        ->paginate(20)
                        ->withQueryString();

        return view('lms.topics.index', compact('topics', 'subjects'));
    }

    // Menyimpan Bab Baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'order_number' => 'required|integer|min:1',
            'description' => 'nullable|string'
        ], [
            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'title.required' => 'Judul bab tidak boleh kosong.',
        ]);

        Topic::create($validated);

        return back()->with('success', 'Pokok Bahasan / Bab berhasil ditambahkan!');
    }

    // Menghapus Bab
    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        
        // Pastikan tidak ada materi/tugas yang masih terkait sebelum dihapus (Opsional, karena di migration kita set nullOnDelete)
        if ($topic->materials()->count() > 0 || $topic->assignments()->count() > 0) {
            return back()->withErrors('Tidak bisa menghapus Bab ini karena masih ada Materi atau Tugas di dalamnya.');
        }

        $topic->delete();

        return back()->with('success', 'Bab berhasil dihapus!');
    }
}