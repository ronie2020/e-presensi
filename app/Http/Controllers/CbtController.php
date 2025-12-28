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
use App\Exports\QuestionTemplateExport; 
use Barryvdh\DomPDF\Facade\Pdf; 

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
        return view('cbt.create');
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
        CbtExam::create($validated);

        return redirect()->route('cbt.index')->with('success', 'Jadwal ujian berhasil dibuat!');
    }

    /**
     * HAPUS DATA UJIAN (Jadwal, Soal, & Hasil)
     */
    public function destroy($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);

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
     * Simpan Soal Manual
     */
    public function storeQuestion(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'required',
            'option_A' => 'required',
            'option_B' => 'required',
            'correct_answer' => 'required|in:A,B,C,D,E',
            'score_weight' => 'required|integer|min:1',
            'question_image' => 'nullable|image|max:2048'
        ]);

        $exam = CbtExam::findOrFail($id);

        $imagePath = null;
        if ($request->hasFile('question_image')) {
            $imagePath = $request->file('question_image')->store('soal', 'public');
        }

        $options = [
            'A' => $request->option_A,
            'B' => $request->option_B,
            'C' => $request->option_C,
            'D' => $request->option_D,
            'E' => $request->option_E ?? null,
        ];

        $options = array_filter($options, fn($value) => !is_null($value) && $value !== '');

        CbtQuestion::create([
            'cbt_exam_id' => $exam->id,
            'question_text' => $request->question_text,
            'question_image' => $imagePath,
            'options' => $options, 
            'correct_answer' => $request->correct_answer,
            'score_weight' => $request->score_weight
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan!');
    }

    /**
     * UPDATE SOAL
     */
    public function updateQuestion(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'required',
            'option_A' => 'required',
            'option_B' => 'required',
            'correct_answer' => 'required|in:A,B,C,D,E',
            'score_weight' => 'required|integer|min:1',
            'question_image' => 'nullable|image|max:2048'
        ]);

        $question = CbtQuestion::findOrFail($id);

        if ($request->hasFile('question_image')) {
            if ($question->question_image && Storage::exists('public/' . $question->question_image)) {
                Storage::delete('public/' . $question->question_image);
            }
            $question->question_image = $request->file('question_image')->store('soal', 'public');
        }

        $options = [
            'A' => $request->option_A,
            'B' => $request->option_B,
            'C' => $request->option_C,
            'D' => $request->option_D,
            'E' => $request->option_E ?? null,
        ];
        $options = array_filter($options, fn($value) => !is_null($value) && $value !== '');

        $question->question_text = $request->question_text;
        $question->options = $options; 
        $question->correct_answer = $request->correct_answer;
        $question->score_weight = $request->score_weight;
        
        $question->save();

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
     * (UPDATED: Menggunakan QuestionTemplateExport)
     */
    public function downloadTemplate()
    {
        // Mendownload file dengan nama 'template_soal_ujian.xlsx'
        // Menggunakan class Export yang baru dibuat
        return Excel::download(new QuestionTemplateExport, 'template_soal_ujian.xlsx');
    }

    /**
     * Monitoring Real-time
     */
    public function monitoring($id)
    {
        $exam = CbtExam::withCount('questions')->findOrFail($id);

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
            $badgeColor = 'slate';
            $startTime = '-';
            $score = '-';
            $isActive = false;

            if ($session) {
                $startTime = \Carbon\Carbon::parse($session->created_at)->format('H:i');
                
                if ($session->status == 'finished') {
                    $status = 'Selesai';
                    $badgeColor = 'green';
                    $score = $session->total_score ?? 0;
                } else {
                    $status = 'Sedang Mengerjakan';
                    $badgeColor = 'blue';
                    $isActive = true; 
                    $score = $session->total_score ?? 0;
                }
            }

            return (object) [
                'id' => $student->id,
                'name' => $student->name,
                'class' => $student->schoolClass->name ?? '-',
                'status' => $status,
                'badge_color' => $badgeColor,
                'start_time' => $startTime,
                'score' => $score,
                'is_active' => $isActive,
            ];
        });

        $stats = [
            'total_students' => $students->count(),
            'working' => $monitoringData->where('status', 'Sedang Mengerjakan')->count(),
            'finished' => $monitoringData->where('status', 'Selesai')->count(),
            'not_started' => $monitoringData->where('status', 'Belum Mengerjakan')->count(),
        ];

        return view('cbt.monitoring', compact('exam', 'monitoringData', 'stats'));
    }

    /**
     * [BARU] Halaman Rekapitulasi Nilai (Report)
     */
    public function recap($id)
    {
        $exam = CbtExam::findOrFail($id);

        // 1. Ambil Kunci Jawaban
        $questions = DB::table('cbt_questions')
            ->where('cbt_exam_id', $id)
            ->pluck('correct_answer', 'id');

        // 2. Ambil Data Ujian Siswa
        $results = DB::table('cbt_student_exams')
            ->join('students', 'cbt_student_exams.student_id', '=', 'students.id')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->where('cbt_student_exams.cbt_exam_id', $id)
            ->where('cbt_student_exams.status', 'finished') 
            ->select(
                'cbt_student_exams.*',
                'students.name as student_name',
                'students.student_id as student_nisn',
                'classes.name as class_name'
            )
            ->orderBy('cbt_student_exams.total_score', 'desc')
            ->get();

        // 3. LOGIKA HITUNG MANUAL
        foreach ($results as $row) {
            $correct = 0;
            $wrong = 0;

            try {
                $studentAnswers = DB::table('cbt_student_answers')
                    ->where('cbt_student_exam_id', $row->id)
                    ->get();

                foreach ($studentAnswers as $ans) {
                    if (isset($questions[$ans->cbt_question_id])) {
                        if (strtoupper($ans->answer) == strtoupper($questions[$ans->cbt_question_id])) {
                            $correct++;
                        } else {
                            $wrong++;
                        }
                    }
                }
            } catch (\Exception $e) {}

            $row->correct_answers = $correct;
            $row->wrong_answers = $wrong;
        }

        // Hitung Statistik
        $stats = [
            'average' => $results->avg('total_score') ?? 0,
            'max_score' => $results->max('total_score') ?? 0,
            'min_score' => $results->min('total_score') ?? 0,
            'passed_count' => $results->where('total_score', '>=', $exam->passing_grade)->count(),
        ];

        return view('cbt.recap', compact('exam', 'results', 'stats'));
    }

    /**
     * [BARU] Export Excel / PDF
     */
    public function export($id, $type)
    {
        $exam = CbtExam::findOrFail($id);
        $fileName = 'REKAP_NILAI_' . \Illuminate\Support\Str::slug($exam->title) . '_' . date('Y-m-d');

        if ($type == 'excel') {
            $recapData = $this->recap($id);
            $results = $recapData->getData()['results']; 

            return Excel::download(new CbtScoreExport($results, $exam->passing_grade), $fileName . '.xlsx');
        } 
        
        if ($type == 'pdf') {
            return back()->with('error', 'Fitur PDF belum dikonfigurasi sepenuhnya. Gunakan Excel terlebih dahulu.');
        }

        return back()->with('error', 'Tipe file tidak valid.');
    }

    /**
     * Reset Ujian
     */
    public function resetExam($exam_id, $student_id)
    {
        DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->delete(); 

        return back()->with('success', 'Status ujian siswa berhasil di-reset. Siswa dapat login kembali.');
    }

    /**
     * Hasil Ujian (Dashboard Global - Opsional)
     */
    public function results()
    {
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

    /**
     * Generate dan Download File Config (.seb)
     */
    public function download_seb($id)
    {
        $exam = CbtExam::findOrFail($id);
        $startUrl = route('seb.login'); 

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
    <key>allowQuit</key>
    <true/>
    <key>ignoreExitKeys</key>
    <false/>
    <key>showTaskBar</key>
    <true/>
    <key>showReloadButton</key>
    <true/>
    <key>showQuitButton</key>
    <true/>
</dict>
</plist>';

        $fileName = \Illuminate\Support\Str::slug($exam->title) . '.seb';

        return response()->streamDownload(function () use ($sebConfig) {
            echo $sebConfig;
        }, $fileName, [
            'Content-Type' => 'application/seb',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ]);
    }

    /**
     * Refresh Token Manual
     */
    public function refreshToken($id)
    {
        $exam = CbtExam::findOrFail($id);
        $newToken = strtoupper(\Illuminate\Support\Str::random(5));
        
        $exam->update([
            'token' => $newToken
        ]);

        return back()->with('success', 'Token ujian berhasil diperbarui: ' . $newToken);
    }
}