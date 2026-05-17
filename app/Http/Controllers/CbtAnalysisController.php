<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CbtExam;
use App\Models\Student;
use App\Models\Subject;
use App\Models\LmsAssignment;
use App\Models\LmsSubmission;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CbtScoreExport;
use Illuminate\Support\Str;

class CbtAnalysisController extends Controller
{
    /**
     * Tampilkan Hasil Ujian Global (Semua Ujian)
     */
    public function results() 
    { 
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

    /**
     * Tampilkan Rekap Nilai per Ujian
     */
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

    /**
     * Export Hasil Ujian (PDF/Excel)
     */
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

    /**
     * Tampilkan Detail Jawaban per Siswa
     */
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

    /**
     * Tampilkan Analisis Butir Soal
     */
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
            
        $tagAnalysis = []; 
            
        $analysis = $exam->questions->map(function($q) use ($allAnswers, $totalStudents, &$tagAnalysis) {
            $answers = $allAnswers->get($q->id);
            $stats = [
                'id' => $q->id,
                'type' => $q->question_type ?? 'choice', 
                'text' => strip_tags($q->question_text), 
                'correct_key' => $q->correct_answer,
                'tags' => $q->tags, 
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
                    if(isset($ans->score) && $ans->score > 0) $isCorrect = true;
                    elseif(strcasecmp($ans->answer, $q->correct_answer) == 0) $isCorrect = true;

                    if($isCorrect) $stats['correct_count']++;
                    else $stats['wrong_count']++;
                }
            }

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
    
    /**
     * Cetak Laporan Analisis Butir Soal (PDF)
     */
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
                    if(isset($ans->score) && $ans->score > 0) $isCorrect = true;
                    elseif(strcasecmp($ans->answer, $q->correct_answer) == 0) $isCorrect = true;

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

    /**
     * Berikan Penilaian (Grade) Manual pada Soal Essay via AJAX
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

        // Kalkulasi ulang total score siswa
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

    /**
     * Post Nilai CBT ke Gradebook LMS (Buku Nilai Utama)
     */
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

                $assignment = LmsAssignment::updateOrCreate(
                    [
                        'class_id' => $classId,
                        'subject_id' => $subject->id,
                        'title' => $exam->title, 
                        'assignment_type' => 'quiz', 
                    ],
                    [
                        'description' => 'Nilai otomatis diposting dari hasil Ujian CBT.',                       
                        'teacher_id' => null, 
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

    /**
     * Logika Internal Ambil Data Rekap (Private)
     */
    private function getRecapData($exam_id) 
    {
        $selects = [
            'cbt_student_exams.id as session_id',
            'cbt_student_exams.student_id',
            'cbt_student_exams.total_score', 
            'students.name as student_name',
            'students.student_id as student_nisn',
            'students.class_id', 
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
}