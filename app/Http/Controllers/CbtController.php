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
        return view('cbt.edit', compact('exam'));
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
     * Update Soal
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

        $options = [
            'A' => $request->option_A,
            'B' => $request->option_B,
            'C' => $request->option_C,
            'D' => $request->option_D,
            'E' => $request->option_E ?? null,
        ];
        $options = array_filter($options, fn($value) => !is_null($value) && $value !== '');

        $question->update([
            'question_text' => $request->question_text,
            'options' => $options, 
            'correct_answer' => $request->correct_answer,
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

        // Ambil data untuk initial load (agar halaman tidak kosong saat pertama dibuka)
        $data = $this->getMonitoringDataInternal($id);

        return view('cbt.monitoring', [
            'exam' => $exam,
            'monitoringData' => $data['monitoringData'],
            'stats' => $data['stats']
        ]);
    }

    /**
     * API Endpoint untuk Live Monitoring (AJAX)
     * Dipanggil oleh Alpine.js via fetch()
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
            $score = '-'; // Default string '-' jika belum ada data
            $isActive = false;
            
            // [UPDATE] Deteksi SEB dan Device
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
                'is_seb' => $isSeb,        // Data baru untuk UI
                'device' => $deviceType,   // Data baru untuk UI
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

    // --- RECAP & ANALYTICS (OPTIMIZED) ---

    /**
     * Helper Private untuk mengambil data Rekap (Menghindari Duplikasi Code)     
     */
    private function getRecapData($exam_id) 
    {
        // 1. Ambil Data Dasar Siswa & Nilai Akhir (Single Query)
        $results = DB::table('cbt_student_exams')
            ->join('students', 'cbt_student_exams.student_id', '=', 'students.id')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->where('cbt_student_exams.cbt_exam_id', $exam_id)
            ->where('cbt_student_exams.status', 'finished') 
            ->select(
                'cbt_student_exams.id as session_id',
                'cbt_student_exams.student_id',
                'cbt_student_exams.total_score',
                'students.name as student_name',
                'students.student_id as student_nisn',
                'classes.name as class_name'
            )
            ->orderBy('cbt_student_exams.total_score', 'desc')
            ->get();

        // 2. Ambil Statistik Benar/Salah       
        $sessionIds = $results->pluck('session_id');
        
        $statsAnswers = DB::table('cbt_student_answers')
            ->join('cbt_questions', 'cbt_student_answers.cbt_question_id', '=', 'cbt_questions.id')
            ->whereIn('cbt_student_answers.cbt_student_exam_id', $sessionIds)
            ->select(
                'cbt_student_answers.cbt_student_exam_id',
                DB::raw('SUM(CASE WHEN UPPER(cbt_student_answers.answer) = UPPER(cbt_questions.correct_answer) THEN 1 ELSE 0 END) as correct'),
                DB::raw('SUM(CASE WHEN UPPER(cbt_student_answers.answer) != UPPER(cbt_questions.correct_answer) THEN 1 ELSE 0 END) as wrong')
            )
            ->groupBy('cbt_student_answers.cbt_student_exam_id')
            ->get()
            ->keyBy('cbt_student_exam_id');

        // 3. Gabungkan Data 
        foreach ($results as $row) {
            $stat = $statsAnswers->get($row->session_id);
            $row->correct_answers = $stat ? $stat->correct : 0;
            $row->wrong_answers = $stat ? $stat->wrong : 0;
        }

        return $results;
    }

    /**
     * Halaman Rekapitulasi Nilai (Report)
     */
    public function recap($id)
    {
        $exam = CbtExam::findOrFail($id);
        
        // Gunakan fungsi helper optimized
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
     * Export Excel / PDF
     */
    public function export($id, $type)
    {
        $exam = CbtExam::findOrFail($id);
        
        // Gunakan fungsi helper optimized (sama dengan recap)
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
        
        // PDF View
        return view('cbt.pdf_export', compact('exam', 'results', 'stats'));
    }

    /**
     * ANALISIS BUTIR SOAL
     * Menampilkan statistik tingkat kesukaran dan daya beda soal.
     */
    public function analysis($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);
        
        // 1. Ambil semua ID sesi ujian yang SUDAH SELESAI
        $finishedSessionIds = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $id)
            ->where('status', 'finished')
            ->pluck('id');
            
        $totalStudents = $finishedSessionIds->count();

        // 2. Ambil semua jawaban siswa untuk ujian ini (Batch Query)     
        $allAnswers = DB::table('cbt_student_answers')
            ->whereIn('cbt_student_exam_id', $finishedSessionIds)
            ->get()
            ->groupBy('cbt_question_id'); 

        // 3. Olah data statistik per butir soal
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
                    
                    // Hitung distribusi opsi (A, B, C, D) untuk melihat pengecoh
                    if(isset($stats['options'][$val])) {
                        $stats['options'][$val]++;
                    }
                    
                    // Hitung benar/salah
                    if($val == strtoupper($q->correct_answer)) {
                        $stats['correct_count']++;
                    } else {
                        $stats['wrong_count']++;
                    }
                }
            }

            // Hitung Tingkat Kesukaran (P)
            // Rumus: Jumlah Benar / Total Peserta           
            $p = $totalStudents > 0 ? ($stats['correct_count'] / $totalStudents) : 0;
            
            // Kategori Kesukaran
            $difficulty = 'Sedang';
            $badgeColor = 'bg-blue-100 text-blue-700';
            
            if ($p > 0.70) {
                $difficulty = 'Mudah';
                $badgeColor = 'bg-emerald-100 text-emerald-700';
            } elseif ($p < 0.30) {
                $difficulty = 'Sukar';
                $badgeColor = 'bg-rose-100 text-rose-700';
            }

            $stats['difficulty_label'] = $difficulty;
            $stats['difficulty_badge'] = $badgeColor;
            $stats['difficulty_index'] = round($p * 100); 

            return (object) $stats;
        });

        return view('cbt.analysis', compact('exam', 'analysis', 'totalStudents'));
    }

    /**
     * Halaman Detail Jawaban Siswa
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
            ->select(
                'cbt_questions.question_text',
                'cbt_questions.question_image',
                'cbt_questions.options', 
                'cbt_questions.correct_answer',
                'cbt_questions.score_weight',
                'cbt_student_answers.answer as student_answer'
            )
            ->get();

        $answers->transform(function ($item) {
            $opts = json_decode($item->options, true);        
            $item->option_A = $opts['A'] ?? '';
            $item->option_B = $opts['B'] ?? '';
            $item->option_C = $opts['C'] ?? '';
            $item->option_D = $opts['D'] ?? '';
            return $item;
        });

        $stats = [
            'correct' => $answers->filter(fn($q) => strtoupper($q->student_answer) == strtoupper($q->correct_answer))->count(),
            'wrong'   => $answers->filter(fn($q) => strtoupper($q->student_answer) != strtoupper($q->correct_answer) && !is_null($q->student_answer))->count(),
        ];

        return view('cbt.result_detail', compact('exam', 'student', 'examSession', 'answers', 'stats'));
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
     * Refresh Token Manual
     */
    public function refreshToken($id)
    {
        $exam = CbtExam::findOrFail($id);
        $newToken = strtoupper(Str::random(5));
        $exam->update(['token' => $newToken]);
        return back()->with('success', 'Token ujian berhasil diperbarui: ' . $newToken);
    }

    /**
     * Generate dan Download File Config (.seb)
     * REVISI: Mengaktifkan Password untuk Keluar (Exit)
     */
    public function download_seb($id)
    {
        $exam = CbtExam::findOrFail($id);
        $startUrl = route('seb.login'); // Pastikan route ini benar untuk login siswa
        
        // --- PASSWORD KELUAR SEB ---
        // Ganti '12345' dengan password yang Anda inginkan
        $quitPassword = '12345'; 

        // --- UPDATE PENGATURAN XML SEB ---
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
    
    <!-- PENGATURAN TAMPILAN -->
    <key>allowVirtualMachine</key>
    <true/>
    <key>allowScreenSharing</key>
    <true/>
    
    <!-- PENGATURAN KELUAR (EXIT) DENGAN PASSWORD -->
    <key>allowQuit</key>
    <true/> 
    <key>showQuitButton</key>
    <true/>
    <key>quitPassword</key>
    <string>'. $quitPassword .'</string>
    
    <!-- KEAMANAN LAINNYA -->
    <key>ignoreExitKeys</key>
    <true/> <!-- Disable Alt+F4 dll, paksa pakai tombol Quit yang ada passwordnya -->
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
     * Sync Nilai CBT ke Buku Nilai (LMS)
     */
    public function syncToGradebook(Request $request, $id)
    {
        $exam = CbtExam::findOrFail($id);

        $subject = Subject::where('name', 'like', '%' . $exam->subject_name . '%')->first();

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
                $assignment = LmsAssignment::firstOrCreate(
                    [
                        'class_id' => $class->id,
                        'subject_id' => $subject->id,
                        'title' => 'NILAI UJIAN: ' . $exam->title, 
                    ],
                    [
                        'assignment_type' => 'exam', 
                        'description' => 'Nilai import otomatis dari CBT.',
                        'due_date' => $exam->end_time,
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
                    $countSynced++;
                }
            }
            
            DB::commit();
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
    /**
     * Halaman Pilih Kelas untuk Cetak Kartu
     */
    public function cardIndex()
    {
        // Ambil daftar kelas untuk dropdown filter
        $classes = SchoolClass::orderBy('name')->get();
        return view('cbt.cards.index', compact('classes'));
    }

    /**
     * Proses Cetak Kartu (Tampilan Print)
     */
     /**
     * Proses Cetak Kartu (Tampilan Print)
     */
    public function printCards(Request $request)
    {
        $query = Student::with('schoolClass')->orderBy('name');

        // Filter Sesuai Mode yang Dipilih (Lebih Strict)
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
            // PERBAIKAN: Gunakan return response() bukan return echo
            return response("<script>alert('Tidak ada siswa ditemukan pada kriteria tersebut.'); window.close();</script>");
        }

        // Generate URL Login untuk QR Code
        foreach($students as $student) {
            $student->login_url = route('student.login', ['username' => $student->student_id]);
        }

        return view('cbt.cards.print', compact('students'));
    }

    /**
     * [BARU] AMBIL FOTO PROCTORING (AJAX)
     */
    public function getStudentPhotos($exam_id, $student_id)
    {
        // Cari sesi ujian siswa
        $session = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->first();

        if (!$session) {
            return response()->json([]);
        }

        // Ambil foto-foto
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