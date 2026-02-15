<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CbtQuestionBank;
use App\Models\CbtQuestion;
use App\Models\CbtExam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; 

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
            
        return view('cbt.bank.index', compact('banks'));
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
     * Kelola Isi Soal di dalam Bank
     */
    public function manage($id)
    {
        $bank = CbtQuestionBank::with('questions')->findOrFail($id);
        return view('cbt.bank.manage', compact('bank'));
    }

    /**
     * [UPDATE] Simpan Soal Baru ke Bank Soal (Support Multi-Tipe)
     */
    public function storeQuestion(Request $request, $bank_id)
    {
        // 1. Validasi Umum
        $request->validate([
            'question_type' => 'required|in:choice,essay,true_false,matching',
            'question_text' => 'required',
            'score_weight' => 'required|integer|min:1',
            'question_image' => 'nullable|image|max:2048'
        ]);

        // 2. Handle Upload Gambar
        $imagePath = null;
        if ($request->hasFile('question_image')) {
            $imagePath = $request->file('question_image')->store('soal', 'public');
        }

        $type = $request->question_type;
        $options = [];
        $correctAnswer = '';

        // 3. Logika Data Berdasarkan Tipe Soal
        if ($type === 'choice') {
            $request->validate(['correct_answer' => 'required']);
            // Filter opsi kosong
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
            // Format JSON untuk Menjodohkan
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

        // 4. Simpan ke Database
        CbtQuestion::create([
            'cbt_question_bank_id' => $bank_id,
            'cbt_exam_id' => null, // Masuk ke Bank, bukan Ujian langsung
            'question_type' => $type,
            'question_text' => $request->question_text,
            'question_image' => $imagePath,
            'options' => $options, // Otomatis jadi JSON via Casts di Model
            'correct_answer' => $correctAnswer,
            'score_weight' => $request->score_weight
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan ke Bank!');
    }

    /**
     * Hapus Bank Soal
     */
    public function destroy($id)
    {
        $bank = CbtQuestionBank::findOrFail($id);
        $bank->delete();
        return back()->with('success', 'Bank soal berhasil dihapus.');
    }

    /**
     * Hapus Soal di dalam Bank
     */
    public function destroyQuestion($id)
    {
        $q = CbtQuestion::findOrFail($id);
        if($q->cbt_question_bank_id) {
            // Hapus gambar jika ada
            if ($q->question_image && Storage::exists('public/' . $q->question_image)) {
                Storage::delete('public/' . $q->question_image);
            }
            $q->delete();
            return back()->with('success', 'Soal dihapus dari bank.');
        }
        return back()->with('error', 'Soal tidak valid.');
    }

    // =================================================================
    //  FITUR INTEGRASI UJIAN <-> BANK SOAL
    // =================================================================

    /**
     * [TOMBOL: Simpan ke Bank]
     * Mengcopy semua soal DARI Ujian KE Bank Soal
     */
    public function storeFromExam(Request $request, $exam_id)
    {
        $exam = CbtExam::with('questions')->findOrFail($exam_id);

        if ($exam->questions->count() == 0) {
            return back()->with('error', 'Tidak ada soal di ujian ini untuk disimpan.');
        }

        DB::beginTransaction();
        try {
            $targetBankId = null;

            // Opsi 1: Buat Bank Baru
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
            // Opsi 2: Gabung ke Bank Lama
            else {
                $request->validate(['bank_id' => 'required']);
                $targetBankId = $request->bank_id;
            }

            // Proses Duplikasi Soal
            $count = 0;
            foreach ($exam->questions as $q) {
                // Replicate menduplikasi model data tanpa menyimpannya
                $newQ = $q->replicate();
                
                // Ubah relasi: Dari Ujian -> Ke Bank
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

    /**
     * [TOMBOL: Ambil Bank]
     * Mengcopy semua soal DARI Bank Soal KE Ujian
     */
    public function importToExam(Request $request, $exam_id)
    {
        $request->validate(['bank_id' => 'required']);
        
        $exam = CbtExam::findOrFail($exam_id);
        $bank = CbtQuestionBank::with('questions')->findOrFail($request->bank_id);

        if ($bank->questions->count() == 0) {
            return back()->with('error', 'Bank soal ini kosong.');
        }

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($bank->questions as $q) {
                $newQ = $q->replicate();
                
                // Ubah relasi: Dari Bank -> Ke Ujian
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
}