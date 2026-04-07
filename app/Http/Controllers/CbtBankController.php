<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CbtQuestionBank;
use App\Models\CbtQuestion;
use App\Models\CbtExam;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; 
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BankQuestionsImport;

class CbtBankController extends Controller
{
    /**
     * Tampilkan Daftar Bank Soal
     */
    public function index()
    {
        $banks = CbtQuestionBank::withCount('questions')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $subjects = Subject::orderBy('order', 'asc')->get();
            
        return view('cbt.bank.index', compact('banks', 'subjects'));
    }

    /**
     * Simpan Bank Soal Baru (Header)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_name' => 'required|string',
            'class_level' => 'required',
        ]);

        CbtQuestionBank::create([
            'code' => strtoupper(Str::random(6)),
            'title' => $request->title,
            'subject_name' => $request->subject_name,
            'class_level' => $request->class_level,
            'author_id' => Auth::id(),
        ]);

        return back()->with('success', 'Bank Soal berhasil dibuat!');
    }

    /**
     * Update Bank Soal (Header)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_name' => 'required|string',
            'class_level' => 'required',
        ]);

        $bank = CbtQuestionBank::findOrFail($id);
        
        $bank->update([
            'title' => $request->title,
            'subject_name' => $request->subject_name,
            'class_level' => $request->class_level,
        ]);

        return back()->with('success', 'Informasi Bank Soal berhasil diperbarui!');
    }

    /**
     * Kelola Isi Soal di dalam Bank
     */
    public function manage($id)
    {
        $bank = CbtQuestionBank::with('questions')->findOrFail($id);
        $totalPoints = $bank->questions->sum('score_weight');       

        return view('cbt.bank.questions', compact('bank', 'totalPoints'));
    }

    /**
     * Simpan Soal Baru ke Bank Soal (Support Multi-Tipe & Gambar Opsi)
     */
    public function storeQuestion(Request $request, $bank_id)
    {
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
            'cbt_question_bank_id' => $bank_id,
            'cbt_exam_id' => null, 
            'question_type' => $type,
            'question_text' => $request->question_text,
            'question_image' => $imagePath,
            'options' => $options, 
            'correct_answer' => $correctAnswer,
            'score_weight' => $request->score_weight,
            'tags' => $request->tags // <--- TAMBAHKAN INI
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan ke Bank!');
    }

     /**
     * Update Soal (Support Hapus/Ganti Gambar Opsi)
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

        $type = $request->question_type ?? $question->question_type;
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

                    // Logika replace/hapus Gambar Kiri
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

                    // Logika replace/hapus Gambar Kanan
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
            'question_image' => $question->question_image, 
            'options' => $options,
            'correct_answer' => $correctAnswer,
            'score_weight' => $request->score_weight,
            'tags' => $request->tags // <--- TAMBAHKAN INI
        ]);
        
        return back()->with('success', 'Soal berhasil diperbarui!');
    }

    /**
     * Hapus Bank Soal
     */
    public function destroy($id)
    {
        $bank = CbtQuestionBank::with('questions')->findOrFail($id);
        
        // Bersihkan storage gambar saat Bank Soal dihapus
        foreach ($bank->questions as $question) {
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

        $bank->delete();
        return back()->with('success', 'Bank soal beserta seluruh soal di dalamnya berhasil dihapus.');
    }

    /**
     * Hapus Soal di dalam Bank
     */
    public function destroyQuestion($id)
    {
        $q = CbtQuestion::findOrFail($id);
        if($q->cbt_question_bank_id) {
            
            if ($q->question_image && Storage::exists('public/' . $q->question_image)) {
                Storage::delete('public/' . $q->question_image);
            }
            
            $opts = is_string($q->options) ? json_decode($q->options, true) : ($q->options ?? []);
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

            $q->delete();
            return back()->with('success', 'Soal dihapus dari bank.');
        }
        return back()->with('error', 'Soal tidak valid.');
    }
    
     // --- FITUR BARU: BULK DELETE & BULK WEIGHT UNTUK BANK SOAL ---
    public function bulkDelete(Request $request, $bank_id)
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

        return back()->with('success', count($ids) . ' soal berhasil dihapus dari Bank Soal.');
    }

    public function bulkWeight(Request $request, $bank_id)
    {
        if (!$request->question_ids || !$request->score_weight) return back()->with('error', 'Data tidak lengkap.');
        
        $ids = explode(',', $request->question_ids);
        CbtQuestion::whereIn('id', $ids)->update(['score_weight' => $request->score_weight]);
        
        return back()->with('success', 'Bobot ' . count($ids) . ' soal di Bank Soal berhasil diubah.');
    }
    // --- END FITUR BARU ---

