<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\CbtExam;
use App\Models\CbtQuestion;
use App\Models\CbtQuestionBank;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionsImport;
use App\Exports\QuestionTemplateExport;
use Illuminate\Support\Str;

class CbtQuestionController extends Controller
{
    public function manageQuestions($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);   
        $totalPoints = $exam->questions->sum('score_weight');
        
        // Ambil data bank soal yang relevan untuk dikirim ke view
        $banks = CbtQuestionBank::where('class_level', $exam->class_level)
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
            'tags' => $request->tags
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
            'tags' => $request->tags
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

    public function bulkDelete(Request $request, $exam_id)
    {
        if (!$request->question_ids) return back()->with('error', 'Tidak ada soal yang dipilih.');
        
        $ids = explode(',', $request->question_ids);
        $questions = CbtQuestion::whereIn('id', $ids)->get();

        foreach ($questions as $question) {
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
                    if(isset($pair['left_image']) && Storage::exists('public/' . $pair['left_image'])) Storage::delete('public/' . $pair['left_image']);
                    if(isset($pair['right_image']) && Storage::exists('public/' . $pair['right_image'])) Storage::delete('public/' . $pair['right_image']);
                }
            }
            
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

    public function exportQuestions($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);
        $questions = $exam->questions;

        $headers = ['soal', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e', 'kunci', 'bobot', 'materi_kd'];

        $callback = function() use ($questions, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            foreach ($questions as $q) {
                $opts = is_string($q->options) ? json_decode($q->options, true) : ($q->options ?? []);
                
                fputcsv($file, [
                    strip_tags($q->question_text), 
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
}