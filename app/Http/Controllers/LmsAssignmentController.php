<?php

namespace App\Http\Controllers;

use App\Models\LmsAssignment;
use App\Models\LmsQuizQuestion;
use App\Models\LmsSubmission;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LmsAssignmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. LOGIKA GROUPING (TAMPILKAN 1 KARTU PER GRUP TUGAS)
        // Kita ambil ID pertama dari setiap grup yang memiliki Judul, Mapel, Deadline, dan Waktu Buat yang sama.
        $subQuery = LmsAssignment::selectRaw('MIN(id) as id')
            ->groupBy('title', 'subject_id', 'deadline', 'created_at');

        if ($user->role !== 'admin') {
            $subQuery->where('teacher_id', $user->id);
        }

        // 2. Ambil Data Berdasarkan ID hasil grouping
        $assignments = LmsAssignment::whereIn('id', $subQuery)
            ->with(['subject', 'schoolClass'])
            ->withCount('submissions')
            ->latest()
            ->paginate(10);

        // 3. Tambahkan info tambahan ke setiap item untuk tampilan di View
        foreach ($assignments as $assignment) {
            // Cari "saudara" (tugas yang sama di kelas lain)
            $siblingsQuery = LmsAssignment::where('title', $assignment->title)
                ->where('created_at', $assignment->created_at) // Kunci grouping
                ->where('deadline', $assignment->deadline);
                
            if ($user->role !== 'admin') {
                $siblingsQuery->where('teacher_id', $user->id);
            }

            $siblingsCount = $siblingsQuery->count();
            
            // Hitung total pengumpulan global (opsional, agar guru tau total dari semua kelas)
            $allIds = $siblingsQuery->pluck('id');
            $globalSubmissions = LmsSubmission::whereIn('assignment_id', $allIds)->count();

            // Inject atribut custom ke model instance
            $assignment->is_bulk = $siblingsCount > 1;     // Apakah ini tugas massal?
            $assignment->total_classes = $siblingsCount;   // Berapa kelas?
            $assignment->global_submissions_count = $globalSubmissions;
        }

        $subjects = Subject::all();
        $classes = SchoolClass::orderBy('name', 'asc')->get();

        return view('lms.assignments.index', compact('assignments', 'subjects', 'classes'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        return view('lms.assignments.create', compact('subjects', 'classes'));
    }

    public function store(Request $request)
    {
        // 1. Tentukan Deskripsi sesuai tipe
        $description = null;
        if ($request->assignment_type == 'file_upload') {
            $description = $request->description_file;
        } elseif ($request->assignment_type == 'quiz') {
            $description = $request->description_quiz;
        } elseif ($request->assignment_type == 'link') {
            $description = $request->description_link;
        }

        $request->merge(['description' => $description]);

        // 2. Validasi
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:' . Subject::class . ',id',
            'deadline' => 'required',
            'description' => 'required|string',
            'assignment_type' => 'required|in:file_upload,quiz,link',
            'target_type' => 'required|in:grade,class',
            'class_id' => 'required_if:target_type,class|nullable|exists:' . SchoolClass::class . ',id',
            'link_url' => 'nullable|required_if:assignment_type,link|url',
            'duration_minutes' => 'nullable|required_if:assignment_type,quiz|integer|min:1',
            'questions' => 'nullable|required_if:assignment_type,quiz|array',
        ]);

        $teacherId = Auth::id();
        
        // [PENTING] Timestamp harus sama persis untuk semua kelas agar terdeteksi satu grup
        $now = now(); 

        try {
            $deadline = Carbon::parse($request->deadline)->format('Y-m-d H:i:s');

            DB::transaction(function () use ($request, $teacherId, $description, $deadline, $now) {
                
                // 3. Cari Kelas Target
                $targetClassIds = [];
                if ($request->target_type == 'class') {
                    $targetClassIds[] = $request->class_id;
                } elseif ($request->target_type == 'grade') {
                    $classes = SchoolClass::where('name', 'like', $request->target_grade . '%')->get();
                    foreach ($classes as $c) {
                        $targetClassIds[] = $c->id;
                    }
                }

                if (empty($targetClassIds)) {
                    throw new \Exception("Tidak ditemukan kelas untuk jenjang " . $request->target_grade);
                }

                // 4. Simpan Tugas ke Setiap Kelas
                foreach ($targetClassIds as $classId) {
                    $assignment = LmsAssignment::create([
                        'teacher_id' => $teacherId,
                        'subject_id' => $request->subject_id,
                        'class_id' => $classId,
                        'title' => $request->title,
                        'description' => $description,
                        'deadline' => $deadline,
                        'assignment_type' => $request->assignment_type,
                        'link_url' => $request->assignment_type == 'link' ? $request->link_url : null,
                        'duration_minutes' => $request->assignment_type == 'quiz' ? $request->duration_minutes : null,
                        'allow_late_submission' => $request->has('allow_late_submission'),
                        'created_at' => $now, // Pakai waktu yang sama
                        'updated_at' => $now,
                    ]);

                    // 5. Simpan Soal Kuis (Jika ada)
                    if ($request->assignment_type == 'quiz' && $request->has('questions')) {
                        foreach ($request->questions as $q) {
                            LmsQuizQuestion::create([
                                'assignment_id' => $assignment->id,
                                'question_text' => $q['text'] ?? '',
                                'question_type' => $q['type'] ?? 'multiple_choice',
                                'options' => $q['options'] ?? null, 
                                'correct_answer' => $q['correct'] ?? null,
                                'points' => $q['points'] ?? 10,
                            ]);
                        }
                    }
                }
            });

            return redirect()->route('lms.assignments.index')->with('success', 'Tugas berhasil diterbitkan!');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    // --- METHOD EDIT (DIPERBARUI) ---
    public function edit($id)
    {
        $assignment = LmsAssignment::findOrFail($id);
        
        if (Auth::user()->role !== 'admin' && $assignment->teacher_id !== Auth::id()) {
            abort(403);
        }

        // Cek apakah ini tugas massal?
        $siblingsCount = LmsAssignment::where('teacher_id', $assignment->teacher_id)
            ->where('title', $assignment->title)
            ->where('created_at', $assignment->created_at) // Kunci grouping
            ->count();

        $isBulk = $siblingsCount > 1;

        $subjects = Subject::all();
        $classes = SchoolClass::orderBy('name', 'asc')->get();

        // Pastikan Anda membuat view 'lms.assignments.edit' juga
        return view('lms.assignments.edit', compact('assignment', 'subjects', 'classes', 'isBulk'));
    }

    // --- METHOD UPDATE (DIPERBARUI - BULK UPDATE) ---
    public function update(Request $request, $id)
    {
        $assignment = LmsAssignment::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $assignment->teacher_id !== Auth::id()) {
            abort(403);
        }

        // Validasi dasar (sesuaikan field yg bisa diedit)
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'deadline' => 'required',
            'description' => 'required|string', // Pastikan input name di form edit konsisten (misal 'description' saja, bukan dipisah per tipe)
            'link_url' => 'nullable|url',
        ]);

        try {
            $deadline = Carbon::parse($request->deadline)->format('Y-m-d H:i:s');

            DB::transaction(function () use ($request, $assignment, $deadline) {
                // 1. Cari Saudara (Grup Tugas yang Sama)
                // Kita cari berdasarkan data LAMA (sebelum diupdate)
                $siblings = LmsAssignment::where('teacher_id', $assignment->teacher_id)
                    ->where('title', $assignment->title)
                    ->where('created_at', $assignment->created_at)
                    ->get();

                if ($siblings->isEmpty()) $siblings = collect([$assignment]);

                // 2. Update Semua Saudara
                foreach ($siblings as $target) {
                    $target->update([
                        'title' => $request->title,
                        'subject_id' => $request->subject_id,
                        'deadline' => $deadline,
                        'description' => $request->description,
                        'link_url' => $request->link_url, 
                        'duration_minutes' => $request->duration_minutes,
                        'allow_late_submission' => $request->has('allow_late_submission'),
                    ]);
                    
                    // Catatan: Update soal kuis untuk bulk agak kompleks, 
                    // untuk sekarang kita asumsikan edit hanya mengubah info dasar.
                    // Jika ingin update soal massal, perlu logika hapus-tambah soal di semua siblings.
                }
            });

            return redirect()->route('lms.assignments.index')->with('success', 'Tugas berhasil diperbarui untuk semua kelas terkait.');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal update: ' . $e->getMessage()]);
        }
    }

    // --- METHOD DESTROY (DIPERBARUI - BULK DELETE) ---
    public function destroy($id)
    {
        $user = Auth::user();
        $assignment = LmsAssignment::findOrFail($id);
        
        if ($user->role !== 'admin' && $assignment->teacher_id !== $user->id) abort(403);
        
        // Hapus Massal (Group)
        $siblings = LmsAssignment::where('teacher_id', $assignment->teacher_id)
            ->where('title', $assignment->title)
            ->where('created_at', $assignment->created_at)
            ->get();

        foreach ($siblings as $target) {
            // Hapus submissions/soal terkait otomatis via cascade database 
            // atau tambahkan logika hapus manual jika perlu
            $target->delete();
        }

        return redirect()->route('lms.assignments.index')->with('success', 'Tugas dihapus dari semua kelas.');
    }

    public function submissions(LmsAssignment $assignment)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $assignment->teacher_id !== $user->id) abort(403);

        // Ambil data pengumpulan
        $submissions = LmsSubmission::with('student')->where('assignment_id', $assignment->id)->get();
        
        // Daftar semua siswa di kelas target untuk ditampilkan di tabel
        $allStudents = Student::where('class_id', $assignment->class_id)
                        ->orderBy('name')
                        ->get();

        return view('lms.assignments.submissions', compact('assignment', 'submissions', 'allStudents'));
    }

    public function grade(Request $request, LmsSubmission $submission)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $submission->assignment->teacher_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'grade' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:255'
        ]);

        $submission->update([
            'grade' => $request->grade,
            'teacher_feedback' => $request->feedback
        ]);

        return back()->with('success', 'Nilai berhasil disimpan.');
    }
}