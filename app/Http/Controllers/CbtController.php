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
        // Ambil data mapel untuk dropdown 
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
        // Jika token kosong, generate otomatis
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
     * Hapus Data Ujian
     */
    public function destroy($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);

        // Hapus gambar soal terkait
        foreach ($exam->questions as $question) {
            if ($question->question_image && Storage::exists('public/' . $question->question_image)) {
                Storage::delete('public/' . $question->question_image);
            }
        }
        
        $exam->delete();

        return redirect()->route('cbt.index')->with('success', 'Data ujian beserta soal dan nilainya berhasil dihapus.');
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
     * Simpan Soal Manual (Support Multi-Tipe)
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

        // Logika Penyimpanan Berdasarkan Tipe
        if ($type === 'choice') {
            $request->validate(['correct_answer' => 'required']);
            $options = array_filter([
                'A' => $request->option_A, 'B' => $request->option_B, 
                'C' => $request->option_C, 'D' => $request->option_D, 'E' => $request->option_E
            ], fn($v) => !is_null($v) && $v !== '');
            $correctAnswer = $request->correct_answer;

        } elseif ($type === 'true_false') {
            $request->validate(['correct_answer' => 'required']);
            $options = ['A' => 'Benar', 'B' => 'Salah'];
            $correctAnswer = $request->correct_answer;

        } elseif ($type === 'matching') {
            $pairs = [];
            $correctMap = [];
            if($request->has('matches')) {
                foreach($request->matches as $match) {
                    if(!empty($match['left']) && !empty($match['right'])) {
                        $pairs[] = ['left' => $match['left'], 'right' => $match['right']];
                        $correctMap[$match['left']] = $match['right'];
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
     * Update Soal (Support Ganti Tipe)
     */
    public function updateQuestion(Request $request, $id)
    {
        $question = CbtQuestion::findOrFail($id);
        
        $request->validate([
            'question_text' => 'required',
            'score_weight' => 'required|integer|min:1',
        ]);

        // Handle Image
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

        // Gunakan tipe soal dari REQUEST jika ada perubahan, fallback ke tipe lama
        $type = $request->question_type ?? $question->question_type ?? 'choice';
        
        $options = [];
        $correctAnswer = ''; 

        // Logika Penyimpanan Berdasarkan Tipe
        if ($type === 'choice') {
            $options = array_filter([
                'A' => $request->option_A, 'B' => $request->option_B, 
                'C' => $request->option_C, 'D' => $request->option_D, 'E' => $request->option_E
            ], fn($v) => !is_null($v) && $v !== '');
            $correctAnswer = $request->correct_answer;

        } elseif ($type === 'true_false') {
            $options = ['A' => 'Benar', 'B' => 'Salah'];
            $correctAnswer = $request->correct_answer;

        } elseif ($type === 'matching') {
            $pairs = [];
            $correctMap = [];
            if($request->has('matches')) {
                foreach($request->matches as $match) {
                    if(!empty($match['left']) && !empty($match['right'])) {
                        $pairs[] = ['left' => $match['left'], 'right' => $match['right']];
                        $correctMap[$match['left']] = $match['right'];
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

    /**
     * Hapus Soal
     */
    public function destroyQuestion($id)
    {
        $question = CbtQuestion::findOrFail($id);
        
        if ($question->question_image && Storage::exists('public/' . $question->question_image)) {
            Storage::delete('public/' . $question->question_image);
        }
        
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }

    /**
     * Import Soal dari Excel
     */
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

    /**
     * Download Template
     */
    public function downloadTemplate()
    {
        return Excel::download(new QuestionTemplateExport, 'template_soal_ujian.xlsx');
    }

    /**
     * Monitoring Real-time (Initial Page Load)
     */
    public function monitoring($id)
    {
        $exam = CbtExam::withCount('questions')->findOrFail($id);

        // Ambil data untuk initial load
        $data = $this->getMonitoringDataInternal($id);

        return view('cbt.monitoring', [
            'exam' => $exam,
            'monitoringData' => $data['monitoringData'],
            'stats' => $data['stats']
        ]);
    }

    /**
     * API Endpoint untuk Live Monitoring (AJAX)
     */
    public function getMonitoringData($id)
    {
        $data = $this->getMonitoringDataInternal($id);
        return response()->json($data['monitoringData']);
    }

    /**
     * API Endpoint untuk Auto Rotate Token (JSON Response)
     */
    public function autoRotateToken($id)
    {
        try {
            $exam = CbtExam::findOrFail($id);
            $newToken = strtoupper(Str::random(5));
            $exam->update(['token' => $newToken]);
            
            return response()->json([
                'status' => 'success',
                'token' => $newToken
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Helper Function Private untuk mengambil data monitoring
     */
    private function getMonitoringDataInternal($id)
    {
        $exam = CbtExam::findOrFail($id);

        // Ambil siswa sesuai kelas ujian
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
                
                // Deteksi SEB
                if (Str::contains($session->user_agent, 'SEB') || Str::contains($session->user_agent, 'SafeExamBrowser')) {
                    $isSeb = true;
                }
                
                // Deteksi Mobile vs Desktop
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

        return [
            'monitoringData' => $monitoringData, 
            'stats' => $stats
        ];
    }

    // --- RECAP & ANALYTICS ---

    /**
     * Helper Private untuk mengambil data Rekap 
     */
     private function getRecapData($exam_id) 
    {
        // 1. Ambil Data Dasar Siswa & Nilai Akhir
        $results = DB::table('cbt_student_exams')
            ->join('students', 'cbt_student_exams.student_id', '=', 'students.id')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->where('cbt_student_exams.cbt_exam_id', $exam_id)
            ->where('cbt_student_exams.status', 'finished') 
            ->select(
                'cbt_student_exams.id as session_id',
                'cbt_student_exams.student_id',
                'cbt_student_exams.total_score', // Ini nilai yg tersimpan (bisa salah jika bug)
                'students.name as student_name',
                'students.student_id as student_nisn',
                'classes.name as class_name'
            )
            ->orderBy('cbt_student_exams.total_score', 'desc')
            ->get();

        // 2. Kalkulasi Ulang Nilai (Untuk memastikan nilai 0 bukan karena bug)
        foreach ($results as $row) {
            $correctCount = 0;
            $calculatedScore = 0; // Hitung ulang skor
            
            $answers = DB::table('cbt_student_answers')
                ->join('cbt_questions', 'cbt_student_answers.cbt_question_id', '=', 'cbt_questions.id')
                ->where('cbt_student_answers.cbt_student_exam_id', $row->session_id)
                ->get();

            foreach($answers as $ans) {
                $isCorrect = false;
                $type = $ans->question_type ?? 'choice';
                
                $studentAns = trim($ans->answer);
                $correctAns = trim($ans->correct_answer);

                if ($type == 'matching') {
                    $keyMap = json_decode($correctAns, true) ?? [];
                    $studentMap = json_decode($studentAns, true) ?? [];
                    if (is_array($keyMap)) ksort($keyMap);
                    if (is_array($studentMap)) ksort($studentMap);
                    if (!empty($keyMap) && $keyMap == $studentMap) $isCorrect = true;
                } elseif ($type == 'essay') {
                    if (!empty($correctAns) && strcasecmp($studentAns, $correctAns) == 0) $isCorrect = true;
                } else {
                    // PG & True/False (Case insensitive)
                    if (strcasecmp($studentAns, $correctAns) == 0) $isCorrect = true;
                }
                
                if($isCorrect) {
                    $correctCount++;
                    $calculatedScore += $ans->score_weight;
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
                'text' => strip_tags($q->question_text), 
                'correct_key' => $q->correct_answer,
                'correct_count' => 0,
                'wrong_count' => 0,
                'options' => ['A'=>0, 'B'=>0, 'C'=>0, 'D'=>0, 'E'=>0]
            ];
            if ($answers) {
                foreach($answers as $ans) {
                    $val = strtoupper($ans->answer);
                    if(isset($stats['options'][$val])) $stats['options'][$val]++;
                    if(strcasecmp($val, $q->correct_answer) == 0) $stats['correct_count']++;
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
            ->select('cbt_questions.*', 'cbt_student_answers.answer as student_answer')
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
                if($q->question_type == 'matching') return false;
                return strcasecmp($q->student_answer, $q->correct_answer) == 0;
            })->count(),
            'wrong'   => $answers->filter(function($q) {
                if($q->question_type == 'matching') return false;
                return strcasecmp($q->student_answer, $q->correct_answer) != 0 && !is_null($q->student_answer);
            })->count(),
        ];
        return view('cbt.result_detail', compact('exam', 'student', 'examSession', 'answers', 'stats'));
    }

    public function resetExam($exam_id, $student_id)
    {
        DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->delete(); 
        return back()->with('success', 'Status ujian siswa berhasil di-reset. Siswa dapat login kembali.');
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
        $sebConfig = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>originatorVersion</key>
    <string>SEB_Win_2.4.1</string>
    <key>startURL</key>
    <string>' . $startUrl . '</string>
    <key>sendBrowserExamKey</key>
    <true/>
    <key>examKeySalt</key>
    <data>' . base64_encode(random_bytes(32)) . '</data>
    <key>allowVirtualMachine</key>
    <true/>
    <key>allowScreenSharing</key>
    <true/>
    <key>allowQuit</key>
    <true/> 
    <key>showQuitButton</key>
    <true/>
    <key>quitPassword</key>
    <string>'. $quitPassword .'</string>
    <key>ignoreExitKeys</key>
    <true/>
    <key>showTaskBar</key>
    <true/>
    <key>showReloadButton</key>
    <true/>
    <key>showInputLanguageButton</key>
    <true/>
</dict>
</plist>';
        $fileName = Str::slug($exam->title) . '.seb';
        return response()->streamDownload(function () use ($sebConfig) {
            echo $sebConfig;
        }, $fileName, [
            'Content-Type' => 'application/seb',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ]);
    }

    /**
     * Fungsi Sync Gradebook 
     */
    public function syncToGradebook(Request $request, $id)
    {
        $exam = CbtExam::findOrFail($id);
        
        //Prioritaskan Exact Match dulu, karena dropdown pakai nama persis
        $subject = Subject::where('name', $exam->subject_name)->first();        
      
        if (!$subject) {
            $subject = Subject::where('name', 'like', '%' . $exam->subject_name . '%')->first();
        }

        if (!$subject) {
            return back()->with('error', 'Gagal: Mata Pelajaran "' . $exam->subject_name . '" tidak ditemukan di Data Master Mapel.');
        }

        $targetClasses = SchoolClass::where('name', 'like', $exam->class_level . '%')->get();
        if ($targetClasses->isEmpty()) {
            return back()->with('error', 'Gagal: Tidak ditemukan kelas untuk tingkat ' . $exam->class_level);
        }

        $countSynced = 0;
        DB::beginTransaction();
        try {
            foreach ($targetClasses as $class) {
                
                $assignment = LmsAssignment::updateOrCreate(
                    [
                        'class_id' => $class->id,
                        'subject_id' => $subject->id,
                        'title' => 'NILAI UJIAN: ' . $exam->title, 
                    ],
                    [
                        'teacher_id' => Auth::id(), 
                        'assignment_type' => 'exam', 
                        'description' => 'Nilai import otomatis dari CBT.',
                        'deadline' => $exam->end_time, 
                    ]
                );
                
                $studentResults = DB::table('cbt_student_exams')
                    ->join('students', 'cbt_student_exams.student_id', '=', 'students.id')
                    ->where('cbt_student_exams.cbt_exam_id', $exam->id)
                    ->where('cbt_student_exams.status', 'finished')
                    ->where('students.class_id', $class->id)
                    ->select('students.id as student_id', 'cbt_student_exams.total_score')
                    ->get();

                foreach ($studentResults as $res) {   
                    
                    // 1. Simpan ke LmsSubmission (Standard)
                    LmsSubmission::updateOrCreate(
                        [
                            'assignment_id' => $assignment->id,
                            'student_id' => $res->student_id
                        ],
                        [
                            'grade' => $res->total_score,
                            'status' => 'graded',
                            'submitted_at' => now(),
                            'teacher_feedback' => 'Sinkronisasi Otomatis dari CBT',
                        ]
                    );

                    // 2. Simpan ke LmsGrade (Backup / Legacy)                    
                    if (class_exists('App\Models\LmsGrade')) {
                         LmsGrade::updateOrCreate(
                            [
                                'lms_assignment_id' => $assignment->id,
                                'student_id' => $res->student_id
                            ],
                            [
                                'score' => $res->total_score,
                                'status' => 'graded',
                                'graded_at' => now(),
                            ]
                        );
                    }
                    
                    $countSynced++;
                }
            }
            DB::commit();
            
            if ($countSynced == 0) {
                 return back()->with('warning', "Proses selesai, namun tidak ada nilai yang diposting. Pastikan siswa sudah menyelesaikan ujian (Status: Selesai).");
            }

            return back()->with('success', "Berhasil memposting nilai ke Buku Nilai! ($countSynced siswa diperbarui).");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function results() { 
        $results = DB::table('cbt_student_exams')
            ->join('students', 'cbt_student_exams.student_id', '=', 'students.id')
            ->join('cbt_exams', 'cbt_student_exams.cbt_exam_id', '=', 'cbt_exams.id')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->where('cbt_student_exams.status', 'finished')
            ->select(
                'cbt_student_exams.*',
                'students.name as student_name',
                'classes.name as class_name',
                'cbt_exams.title as exam_title',
                'cbt_exams.subject_name'
            )
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