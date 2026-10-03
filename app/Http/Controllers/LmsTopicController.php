<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Topic;
use App\Models\Subject;
use App\Models\SchoolClass;
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

        $classes = $this->getScopedClasses($user);
        if ($classes->isEmpty()) {
            $classes = SchoolClass::where('name', '!=', 'admin')->orderBy('name', 'asc')->get();
        }

        $query = Topic::with(['subject', 'schoolClass']);

        if (!$isAdmin) {
            $query->whereIn('subject_id', $subjects->pluck('id'));
        }

        // Filter berdasarkan mapel jika ada
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter berdasarkan tingkat (misal 7, 8, 9) jika ada
        if ($request->filled('grade_level')) {
            $grade = $request->grade_level;
            $query->where(function($q) use ($grade) {
                $q->where('grade_level', $grade)
                  ->orWhereHas('schoolClass', function($sq) use ($grade) {
                      $sq->where('name', 'like', $grade . '%');
                  });
            });
        }

        // Filter berdasarkan kelas spesifik jika ada
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Urutkan berdasarkan mapel, lalu berdasarkan tingkat/kelas, lalu berdasarkan urutan Bab
        $topics = $query->orderBy('subject_id', 'asc')
                        ->orderByRaw('grade_level IS NULL, grade_level ASC')
                        ->orderBy('order_number', 'asc')
                        ->paginate(20)
                        ->withQueryString();

        $gradeLevels = ['7', '8', '9'];

        return view('lms.topics.index', compact('topics', 'subjects', 'classes', 'gradeLevels'));
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
            'grade_level' => 'nullable|string|max:10',
            'class_id' => 'nullable|exists:classes,id',
            'title' => 'required|string|max:255',
            'order_number' => 'required|integer|min:1',
            'description' => 'nullable|string'
        ], [
            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'title.required' => 'Judul bab tidak boleh kosong.',
            'order_number.required' => 'Nomor urutan bab tidak boleh kosong.',
        ]);

        // Jika kelas spesifik dipilih, sinkronkan grade_level otomatis jika belum terisi
        if (!empty($validated['class_id'])) {
            $class = SchoolClass::find($validated['class_id']);
            if ($class && preg_match('/\d+/', $class->name, $matches)) {
                $validated['grade_level'] = $matches[0];
            }
        }

        Topic::create($validated);

        return back()->with('success', 'Pokok Bahasan / Bab berhasil ditambahkan!');
    }

    // Menghapus Bab
    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        $user = Auth::user();
        if (!$this->isUserAdmin($user)) {
            $allowedSubjectIds = $this->getScopedSubjects($user)->pluck('id')->toArray();
            if (!in_array($topic->subject_id, $allowedSubjectIds)) {
                abort(403, 'Anda tidak memiliki wewenang untuk menghapus pokok bahasan ini.');
            }
        }
        
        // Pastikan tidak ada materi/tugas yang masih terkait sebelum dihapus (Opsional, karena di migration kita set nullOnDelete)
        if ($topic->materials()->count() > 0 || $topic->assignments()->count() > 0) {
            return back()->withErrors('Tidak bisa menghapus Bab ini karena masih ada Materi atau Tugas di dalamnya.');
        }

        $topic->delete();

        return back()->with('success', 'Bab berhasil dihapus!');
    }

    public function edit($id)
    {
        $topic = Topic::findOrFail($id);
        $user = Auth::user();
        if (!$this->isUserAdmin($user)) {
            $allowedSubjectIds = $this->getScopedSubjects($user)->pluck('id')->toArray();
            if (!in_array($topic->subject_id, $allowedSubjectIds)) {
                abort(403, 'Anda tidak memiliki wewenang untuk mengedit pokok bahasan ini.');
            }
        }

        $subjects = $this->getScopedSubjects();
        if ($topic->subject_id && !$subjects->contains('id', $topic->subject_id)) {
            $currSub = Subject::find($topic->subject_id);
            if ($currSub) $subjects->push($currSub);
        }

        $classes = $this->getScopedClasses($user);
        if ($classes->isEmpty()) {
            $classes = SchoolClass::where('name', '!=', 'admin')->orderBy('name', 'asc')->get();
        }

        $gradeLevels = ['7', '8', '9'];

        return view('lms.topics.edit', compact('topic', 'subjects', 'classes', 'gradeLevels'));
    }

    public function update(Request $request, $id)
    {
        $topic = Topic::findOrFail($id);
        $user = Auth::user();
        if (!$this->isUserAdmin($user)) {
            $allowedSubjectIds = $this->getScopedSubjects($user)->pluck('id')->toArray();
            if (!in_array($topic->subject_id, $allowedSubjectIds) || !in_array($request->subject_id, $allowedSubjectIds)) {
                abort(403, 'Anda tidak memiliki wewenang untuk mengubah pokok bahasan ini.');
            }
        }

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grade_level' => 'nullable|string|max:10',
            'class_id' => 'nullable|exists:classes,id',
            'title' => 'required|string|max:255',
            'order_number' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        if (!empty($validated['class_id'])) {
            $class = SchoolClass::find($validated['class_id']);
            if ($class && preg_match('/\d+/', $class->name, $matches)) {
                $validated['grade_level'] = $matches[0];
            }
        } else {
            $validated['class_id'] = null;
        }

        $topic->update($validated);

        return redirect()->route('lms.topics.index')->with('success', 'Bab berhasil diperbarui!');
    }
    
}