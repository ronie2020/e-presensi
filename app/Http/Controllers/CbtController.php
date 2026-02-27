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
use App\Models\LmsAssignment;
use App\Models\LmsGrade;       
use App\Models\LmsSubmission;  
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Auth; 

class CbtController extends Controller
{
    /**
     * Menampilkan Dashboard CBT
     */
    public function index()
    {
        $stats = [
            'active_exams' => CbtExam::where('is_active', true)->count(),
            'total_questions' => DB::table('cbt_questions')->count(),
            'students_working' => DB::table('cbt_student_exams')->where('status', 'ongoing')->count(),
            'avg_score' => DB::table('cbt_student_exams')->whereNotNull('total_score')->avg('total_score') ?? 0,
        ];

        $exams = CbtExam::latest()->take(10)->get();

        return view('cbt.index', compact('stats', 'exams'));
    }

    /**
     * Halaman Buat Jadwal Ujian    
     */
    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('cbt.create', compact('subjects'));
    }

    /**
     * Simpan Jadwal Ujian Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_name' => 'required|string',
            'class_level' => 'required',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration_minutes' => 'required|integer|min:1',
            'passing_grade' => 'required|integer|min:0|max:100',
            'token' => 'nullable|string|max:6',
        ]);

        $validated['is_active'] = $request->has('is_active');
        if (empty($validated['token'])) {
            $validated['token'] = strtoupper(Str::random(5));
        }

        CbtExam::create($validated);

        return redirect()->route('cbt.index')->with('success', 'Jadwal ujian berhasil dibuat!');
    }

    /**
     * Halaman Edit Jadwal Ujian    
     */
    public function edit($id)
    {
        $exam = CbtExam::findOrFail($id);
        $subjects = Subject::orderBy('name')->get(); 
        return view('cbt.edit', compact('exam', 'subjects'));
    }

    /**
     * Update Jadwal Ujian
     */
    public function update(Request $request, $id)
    {
        $exam = CbtExam::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'subject_name' => 'required|string',
            'class_level' => 'required',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration_minutes' => 'required|integer|min:1',
            'passing_grade' => 'required|integer|min:0|max:100',
            'token' => 'nullable|string|max:6',
        ]);

        $updateData = $request->only(['title', 'subject_name', 'class_level', 'start_time', 'end_time', 'duration_minutes', 'passing_grade']);
        $updateData['is_active'] = $request->has('is_active');
              
        if ($request->filled('token')) {
            $updateData['token'] = strtoupper($request->token);
        }

        $exam->update($updateData);

        return redirect()->route('cbt.index')->with('success', 'Jadwal ujian berhasil diperbarui!');
    }

    /**
     * Hapus Data Ujian (Dan Seluruh Soalnya beserta Gambarnya)
     */
    public function destroy($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);

        foreach ($exam->questions as $question) {
            // Hapus gambar utama
            if ($question->question_image && Storage::exists('public/' . $question->question_image)) {
                Storage::delete('public/' . $question->question_image);
            }
            
            // Hapus gambar opsi & menjodohkan
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

    /**
     * Halaman Kelola Soal
     */
    public function manageQuestions($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);
        return view('cbt.manage_questions', compact('exam'));
    }

    /**
     * Simpan Soal Manual
     */
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
            
            // Upload gambar opsi jika ada
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

                    // Hanya masukkan pasangan jika ada teks ATAU gambar yang diinput
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
            'score_weight' => $request->score_weight
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan!');
    }

    /**
     * Update Soal
     */
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

            // Ambil opsi lama untuk mempertahankan gambar yang sudah ada
            $oldOptions = is_string($question->options) ? json_decode($question->options, true) : ($question->options ?? []);

            foreach(['A', 'B', 'C', 'D', 'E'] as $opt) {
                $imgKey = "image_$opt";
                $deleteKey = "delete_image_$opt";

                // Pertahankan gambar lama dulu
                if (isset($oldOptions[$imgKey])) {
                    $options[$imgKey] = $oldOptions[$imgKey];
                }

                // Jika user mencentang hapus ATAU mengupload file baru, hapus file fisik lama
                if (($request->has($deleteKey) && $request->$deleteKey == 'true') || $request->hasFile($imgKey)) {
                    if (isset($oldOptions[$imgKey]) && Storage::exists('public/' . $oldOptions[$imgKey])) {
                        Storage::delete('public/' . $oldOptions[$imgKey]);
                    }
                    unset($options[$imgKey]); 
                }

                // Jika ada file baru diupload, simpan ke storage
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

                    // Lakukan logika replace/delete pada Gambar Kiri
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

                    // Lakukan logika replace/delete pada Gambar Kanan
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
            'score_weight' => $request->score_weight
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
        
        // Hapus Gambar Pilihan Ganda
        foreach(['A', 'B', 'C', 'D', 'E'] as $opt) {
            if(isset($opts["image_$opt"]) && Storage::exists('public/' . $opts["image_$opt"])) {
                Storage::delete('public/' . $opts["image_$opt"]);
            }
        }
        // Hapus Gambar Menjodohkan
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

    // --- RECAP & ANALYTICS ---

    /**
     * Helper Private untuk mengambil data Rekap 
     * Memperhitungkan nilai manual (score) yang tersimpan dan riwayat percobaan
     */
     private function getRecapData($exam_id) 
    {
        // 1. Ambil Data Dasar Siswa & Nilai Akhir
        $selects = [
            'cbt_student_exams.id as session_id',
            'cbt_student_exams.student_id',
            'cbt_student_exams.total_score', 
            'students.name as student_name',
            'students.student_id as student_nisn',
            'classes.name as class_name'
        ];

        // Aman: Hanya select attempt_count jika kolomnya ada di database
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

        // 2. Kalkulasi Ulang Nilai (Untuk memastikan nilai 0 bukan karena bug)
        foreach ($results as $row) {
            $correctCount = 0;
            $calculatedScore = 0; // Hitung ulang skor
            
            $answers = DB::table('cbt_student_answers')
                ->join('cbt_questions', 'cbt_student_answers.cbt_question_id', '=', 'cbt_questions.id')
                ->where('cbt_student_answers.cbt_student_exam_id', $row->session_id)
                // Select kolom 'score' dari tabel jawaban
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
                
                // Cek apakah ada nilai manual (score) di database jawaban
                $manualScore = isset($ans->score) ? floatval($ans->score) : 0;

                if ($manualScore > 0) {
                    // PRIORITAS 1: Jika ada nilai manual (koreksi guru), gunakan itu
                    $calculatedScore += $manualScore;
                    // Anggap benar jika dapat nilai
                    $correctCount++; 
                } 
                elseif ($isCorrect) {
                    // PRIORITAS 2: Jika tidak ada nilai manual, pakai logika auto-grade
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
                    // Distribusi Jawaban (Khusus PG)
                    if(in_array($stats['type'], ['choice', 'true_false'])) {
                        $val = strtoupper($ans->answer);
                        if(isset($stats['options'][$val])) $stats['options'][$val]++;
                    }

                    // Logika Benar/Salah (Support Nilai Manual Essai)
                    $isCorrect = false;
                    
                    // Prioritas 1: Cek jika ada nilai manual (score > 0) atau is_correct di DB
                    if(isset($ans->score) && $ans->score > 0) {
                        $isCorrect = true;
                    } 
                    // Prioritas 2: Auto grade string match
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
        
        return view('cbt.analysis', compact('exam', 'analysis', 'totalStudents'));
    }
    
    // print analisis
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
        
        // Arahkan ke file view khusus print yang sudah kita buat
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
                // Untuk Essay, anggap benar jika sudah dinilai manual (score > 0)
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

    /**
     * [BARU] Fungsi Mengizinkan Ujian Ulang (Retake)
     */
    public function allowRetake($exam_id, $student_id)
    {
        $session = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->first();

        if ($session) {
            // Hapus jawaban lama dan foto pengawasan agar bisa mengerjakan dari awal
            DB::table('cbt_student_answers')->where('cbt_student_exam_id', $session->id)->delete();
            DB::table('cbt_exam_photos')->where('cbt_student_exam_id', $session->id)->delete();

            $updateData = [
                'status' => 'ongoing',       // Ubah status ke ongoing (ujian dimulai lagi)
                'total_score' => null,       // Reset skor
                'created_at' => now(),       // Reset waktu mulai agar timer durasi penuh lagi
                'updated_at' => now(),
            ];

            $attempt = 2; // Default jika baru retake pertama kali
            
            // Cek jika kolom attempt_count sudah ditambahkan di database
            if (\Illuminate\Support\Facades\Schema::hasColumn('cbt_student_exams', 'attempt_count')) {
                $attempt = isset($session->attempt_count) ? $session->attempt_count + 1 : 2;
                $updateData['attempt_count'] = $attempt;
            }

            DB::table('cbt_student_exams')->where('id', $session->id)->update($updateData);

            return back()->with('success', 'Siswa diizinkan untuk mengerjakan ulang. Ujian ini menjadi percobaan ke-' . $attempt . '.');
        }

        return back()->with('error', 'Data ujian siswa tidak ditemukan.');
    }

    /**
     * [BARU] Fungsi Penilaian Manual Essai
     */
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

        // Hitung ulang total
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
        $sebConfig = '...'; // Config SEB disingkat
        $fileName = Str::slug($exam->title) . '.seb';
        return response()->streamDownload(function () use ($sebConfig) { echo $sebConfig; }, $fileName, ['Content-Type' => 'application/seb']);
    }

    public function syncToGradebook(Request $request, $id)
    {
        // Logika sync tetap sama seperti file lama
        return back()->with('warning', 'Fitur sync belum diaktifkan.');
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
}