    public function storeFromExam(Request $request, $exam_id)
    {
        $exam = CbtExam::with('questions')->findOrFail($exam_id);

        if ($exam->questions->count() == 0) {
            return back()->with('error', 'Tidak ada soal di ujian ini untuk disimpan.');
        }

        DB::beginTransaction();
        try {
            $targetBankId = null;
            if ($request->mode === 'new') {
                $request->validate(['new_title' => 'required']);
                
                $newBank = CbtQuestionBank::create([
                    'code' => strtoupper(Str::random(6)),
                    'title' => $request->new_title,
                    'subject_name' => $exam->subject_name,
                    'class_level' => $exam->class_level,
                    'author_id' => Auth::id(),
                ]);
                $targetBankId = $newBank->id;
            } 
            else {
                $request->validate(['bank_id' => 'required']);
                $targetBankId = $request->bank_id;
            }

            $count = 0;
            foreach ($exam->questions as $q) {               
                $newQ = $q->replicate();
                $newQ->cbt_exam_id = null; 
                $newQ->cbt_question_bank_id = $targetBankId;                
                $newQ->save();
                $count++;
            }

            DB::commit();
            return back()->with('success', "Berhasil menyimpan $count soal ke Bank Soal.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

     public function importToExam(Request $request, $exam_id)
    {
        $request->validate([
            'bank_id' => 'required',
            'import_mode' => 'nullable|string',
            'selected_question_ids' => 'nullable|array'
        ]);
        
        $exam = CbtExam::findOrFail($exam_id);
        $bank = CbtQuestionBank::with('questions')->findOrFail($request->bank_id);

        if ($bank->questions->count() == 0) {
            return back()->with('error', 'Bank soal ini kosong.');
        }

        // LOGIKA BARU: Cek mode import, apakah sebagian atau semua
        $questionsToImport = collect();
        if ($request->import_mode === 'partial' && !empty($request->selected_question_ids)) {
            // Ambil hanya soal yang dicentang
            $questionsToImport = $bank->questions->whereIn('id', $request->selected_question_ids);
        } else {
            // Ambil seluruh soal dari bank (Default lama)
            $questionsToImport = $bank->questions;
        }

        if ($questionsToImport->count() == 0) {
            return back()->with('error', 'Tidak ada soal valid yang dipilih untuk diimpor.');
        }

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($questionsToImport as $q) {
                $newQ = $q->replicate();
                $newQ->cbt_question_bank_id = null;
                $newQ->cbt_exam_id = $exam->id;
                
                $newQ->save();
                $count++;
            }

            DB::commit();
            return back()->with('success', "Berhasil mengambil $count soal dari Bank.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengambil: ' . $e->getMessage());
        }
    }

    public function printQuestions($id)
    {
        $bank = CbtQuestionBank::with('questions')->findOrFail($id);
        $title = $bank->title;
        $subject = $bank->subject_name;
        $info = "Kelas: " . $bank->class_level . " | Kode Bank: " . $bank->code;
        $questions = $bank->questions;
        $type = 'Gudang Bank Soal';
        
        return view('cbt.print_questions', compact('title', 'subject', 'info', 'questions', 'type'));
    }

      /**
     * Proses Import Soal dari Excel
     */
    public function importQuestions(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        try {
            Excel::import(new BankQuestionsImport($id), $request->file('file'));
            return back()->with('success', 'Soal-soal dari Excel berhasil diimport ke Bank Soal!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport soal: ' . $e->getMessage());
        }
    }

    /**
     * Download Template Import Soal
     */
    public function downloadTemplate()
    {
        $headers = ['soal', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e', 'kunci', 'bobot', 'materi_kd'];
        
        $callback = function() use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            // Contoh baris pengisian
            fputcsv($file, ['Contoh soal: Siapa penemu lampu?', 'Edison', 'Tesla', 'Newton', 'Einstein', '', 'A', '2', 'IPA']);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=template_bank_soal.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }

     /**
     * Export Soal dari Bank Soal ke CSV/Excel
     */
    public function exportQuestions($id)
    {
        $bank = CbtQuestionBank::with('questions')->findOrFail($id);
        $questions = $bank->questions;

        $headers = ['soal', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e', 'kunci', 'bobot', 'materi_kd'];

        $callback = function() use ($questions, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            foreach ($questions as $q) {
                // Ambil opsi dari json/array
                $opts = is_string($q->options) ? json_decode($q->options, true) : ($q->options ?? []);
                
                fputcsv($file, [
                    $q->question_text,
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

        $fileName = 'Export_Bank_' . Str::slug($bank->title) . '_' . date('Ymd_His') . '.csv';

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
        $bank = CbtQuestionBank::with('questions')->findOrFail($id);
        return view('cbt.bank.preview', compact('bank'));
    }

    /**
     * Export ke format Microsoft Word (.doc)
     */
    public function exportWord($id)
    {
        $bank = CbtQuestionBank::with('questions')->findOrFail($id);
        $fileName = 'Bank_Soal_' . Str::slug($bank->title) . '_' . date('Ymd') . '.doc';

        $headers = [
            "Content-type" => "application/vnd.ms-word",
            "Content-Disposition" => "attachment;Filename={$fileName}",
            "Pragma" => "no-cache",
            "Expires" => "0"
        ];

        return response()->view('cbt.bank.export_word', compact('bank'))->withHeaders($headers);
    }
}