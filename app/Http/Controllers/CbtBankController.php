<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CbtQuestionBank;
use App\Models\CbtQuestion;
use App\Models\CbtExam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk Transaction

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
     * Simpan Bank Soal Baru
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
        return view('cbt.bank.questions', compact('bank'));
    }

    /**
     * Hapus Bank Soal
     */
    public function destroy($id)
    {
        $bank = CbtQuestionBank::findOrFail($id);
        $bank->delete();
        return back()->with('success', 'Bank Soal berhasil dihapus.');
    }

    /**
     * Simpan Soal Baru ke Bank Soal
     */
    public function storeQuestion(Request $request, $bank_id)
    {
        $request->validate([
            'question_text' => 'required',
            'correct_answer' => 'required',
            'score_weight' => 'required|integer',
        ]);

        $imagePath = $request->hasFile('question_image') 
            ? $request->file('question_image')->store('soal', 'public') 
            : null;
        
        $options = array_filter([
            'A' => $request->option_A,
            'B' => $request->option_B, 
            'C' => $request->option_C, 
            'D' => $request->option_D
        ], fn($v) => !is_null($v));

        CbtQuestion::create([
            'cbt_question_bank_id' => $bank_id,
            'cbt_exam_id' => null, // Karena masuk ke bank, bukan ujian spesifik
            'question_text' => $request->question_text,
            'question_image' => $imagePath,
            'options' => $options,
            'correct_answer' => $request->correct_answer,
            'score_weight' => $request->score_weight
        ]);

        return back()->with('success', 'Soal ditambahkan ke Bank!');
    }

    /**
     * FITUR INTI: Tarik Soal dari Bank ke Ujian
     */
    public function importToExam(Request $request, $exam_id)
    {
        $request->validate(['bank_id' => 'required|exists:cbt_question_banks,id']);
        
        $exam = CbtExam::findOrFail($exam_id);
        $bank = CbtQuestionBank::with('questions')->findOrFail($request->bank_id);
        
        if ($bank->questions->count() == 0) {
            return back()->with('error', 'Bank soal ini masih kosong!');
        }

        $count = 0;
        foreach($bank->questions as $q) {
            // Duplikasi soal ke tabel questions dengan exam_id baru
            $newQ = $q->replicate();
            $newQ->cbt_question_bank_id = null; // Putus hubungan dari bank
            $newQ->cbt_exam_id = $exam->id;    // Hubungkan ke ujian ini
            $newQ->save();
            $count++;
        }

        return back()->with('success', "Berhasil menyalin $count soal dari Bank ke Ujian ini.");
    }

    /**
     * [BARU] EKSPOR SOAL DARI UJIAN KE BANK
     * Menyimpan soal yang sudah ada di ujian ke dalam Bank Soal (Baru/Lama)
     */
    public function storeFromExam(Request $request, $exam_id)
    {
        $request->validate([
            'mode' => 'required|in:new,existing',
            'new_title' => 'required_if:mode,new|nullable|string|max:255',
            'bank_id' => 'required_if:mode,existing|nullable|exists:cbt_question_banks,id',
        ]);

        $exam = CbtExam::with('questions')->findOrFail($exam_id);

        if ($exam->questions->count() == 0) {
            return back()->with('error', 'Tidak ada soal di ujian ini untuk disimpan.');
        }

        DB::beginTransaction();
        try {
            $targetBankId = null;

            // Jika buat bank baru
            if ($request->mode === 'new') {
                $newBank = CbtQuestionBank::create([
                    'code' => strtoupper(Str::random(6)),
                    'title' => $request->new_title,
                    'subject_name' => $exam->subject_name,
                    'class_level' => $exam->class_level,
                    'author_id' => Auth::id(),
                ]);
                $targetBankId = $newBank->id;
            } else {
                // Jika ke bank yang sudah ada
                $targetBankId = $request->bank_id;
            }

            // Proses Copy Soal
            $count = 0;
            foreach ($exam->questions as $q) {
                $newQ = $q->replicate();
                $newQ->cbt_exam_id = null; // Lepas dari ujian
                $newQ->cbt_question_bank_id = $targetBankId; // Masukkan ke bank target
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
}