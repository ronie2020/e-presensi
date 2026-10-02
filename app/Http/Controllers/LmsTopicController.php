<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Topic;
use App\Models\Subject;
use App\Models\TeachingLoad;
use App\Models\Timetable;
use App\Models\LmsMaterial;
use App\Models\LmsAssignment;
use App\Traits\TeacherScopeTrait;

class LmsTopicController extends Controller
{
    use TeacherScopeTrait;

    // Menampilkan halaman kelola Bab
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $this->isUserAdmin($user);
        $subjects = $this->getScopedSubjects($user);
        $query = Topic::with('subject');

        if (!$isAdmin) {
            $query->whereIn('subject_id', $subjects->pluck('id'));
        }

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
        $user = Auth::user();
        if (!$this->isUserAdmin($user)) {
            $allowedSubjectIds = $this->getScopedSubjects($user)->pluck('id')->toArray();
            if (!in_array($request->subject_id, $allowedSubjectIds)) {
                return back()->withErrors('Anda tidak memiliki wewenang untuk menambahkan bab pada mata pelajaran ini.');
            }
        }

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

    public function edit($id)
    {
        $topic = \App\Models\Topic::findOrFail($id);
        $subjects = $this->getScopedSubjects();
        if ($topic->subject_id && !$subjects->contains('id', $topic->subject_id)) {
            $currSub = Subject::find($topic->subject_id);
            if ($currSub) $subjects->push($currSub);
        }
        return view('lms.topics.edit', compact('topic', 'subjects'));
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'order_number' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $topic = \App\Models\Topic::findOrFail($id);
        $topic->update($request->all());

        return redirect()->route('lms.topics.index')->with('success', 'Bab berhasil diperbarui!');
    }
    
}