<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CbtExam;
use App\Models\CbtQuestion;
use App\Models\Student;
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionsImport;
use App\Exports\CbtScoreExport; 
use App\Exports\QuestionTemplateExport; // <-- TAMBAHAN: Import class Export Template
use App\Models\LmsAssignment;
use App\Models\LmsGrade;       
use App\Models\LmsSubmission;  
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Auth; 

class CbtController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'active_exams' => CbtExam::where('is_active', true)->count(),
            'total_questions' => DB::table('cbt_questions')->count(),
            'students_working' => DB::table('cbt_student_exams')->where('status', 'ongoing')->count(),
            'avg_score' => DB::table('cbt_student_exams')->whereNotNull('total_score')->avg('total_score') ?? 0,
        ];

         // 1. PENCARIAN & FILTER SERVER-SIDE
        $query = CbtExam::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subject_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter') && $request->filter != 'all') {
            $query->where('is_active', $request->filter == 'active');
        }

        // withQueryString() memastikan saat pindah halaman (pagination), search & filter tidak hilang
        $exams = $query->latest()->paginate(12)->withQueryString();

        return view('cbt.index', compact('stats', 'exams'));
    }

     // --- 3. FITUR QUICK TOGGLE STATUS ---
    public function toggleStatus(Request $request, $id)
    {
        $exam = CbtExam::findOrFail($id);
        $exam->update([
            'is_active' => !$exam->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $exam->is_active,
            'message' => $exam->is_active ? 'Ujian diaktifkan!' : 'Ujian dinonaktifkan!'
        ]);
    }

    // --- 5. FITUR DUPLIKASI UJIAN ---
    public function cloneExam($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Duplikasi data ujian utama
            $newExam = $exam->replicate();
            $newExam->title = $exam->title . ' (Salinan)';
            $newExam->token = strtoupper(Str::random(5)); // Token baru
            $newExam->is_active = false; // Matikan default agar aman
            $newExam->save();

            // Duplikasi setiap soal yang ada di ujian tersebut
            foreach ($exam->questions as $q) {
                $newQ = $q->replicate();
                $newQ->cbt_exam_id = $newExam->id;

                // Salin file gambar utama soal jika ada
                if ($q->question_image && Storage::exists('public/' . $q->question_image)) {
                    $newPath = 'soal/copy_' . time() . '_' . basename($q->question_image);
                    Storage::copy('public/' . $q->question_image, 'public/' . $newPath);
                    $newQ->question_image = $newPath;
                }

                // Salin file gambar pada opsi jawaban (Jika ada)
                $opts = is_string($q->options) ? json_decode($q->options, true) : ($q->options ?? []);
                $newOpts = $opts;
                foreach(['A', 'B', 'C', 'D', 'E'] as $opt) {
                    if(isset($opts["image_$opt"]) && Storage::exists('public/' . $opts["image_$opt"])) {
                        $newOptPath = 'soal/copy_' . time() . '_' . basename($opts["image_$opt"]);
                        Storage::copy('public/' . $opts["image_$opt"], 'public/' . $newOptPath);
                        $newOpts["image_$opt"] = $newOptPath;
                    }
                }
                $newQ->options = $newOpts;
                $newQ->save();
            }

            DB::commit();
            return redirect()->route('cbt.index')->with('success', 'Jadwal ujian beserta soal berhasil diduplikasi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menduplikasi jadwal: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('cbt.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Dasar
        $request->validate([
            'title' => 'required|string|max:255',
            'exam_type' => 'required|in:cbt,google_form',
            'subject_name' => 'required|string',
        ]);

        // 2. TANGKAP DATA SECARA EKSPLISIT (Solusi Anti-Gagal)
        // Dengan cara ini, tidak ada lagi field yang akan diam-diam dibuang oleh Laravel
        $data = [
            'title' => $request->title,
            'exam_type' => $request->exam_type,
            'google_form_url' => $request->exam_type === 'google_form' ? $request->google_form_url : null,
            'subject_name' => $request->subject_name,
            'class_level' => $request->class_level,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $request->duration_minutes,
            'question_limit' => $request->exam_type === 'google_form' ? 0 : ($request->question_limit ?? 0),
            'passing_grade' => $request->exam_type === 'google_form' ? 0 : ($request->passing_grade ?? 0),
            'token' => $request->filled('token') ? strtoupper($request->token) : strtoupper(Str::random(5)),
            'is_active' => $request->has('is_active'),
            'randomize_questions' => $request->exam_type === 'google_form' ? false : $request->has('randomize_questions'),
            'randomize_options' => $request->exam_type === 'google_form' ? false : $request->has('randomize_options'),

        ];

        // 3. Simpan ke Database
        CbtExam::create($data);

        return redirect()->route('cbt.index')->with('success', 'Jadwal ujian berhasil dibuat!');
    }

    public function edit($id)
    {
        $exam = CbtExam::findOrFail($id);
        $subjects = Subject::orderBy('name')->get(); 
        return view('cbt.edit', compact('exam', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        $exam = CbtExam::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'exam_type' => 'required|in:cbt,google_form',
            'subject_name' => 'required|string',
        ]);

        // Tangkap data eksplisit untuk Update
        $data = [
            'title' => $request->title,
            'exam_type' => $request->exam_type,
            'google_form_url' => $request->exam_type === 'google_form' ? $request->google_form_url : null,
            'subject_name' => $request->subject_name,
            'class_level' => $request->class_level,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $request->duration_minutes,
            'question_limit' => $request->exam_type === 'google_form' ? 0 : ($request->question_limit ?? 0),
            'passing_grade' => $request->exam_type === 'google_form' ? 0 : ($request->passing_grade ?? 0),
            'is_active' => $request->has('is_active'),
            'randomize_questions' => $request->exam_type === 'google_form' ? false : $request->has('randomize_questions'),
            'randomize_options' => $request->exam_type === 'google_form' ? false : $request->has('randomize_options'),
        ];

        if ($request->filled('token')) {
            $data['token'] = strtoupper($request->token);
        }

        $exam->update($data);

        return redirect()->route('cbt.index')->with('success', 'Jadwal ujian berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);

        foreach ($exam->questions as $question) {
            if ($question->question_image && Storage::exists('public/' . $question->question_image)) {
                Storage::delete('public/' . $question->question_image);
            }
            
            $opts = is_string($question->options) ? json_decode($question->options, true) : ($question->options ?? []);
            foreach(['A', 'B', 'C', 'D', 'E'] as $opt) {
                if(isset($opts["image_$opt"]) && Storage::exists('public/' . $opts["image_$opt"])) {
                    Storage::delete('public/' . $opts["image_$opt"]);
                }
            }
            if(isset($opts['pairs'])) {
                foreach($opts['pairs'] as $pair) {
                    if(isset($pair['left_image']) && Storage::exists('public/' . $pair['left_image'])) {
                        Storage::delete('public/' . $pair['left_image']);
                    }
                    if(isset($pair['right_image']) && Storage::exists('public/' . $pair['right_image'])) {
                        Storage::delete('public/' . $pair['right_image']);
                    }
                }
            }
        }
        
       $exam->delete();

        return redirect()->route('cbt.index')->with('success', 'Data ujian berhasil dihapus.');
    }

    public function manageQuestions($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);   
        $totalPoints = $exam->questions->sum('score_weight');
        
        // Ambil data bank soal yang relevan untuk dikirim ke view
        $banks = \App\Models\CbtQuestionBank::where('class_level', $exam->class_level)
            ->orWhere('subject_name', 'like', '%' . $exam->subject_name . '%')
            ->get();
        
        return view('cbt.manage_questions', compact('exam', 'totalPoints', 'banks'));
    }



    public function storeQuestion(Request $request, $id)
    {
        $exam = CbtExam::findOrFail($id);
        
        $request->validate([
            'question_type' => 'required|in:choice,essay,true_false,matching',
            'question_text' => 'required',
            'score_weight' => 'required|integer|min:1',
            'question_image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('question_image')) {
            $imagePath = $request->file('question_image')->store('soal', 'public');
        }

        $type = $request->question_type;
        $options = [];
        $correctAnswer = '';

        if ($type === 'choice') {
            $request->validate(['correct_answer' => 'required']);
            
            $options = [
                'A' => $request->option_A, 'B' => $request->option_B, 
                'C' => $request->option_C, 'D' => $request->option_D, 'E' => $request->option_E
            ];
            
            foreach(['A', 'B', 'C', 'D', 'E'] as $opt) {
                if ($request->hasFile("image_$opt")) {
                    $options["image_$opt"] = $request->file("image_$opt")->store('soal', 'public');
                }
            }
            
            $options = array_filter($options, fn($v) => !is_null($v) && $v !== '');
            $correctAnswer = $request->correct_answer;

        } elseif ($type === 'true_false') {
            $request->validate(['correct_answer' => 'required']);
            $options = ['A' => 'Benar', 'B' => 'Salah'];
            $correctAnswer = $request->correct_answer;

        } elseif ($type === 'matching') {
            $pairs = [];
            $correctMap = [];
            if($request->has('matches')) {
                foreach($request->matches as $index => $match) {
                    $leftText = $match['left'] ?? '';
                    $rightText = $match['right'] ?? '';
                    
                    $leftImgPath = null;
                    $rightImgPath = null;

                    if ($request->hasFile("matches.$index.left_image")) {
                        $leftImgPath = $request->file("matches.$index.left_image")->store('soal', 'public');
                    }
                    if ($request->hasFile("matches.$index.right_image")) {
                        $rightImgPath = $request->file("matches.$index.right_image")->store('soal', 'public');
                    }

                    if(!empty($leftText) || !empty($rightText) || $leftImgPath || $rightImgPath) {
                        $pairs[] = [
                            'left' => $leftText, 'right' => $rightText,
                            'left_image' => $leftImgPath, 'right_image' => $rightImgPath
                        ];
                        
                        $keyLeft = !empty($leftText) ? $leftText : 'IMG_LEFT_'.$index;
                        $keyRight = !empty($rightText) ? $rightText : 'IMG_RIGHT_'.$index;
                        $correctMap[$keyLeft] = $keyRight;
                    }
                }
            }
            $options = ['pairs' => $pairs]; 
            $correctAnswer = json_encode($correctMap);

        } elseif ($type === 'essay') {
            $options = []; 
            $correctAnswer = $request->correct_answer ?? ''; 
        }

         CbtQuestion::create([
            'cbt_exam_id' => $exam->id,
            'question_type' => $type,
            'question_text' => $request->question_text,
            'question_image' => $imagePath,
            'options' => $options,
            'correct_answer' => $correctAnswer,
            'score_weight' => $request->score_weight,
            'tags' => $request->tags // NEW: Simpan Tags
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan!');
    }

    public function updateQuestion(Request $request, $id)
    {
        $question = CbtQuestion::findOrFail($id);
        
        $request->validate([
            'question_text' => 'required',
            'score_weight' => 'required|integer|min:1',
        ]);

        if ($request->has('delete_image') && $request->delete_image == 'true') {
            if ($question->question_image && Storage::exists('public/' . $question->question_image)) {
                Storage::delete('public/' . $question->question_image);
            }
            $question->question_image = null;
        }
        if ($request->hasFile('question_image')) {
            if ($question->question_image && Storage::exists('public/' . $question->question_image)) {
                Storage::delete('public/' . $question->question_image);
            }
            $question->question_image = $request->file('question_image')->store('soal', 'public');
        }

        $type = $request->question_type ?? $question->question_type ?? 'choice';
        $options = [];
        $correctAnswer = ''; 

        if ($type === 'choice') {
            $options = [
                'A' => $request->option_A, 'B' => $request->option_B, 
                'C' => $request->option_C, 'D' => $request->option_D, 'E' => $request->option_E
            ];

            $oldOptions = is_string($question->options) ? json_decode($question->options, true) : ($question->options ?? []);

            foreach(['A', 'B', 'C', 'D', 'E'] as $opt) {
                $imgKey = "image_$opt";
                $deleteKey = "delete_image_$opt";

                if (isset($oldOptions[$imgKey])) {
                    $options[$imgKey] = $oldOptions[$imgKey];
                }

                if (($request->has($deleteKey) && $request->$deleteKey == 'true') || $request->hasFile($imgKey)) {
                    if (isset($oldOptions[$imgKey]) && Storage::exists('public/' . $oldOptions[$imgKey])) {
                        Storage::delete('public/' . $oldOptions[$imgKey]);
                    }
                    unset($options[$imgKey]); 
                }

                if ($request->hasFile($imgKey)) {
                    $options[$imgKey] = $request->file($imgKey)->store('soal', 'public');
                }
            }

            $options = array_filter($options, fn($v) => !is_null($v) && $v !== '');
            $correctAnswer = $request->correct_answer;

        } elseif ($type === 'true_false') {
            $options = ['A' => 'Benar', 'B' => 'Salah'];
            $correctAnswer = $request->correct_answer;

        } elseif ($type === 'matching') {
            $pairs = [];
            $correctMap = [];
            
            $oldOptions = is_string($question->options) ? json_decode($question->options, true) : ($question->options ?? []);
            $oldPairs = $oldOptions['pairs'] ?? [];

            if($request->has('matches')) {
                foreach($request->matches as $index => $match) {
                    $leftText = $match['left'] ?? '';
                    $rightText = $match['right'] ?? '';
                    
                    $leftImgPath = null;
                    $rightImgPath = null;

                    if (isset($oldPairs[$index]['left_image'])) {
                        $leftImgPath = $oldPairs[$index]['left_image']; 
                    }
                    if ((isset($match['delete_left_image']) && $match['delete_left_image'] == 'true') || $request->hasFile("matches.$index.left_image")) {
                        if ($leftImgPath && Storage::exists('public/' . $leftImgPath)) Storage::delete('public/' . $leftImgPath);
                        $leftImgPath = null;
                    }
                    if ($request->hasFile("matches.$index.left_image")) {
                        $leftImgPath = $request->file("matches.$index.left_image")->store('soal', 'public');
                    }

                    if (isset($oldPairs[$index]['right_image'])) {
                        $rightImgPath = $oldPairs[$index]['right_image'];
                    }
                    if ((isset($match['delete_right_image']) && $match['delete_right_image'] == 'true') || $request->hasFile("matches.$index.right_image")) {
                        if ($rightImgPath && Storage::exists('public/' . $rightImgPath)) Storage::delete('public/' . $rightImgPath);
                        $rightImgPath = null;
                    }
                    if ($request->hasFile("matches.$index.right_image")) {
                        $rightImgPath = $request->file("matches.$index.right_image")->store('soal', 'public');
                    }

                    if(!empty($leftText) || !empty($rightText) || $leftImgPath || $rightImgPath) {
                        $pairs[] = [
                            'left' => $leftText, 'right' => $rightText,
                            'left_image' => $leftImgPath, 'right_image' => $rightImgPath
                        ];
                        
                        $keyLeft = !empty($leftText) ? $leftText : 'IMG_LEFT_'.$index;
                        $keyRight = !empty($rightText) ? $rightText : 'IMG_RIGHT_'.$index;
                        $correctMap[$keyLeft] = $keyRight;
                    }
                }
            }
            $options = ['pairs' => $pairs];
            $correctAnswer = json_encode($correctMap);

        } elseif ($type === 'essay') {
            $options = [];          
            $correctAnswer = $request->correct_answer ?? '';
        }

         $question->update([
            'question_type' => $type, 
            'question_text' => $request->question_text,
            'options' => $options, 
            'correct_answer' => $correctAnswer,
            'score_weight' => $request->score_weight,
            'tags' => $request->tags // NEW: Update Tags
        ]);
        
        return back()->with('success', 'Soal berhasil diperbarui!');
    }

    public function destroyQuestion($id)
    {
        $question = CbtQuestion::findOrFail($id);
        
        if ($question->question_image && Storage::exists('public/' . $question->question_image)) {
            Storage::delete('public/' . $question->question_image);
        }

        $opts = is_string($question->options) ? json_decode($question->options, true) : ($question->options ?? []);
        
        foreach(['A', 'B', 'C', 'D', 'E'] as $opt) {
            if(isset($opts["image_$opt"]) && Storage::exists('public/' . $opts["image_$opt"])) {
                Storage::delete('public/' . $opts["image_$opt"]);
            }
        }
        if(isset($opts['pairs'])) {
            foreach($opts['pairs'] as $pair) {
                if(isset($pair['left_image']) && Storage::exists('public/' . $pair['left_image'])) {
                    Storage::delete('public/' . $pair['left_image']);
                }
                if(isset($pair['right_image']) && Storage::exists('public/' . $pair['right_image'])) {
                    Storage::delete('public/' . $pair['right_image']);
                }
            }
        }
        
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }

    // --- FITUR BARU: BULK DELETE & BULK WEIGHT ---
    public function bulkDelete(Request $request, $exam_id)
    {
        if (!$request->question_ids) return back()->with('error', 'Tidak ada soal yang dipilih.');
        
        $ids = explode(',', $request->question_ids);
        $questions = CbtQuestion::whereIn('id', $ids)->get();

        foreach ($questions as $question) {
            // Hapus gambar utama soal
            if ($question->question_image && Storage::exists('public/' . $question->question_image)) {
                Storage::delete('public/' . $question->question_image);
            }
            
            // Hapus gambar pada opsi & matching
            $opts = is_string($question->options) ? json_decode($question->options, true) : ($question->options ?? []);
            foreach(['A', 'B', 'C', 'D', 'E'] as $opt) {
                if(isset($opts["image_$opt"]) && Storage::exists('public/' . $opts["image_$opt"])) {
                    Storage::delete('public/' . $opts["image_$opt"]);
                }
            }
            if(isset($opts['pairs'])) {
                foreach($opts['pairs'] as $pair) {
                    if(isset($pair['left_image']) && Storage::exists('public/' . $pair['left_image'])) Storage::delete('public/' . $pair['left_image']);
                    if(isset($pair['right_image']) && Storage::exists('public/' . $pair['right_image'])) Storage::delete('public/' . $pair['right_image']);
                }
            }
            
            // Hapus record database
            $question->delete();
        }

        return back()->with('success', count($ids) . ' soal berhasil dihapus.');
    }

    public function bulkWeight(Request $request, $exam_id)
    {
        if (!$request->question_ids || !$request->score_weight) return back()->with('error', 'Data tidak lengkap.');
        
        $ids = explode(',', $request->question_ids);
        CbtQuestion::whereIn('id', $ids)->update(['score_weight' => $request->score_weight]);
        
        return back()->with('success', 'Bobot ' . count($ids) . ' soal berhasil diubah.');
    }

    public function importQuestions(Request $request, $exam_id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new QuestionsImport($exam_id), $request->file('file'));
            return back()->with('success', 'Soal berhasil diimport dari Excel!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new QuestionTemplateExport, 'template_soal_ujian.xlsx');
    }

    public function monitoring($id)
    {
        $exam = CbtExam::withCount('questions')->findOrFail($id);
        $data = $this->getMonitoringDataInternal($id);

        return view('cbt.monitoring', [
            'exam' => $exam,
            'monitoringData' => $data['monitoringData'],
            'stats' => $data['stats']
        ]);
    }

    public function getMonitoringData($id)
    {
        $data = $this->getMonitoringDataInternal($id);
        return response()->json($data['monitoringData']);
    }

    public function autoRotateToken($id)
    {
        try {
            $exam = CbtExam::findOrFail($id);
            $newToken = strtoupper(Str::random(5));
            $exam->update(['token' => $newToken]);
            
            return response()->json(['status' => 'success', 'token' => $newToken]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error'], 500);
        }
    }

    private function getMonitoringDataInternal($id)
    {
        $exam = CbtExam::findOrFail($id);
        $students = Student::with('schoolClass')
            ->whereHas('schoolClass', function($query) use ($exam) {               
                $query->where('name', 'like', $exam->class_level . '%');
            })
            ->orderBy('name')
            ->get();

        $sessions = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $id)
            ->get()
            ->keyBy('student_id');

        $monitoringData = $students->map(function($student) use ($sessions) {
            $session = $sessions->get($student->id);
            $status = 'Belum Mengerjakan';
            $startTime = '-';
            $score = '-'; 
            $isActive = false;
            $isSeb = false;
            $deviceType = '-';

            if ($session) {
                $startTime = \Carbon\Carbon::parse($session->created_at)->format('H:i');
                if (Str::contains($session->user_agent, 'SEB') || Str::contains($session->user_agent, 'SafeExamBrowser')) {
                    $isSeb = true;
                }
                if (Str::contains(strtolower($session->user_agent), ['mobile', 'android', 'iphone'])) {
                    $deviceType = 'Mobile';
                } else {
                    $deviceType = 'Desktop';
                }

                if ($session->status == 'finished') {
                    $status = 'Selesai';
                    $score = isset($session->total_score) ? (int)$session->total_score : 0;
                } else {
                    $status = 'Sedang Mengerjakan';
                    $isActive = true; 
                    $score = '-';
                }
            }

            return [
                'id' => $student->id,
                'name' => $student->name,
                'class' => $student->schoolClass->name ?? '-',
                'status' => $status,
                'start_time' => $startTime,
                'score' => $score,
                'is_active' => $isActive,
                'is_seb' => $isSeb,
                'device' => $deviceType,
            ];
        })->values();

        $stats = [
            'total_students' => $students->count(),
            'working' => $monitoringData->where('status', 'Sedang Mengerjakan')->count(),
            'finished' => $monitoringData->where('status', 'Selesai')->count(),
            'not_started' => $monitoringData->where('status', 'Belum Mengerjakan')->count(),
        ];

        return ['monitoringData' => $monitoringData, 'stats' => $stats];
    }

    private function getRecapData($exam_id) 
    {
        $selects = [
            'cbt_student_exams.id as session_id',
            'cbt_student_exams.student_id',
            'cbt_student_exams.total_score', 
            'students.name as student_name',
            'students.student_id as student_nisn',
            'students.class_id', // PENAMBAHAN ID KELAS UNTUK SYNC
            'classes.name as class_name'
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('cbt_student_exams', 'attempt_count')) {
            $selects[] = 'cbt_student_exams.attempt_count';
        }

        $results = DB::table('cbt_student_exams')
            ->join('students', 'cbt_student_exams.student_id', '=', 'students.id')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->where('cbt_student_exams.cbt_exam_id', $exam_id)
            ->where('cbt_student_exams.status', 'finished') 
            ->select($selects)
            ->orderBy('cbt_student_exams.total_score', 'desc')
            ->get();

        foreach ($results as $row) {
            $correctCount = 0;
            $calculatedScore = 0; 
            
            $answers = DB::table('cbt_student_answers')
                ->join('cbt_questions', 'cbt_student_answers.cbt_question_id', '=', 'cbt_questions.id')
                ->where('cbt_student_answers.cbt_student_exam_id', $row->session_id)
                ->select('cbt_student_answers.*', 'cbt_questions.question_type', 'cbt_questions.correct_answer as key_answer', 'cbt_questions.score_weight')
                ->get();

            foreach($answers as $ans) {
                $isCorrect = false;
                $type = $ans->question_type ?? 'choice';
                
                $studentAns = trim($ans->answer);
                $correctAns = trim($ans->key_answer);

                if ($type == 'matching') {
                    $keyMap = json_decode($correctAns, true) ?? [];
                    $studentMap = json_decode($studentAns, true) ?? [];
                    if (is_array($keyMap)) ksort($keyMap);
                    if (is_array($studentMap)) ksort($studentMap);
                    if (!empty($keyMap) && $keyMap == $studentMap) $isCorrect = true;
                } elseif ($type == 'essay') {
                    if (!empty($correctAns) && strcasecmp($studentAns, $correctAns) == 0) $isCorrect = true;
                } else {
                    if (strcasecmp($studentAns, $correctAns) == 0) $isCorrect = true;
                }
                
                $manualScore = isset($ans->score) ? floatval($ans->score) : 0;

                if ($manualScore > 0) {
                    $calculatedScore += $manualScore;
                    $correctCount++; 
                } 
                elseif ($isCorrect) {
                    $calculatedScore += $ans->score_weight;
                    $correctCount++;
                }
            }
            
            $row->correct_answers = $correctCount; 
            $row->wrong_answers = $answers->count() - $correctCount;         
            $row->total_score = $calculatedScore; 
        }

        return $results;
    }

    public function recap($id)
    {
        $exam = CbtExam::findOrFail($id);
        $results = $this->getRecapData($id);
        $stats = [
            'average' => $results->avg('total_score') ?? 0,
            'max_score' => $results->max('total_score') ?? 0,
            'min_score' => $results->min('total_score') ?? 0,
            'passed_count' => $results->where('total_score', '>=', $exam->passing_grade)->count(),
        ];
        return view('cbt.recap', compact('exam', 'results', 'stats'));
    }

    public function export($id, $type)
    {
        $exam = CbtExam::findOrFail($id);
        $results = $this->getRecapData($id);
        $stats = [
            'average' => $results->avg('total_score') ?? 0,
            'max_score' => $results->max('total_score') ?? 0,
            'min_score' => $results->min('total_score') ?? 0,
            'passed_count' => $results->where('total_score', '>=', $exam->passing_grade)->count(),
        ];
        $fileName = 'REKAP_' . Str::slug($exam->title);
        if ($type == 'excel') {
            return Excel::download(new CbtScoreExport($results, $exam->passing_grade), $fileName . '.xlsx');
        } 
        return view('cbt.pdf_export', compact('exam', 'results', 'stats'));
    }

    public function analysis($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);
        $finishedSessionIds = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $id)
            ->where('status', 'finished')
            ->pluck('id');
        
         $totalStudents = $finishedSessionIds->count();
        $allAnswers = DB::table('cbt_student_answers')
            ->whereIn('cbt_student_exam_id', $finishedSessionIds)
            ->get()
            ->groupBy('cbt_question_id'); 
            
        $tagAnalysis = []; // NEW: Wadah untuk rekapan materi (KD)
            
        $analysis = $exam->questions->map(function($q) use ($allAnswers, $totalStudents, &$tagAnalysis) {
            $answers = $allAnswers->get($q->id);
            $stats = [
                'id' => $q->id,
                'type' => $q->question_type ?? 'choice', 
                'text' => strip_tags($q->question_text), 
                'correct_key' => $q->correct_answer,
                'tags' => $q->tags, // Masukkan tags agar tampil di tabel frontend
                'correct_count' => 0,
                'wrong_count' => 0,
                'options' => ['A'=>0, 'B'=>0, 'C'=>0, 'D'=>0, 'E'=>0]
            ];
            
            if ($answers) {
                foreach($answers as $ans) {
                    if(in_array($stats['type'], ['choice', 'true_false'])) {
                        $val = strtoupper($ans->answer);
                        if(isset($stats['options'][$val])) $stats['options'][$val]++;
                    }

                    $isCorrect = false;
                    
                    if(isset($ans->score) && $ans->score > 0) {
                        $isCorrect = true;
                    } 
                    elseif(strcasecmp($ans->answer, $q->correct_answer) == 0) {
                        $isCorrect = true;
                    }

                    if($isCorrect) $stats['correct_count']++;
                    else $stats['wrong_count']++;
                }
            }

             // NEW: Kalkulasi Statistik per Tags (Kompetensi Dasar)
            if (!empty($q->tags)) {
                $tags = array_map('trim', explode(',', $q->tags));
                foreach ($tags as $tag) {
                    if (!isset($tagAnalysis[$tag])) {
                        $tagAnalysis[$tag] = ['correct' => 0, 'total' => 0];
                    }
                    $tagAnalysis[$tag]['correct'] += $stats['correct_count'];
                    $tagAnalysis[$tag]['total'] += $totalStudents;
                }
            }
            
            $p = $totalStudents > 0 ? ($stats['correct_count'] / $totalStudents) : 0;
            $difficulty = 'Sedang';
            $badgeColor = 'bg-blue-100 text-blue-700';
            if ($p > 0.70) { $difficulty = 'Mudah'; $badgeColor = 'bg-emerald-100 text-emerald-700'; }
            elseif ($p < 0.30) { $difficulty = 'Sukar'; $badgeColor = 'bg-rose-100 text-rose-700'; }
            
            $stats['difficulty_label'] = $difficulty;
           $stats['difficulty_badge'] = $badgeColor;
            $stats['difficulty_index'] = round($p * 100); 
            
            return (object) $stats;
        });
        
        return view('cbt.analysis', compact('exam', 'analysis', 'totalStudents', 'tagAnalysis'));
    }
    
    public function printAnalysis($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);
        $finishedSessionIds = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $id)
            ->where('status', 'finished')
            ->pluck('id');
        
        $totalStudents = $finishedSessionIds->count();
        $allAnswers = DB::table('cbt_student_answers')
            ->whereIn('cbt_student_exam_id', $finishedSessionIds)
            ->get()
            ->groupBy('cbt_question_id'); 
            
        $analysis = $exam->questions->map(function($q) use ($allAnswers, $totalStudents) {
            $answers = $allAnswers->get($q->id);
            $stats = [
                'id' => $q->id,
                'type' => $q->question_type ?? 'choice', 
                'text' => strip_tags($q->question_text), 
                'correct_key' => $q->correct_answer,
                'correct_count' => 0,
                'wrong_count' => 0,
                'options' => ['A'=>0, 'B'=>0, 'C'=>0, 'D'=>0, 'E'=>0]
            ];
            
            if ($answers) {
                foreach($answers as $ans) {
                    if(in_array($stats['type'], ['choice', 'true_false'])) {
                        $val = strtoupper($ans->answer);
                        if(isset($stats['options'][$val])) $stats['options'][$val]++;
                    }

                    $isCorrect = false;
                    
                    if(isset($ans->score) && $ans->score > 0) {
                        $isCorrect = true;
                    } 
                    elseif(strcasecmp($ans->answer, $q->correct_answer) == 0) {
                        $isCorrect = true;
                    }

                    if($isCorrect) $stats['correct_count']++;
                    else $stats['wrong_count']++;
                }
            }
            
            $p = $totalStudents > 0 ? ($stats['correct_count'] / $totalStudents) : 0;
            $difficulty = 'Sedang';
            $badgeColor = 'bg-blue-100 text-blue-700';
            if ($p > 0.70) { $difficulty = 'Mudah'; $badgeColor = 'bg-emerald-100 text-emerald-700'; }
            elseif ($p < 0.30) { $difficulty = 'Sukar'; $badgeColor = 'bg-rose-100 text-rose-700'; }
            
            $stats['difficulty_label'] = $difficulty;
            $stats['difficulty_badge'] = $badgeColor;
            $stats['difficulty_index'] = round($p * 100); 
            
            return (object) $stats;
        });
        
        return view('cbt.analysis_pdf', compact('exam', 'analysis', 'totalStudents'));
    }

    public function resultDetail($exam_id, $student_id)
    {
        $exam = CbtExam::findOrFail($exam_id);
        $student = Student::with('schoolClass')->findOrFail($student_id);
        $examSession = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->first();
        if (!$examSession) {
            return back()->with('error', 'Siswa ini belum mengerjakan ujian.');
        }
        $answers = DB::table('cbt_student_answers')
            ->join('cbt_questions', 'cbt_student_answers.cbt_question_id', '=', 'cbt_questions.id')
            ->where('cbt_student_answers.cbt_student_exam_id', $examSession->id)
            ->select('cbt_questions.*', 'cbt_student_answers.answer as student_answer', 'cbt_student_answers.id as answer_id', 'cbt_student_answers.score')
            ->get();
        $answers->transform(function ($item) {
            $item->options = json_decode($item->options, true);
            $item->option_A = $item->options['A'] ?? '';
            $item->option_B = $item->options['B'] ?? '';
            $item->option_C = $item->options['C'] ?? '';
            $item->option_D = $item->options['D'] ?? '';
            return $item;
        });
        $stats = [
            'correct' => $answers->filter(function($q) {
                if($q->question_type == 'essay') return ($q->score ?? 0) > 0;
                if($q->question_type == 'matching') return false;
                return strcasecmp($q->student_answer, $q->correct_answer) == 0;
            })->count(),
            'wrong'   => $answers->filter(function($q) {
                if($q->question_type == 'essay') return ($q->score ?? 0) <= 0;
                if($q->question_type == 'matching') return false;
                return strcasecmp($q->student_answer, $q->correct_answer) != 0 && !is_null($q->student_answer);
            })->count(),
        ];
        return view('cbt.result_detail', compact('exam', 'student', 'examSession', 'answers', 'stats'));
    }

    public function allowRetake($exam_id, $student_id)
    {
        $session = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->first();

        if ($session) {
            DB::table('cbt_student_answers')->where('cbt_student_exam_id', $session->id)->delete();
            DB::table('cbt_exam_photos')->where('cbt_student_exam_id', $session->id)->delete();

            $updateData = [
                'status' => 'ongoing',       
                'total_score' => null,       
                'created_at' => now(),       
                'updated_at' => now(),
            ];

            $attempt = 2; 
            
            if (\Illuminate\Support\Facades\Schema::hasColumn('cbt_student_exams', 'attempt_count')) {
                $attempt = isset($session->attempt_count) ? $session->attempt_count + 1 : 2;
                $updateData['attempt_count'] = $attempt;
            }

            DB::table('cbt_student_exams')->where('id', $session->id)->update($updateData);

            return back()->with('success', 'Siswa diizinkan untuk mengerjakan ulang. Ujian ini menjadi percobaan ke-' . $attempt . '.');
        }

        return back()->with('error', 'Data ujian siswa tidak ditemukan.');
    }

    public function gradeEssay(Request $request)
    {
        $request->validate([
            'answer_id' => 'required|integer',
            'score' => 'required|numeric|min:0',
        ]);

        $answer = DB::table('cbt_student_answers')->where('id', $request->answer_id)->first();
        if (!$answer) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);

        $question = DB::table('cbt_questions')->where('id', $answer->cbt_question_id)->first();
        $maxScore = $question->score_weight ?? 100;

        if ($request->score > $maxScore) {
            return response()->json(['status' => 'error', 'message' => "Nilai max: $maxScore"], 422);
        }

        DB::table('cbt_student_answers')->where('id', $request->answer_id)->update([
            'score' => $request->score,
            'is_correct' => $request->score > 0
        ]);

        $sessionId = $answer->cbt_student_exam_id;
        $allAnswers = DB::table('cbt_student_answers')
                        ->join('cbt_questions', 'cbt_student_answers.cbt_question_id', '=', 'cbt_questions.id')
                        ->where('cbt_student_answers.cbt_student_exam_id', $sessionId)
                        ->select('cbt_student_answers.score', 'cbt_student_answers.is_correct', 'cbt_questions.score_weight')
                        ->get();

        $newTotalScore = 0;
        foreach($allAnswers as $ans) {
            if (!is_null($ans->score) && $ans->score > 0) {
                $newTotalScore += $ans->score;
            } elseif ($ans->is_correct) {
                $newTotalScore += $ans->score_weight;
            }
        }

        DB::table('cbt_student_exams')->where('id', $sessionId)->update(['total_score' => $newTotalScore]);

        return response()->json(['status' => 'success', 'message' => 'Nilai tersimpan', 'new_total' => $newTotalScore]);
    }

    public function resetExam($exam_id, $student_id)
    {
        DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->delete(); 
        return back()->with('success', 'Status ujian siswa berhasil di-reset.');
    }

    public function refreshToken($id)
    {
        $exam = CbtExam::findOrFail($id);
        $newToken = strtoupper(Str::random(5));
        $exam->update(['token' => $newToken]);
        return back()->with('success', 'Token ujian berhasil diperbarui: ' . $newToken);
    }

    public function download_seb($id)
    {
        $exam = CbtExam::findOrFail($id);
        $startUrl = route('seb.login'); 
        $quitPassword = '12345'; 
        $sebConfig = '...'; 
        $fileName = Str::slug($exam->title) . '.seb';
        return response()->streamDownload(function () use ($sebConfig) { echo $sebConfig; }, $fileName, ['Content-Type' => 'application/seb']);
    }

    // FITUR SYNC POST NILAI KE LMS
    public function syncToGradebook(Request $request, $id)
    {
        $exam = CbtExam::findOrFail($id);
        
        $subject = Subject::where('name', $exam->subject_name)->first();
        if (!$subject) {
            return back()->with('error', 'Gagal Post Nilai: Mata Pelajaran "' . $exam->subject_name . '" tidak ditemukan di data Master LMS. Pastikan namanya sama persis.');
        }

        $results = $this->getRecapData($id);
        
        if ($results->isEmpty()) {
            return back()->with('warning', 'Belum ada siswa yang menyelesaikan ujian ini, tidak ada nilai yang diposting.');
        }

        $successCount = 0;
        $groupedByClass = $results->groupBy('class_id');

        DB::beginTransaction();
        try {
            foreach ($groupedByClass as $classId => $studentResults) {
                if (!$classId) continue; 

                $assignment = LmsAssignment::firstOrCreate(
                    [
                        'class_id' => $classId,
                        'subject_id' => $subject->id,
                        'title' => $exam->title, 
                        'assignment_type' => 'quiz', 
                    ],
                    [
                        'description' => 'Nilai otomatis diposting dari hasil Ujian CBT.',
                        'teacher_id' => Auth::id(),
                        'deadline' => $exam->end_time, 
                    ]
                );

                foreach ($studentResults as $res) {
                    LmsSubmission::updateOrCreate(
                        [
                            'assignment_id' => $assignment->id,
                            'student_id' => $res->student_id,
                        ],
                        [
                            'grade' => $res->total_score,
                            'status' => 'graded', 
                            'submitted_at' => now(),
                        ]
                    );
                    $successCount++;
                }
            }
            DB::commit();

            if ($successCount > 0) {
                return back()->with('success', "Berhasil memposting $successCount nilai siswa ke Rekap Nilai LMS!");
            }
            
            return back()->with('warning', 'Tidak ada data valid yang bisa diposting (Pastikan data siswa memiliki kelas).');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem saat memposting nilai: ' . $e->getMessage());
        }
    }
    
    public function results() { 
        $results = DB::table('cbt_student_exams')
            ->join('students', 'cbt_student_exams.student_id', '=', 'students.id')
            ->join('cbt_exams', 'cbt_student_exams.cbt_exam_id', '=', 'cbt_exams.id')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->where('cbt_student_exams.status', 'finished')
            ->select('cbt_student_exams.*', 'students.name as student_name', 'classes.name as class_name', 'cbt_exams.title as exam_title', 'cbt_exams.subject_name')
            ->orderBy('cbt_student_exams.created_at', 'desc')
            ->paginate(20);
        return view('cbt.results', compact('results'));
    } 

    public function cardIndex()
    {
        $classes = SchoolClass::orderBy('name')->get();
        return view('cbt.cards.index', compact('classes'));
    }

    public function printCards(Request $request)
    {
        $query = Student::with('schoolClass')->orderBy('name');
        if ($request->mode == 'class') {
            if ($request->has('class_id')) {
                $query->where('class_id', $request->class_id);
            }
        } 
        elseif ($request->mode == 'level') {
            if ($request->has('level') && $request->level != 'all') {
                $query->whereHas('schoolClass', function($q) use ($request) {
                    $q->where('name', 'like', $request->level . '%');
                });
            }
        }
        $students = $query->get();
        if ($students->isEmpty()) {
            return response("<script>alert('Tidak ada siswa ditemukan pada kriteria tersebut.'); window.close();</script>");
        }
        foreach($students as $student) {
            $student->login_url = route('student.login', ['username' => $student->student_id]);
        }
        return view('cbt.cards.print', compact('students'));
    }

    public function getStudentPhotos($exam_id, $student_id)
    {
        $session = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->first();
        if (!$session) {
            return response()->json([]);
        }
        $photos = DB::table('cbt_exam_photos')
            ->where('cbt_student_exam_id', $session->id)
            ->orderBy('captured_at', 'desc')
            ->get()
            ->map(function($p) {
                return [
                    'url' => asset('storage/' . $p->photo_path),
                    'time' => \Carbon\Carbon::parse($p->captured_at)->format('H:i:s'),
                    'ago' => \Carbon\Carbon::parse($p->captured_at)->diffForHumans()
                ];
            });
        return response()->json($photos);
    }

    public function printQuestions($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);
        $title = $exam->title;
        $subject = $exam->subject_name;
        $info = "Kelas: " . $exam->class_level;
        $questions = $exam->questions;
        $type = 'Ujian CBT';
        
        return view('cbt.print_questions', compact('title', 'subject', 'info', 'questions', 'type'));
    }

     /**
     * Export Soal dari Ujian CBT tertentu ke Excel/CSV
     */
    public function exportQuestions($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);
        $questions = $exam->questions;

        // Header kolom Excel
        $headers = ['soal', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e', 'kunci', 'bobot', 'materi_kd'];

        $callback = function() use ($questions, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            foreach ($questions as $q) {
                // Parsing opsi dari JSON jika perlu
                $opts = is_string($q->options) ? json_decode($q->options, true) : ($q->options ?? []);
                
                fputcsv($file, [
                    strip_tags($q->question_text), // Bersihkan tag HTML untuk Excel
                    $q->option_A ?? ($opts['A'] ?? ''),
                    $q->option_B ?? ($opts['B'] ?? ''),
                    $q->option_C ?? ($opts['C'] ?? ''),
                    $q->option_D ?? ($opts['D'] ?? ''),
                    $q->option_E ?? ($opts['E'] ?? ''),
                    $q->correct_answer,
                    $q->score_weight,
                    $q->tags
                ]);
            }
            fclose($file);
        };

        $fileName = 'SOAL_' . Str::slug($exam->title) . '_' . date('Ymd_His') . '.csv';

        return response()->stream($callback, 200, [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }

     // =========================================================================
    // FITUR BARU: PREVIEW & EXPORT WORD
    // =========================================================================

    /**
     * Mode Pratinjau (Preview) seperti tampilan siswa
     */
    public function preview($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);
        return view('cbt.preview', compact('exam'));
    }

    /**
     * Export ke format Microsoft Word (.doc)
     */
    public function exportWord($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);
        $fileName = 'Soal_Ujian_' . Str::slug($exam->title) . '_' . date('Ymd') . '.doc';

        $headers = [
            "Content-type" => "application/vnd.ms-word",
            "Content-Disposition" => "attachment;Filename={$fileName}",
            "Pragma" => "no-cache",
            "Expires" => "0"
        ];

        return response()->view('cbt.export_word', compact('exam'))->withHeaders($headers);
    }
    
}