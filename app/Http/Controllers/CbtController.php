<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CbtExam;
use App\Models\CbtQuestion; 
use Maatwebsite\Excel\Facades\Excel; // Tambahkan import Excel
use App\Imports\QuestionsImport;     // Tambahkan import Class Import tadi

class CbtController extends Controller
{
    // ... (Kode sebelumnya: index, create, store, manageQuestions TETAP SAMA) ...

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

    public function create()
    {
        return view('cbt.create');
    }

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

    public function manageQuestions($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);
        return view('cbt.manage_questions', compact('exam'));
    }

    public function storeQuestion(Request $request, $id)
    {
        // ... (Kode simpan manual tetap sama) ...
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

    public function destroyQuestion($id)
    {
        $question = CbtQuestion::findOrFail($id);
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }

    // --- TAMBAHAN BARU: LOGIKA IMPORT EXCEL ---

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
        // Membuat CSV sederhana on-the-fly
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=template_soal_cbt.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['soal', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'jawaban', 'bobot'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Contoh Data Dummy
            fputcsv($file, ['Siapa presiden pertama RI?', 'Soekarno', 'Suharto', 'Habibie', 'Jokowi', 'A', '2']);
            fputcsv($file, ['Ibu kota Jawa Barat adalah?', 'Bandung', 'Jakarta', 'Surabaya', 'Semarang', 'A', '2']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Placeholder
    public function questionBank() { return "Halaman Bank Soal (Coming Soon)"; }
    public function monitoring($exam_id) { return "Halaman Monitoring Ujian (Coming Soon)"; }
    public function results() { return "Halaman Hasil (Coming Soon)"; }
}