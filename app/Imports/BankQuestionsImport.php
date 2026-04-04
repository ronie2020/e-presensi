<?php

namespace App\Imports;

use App\Models\CbtQuestion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BankQuestionsImport implements ToModel, WithHeadingRow
{
    protected $bank_id;

    public function __construct($bank_id)
    {
        $this->bank_id = $bank_id;
    }

    public function model(array $row)
    {
        // Abaikan baris jika kolom 'soal' kosong
        if (!isset($row['soal']) || trim($row['soal']) === '') {
            return null;
        }

        return new CbtQuestion([
            'cbt_question_bank_id' => $this->bank_id,
            'cbt_exam_id' => null, // Harus null karena masuk ke Bank Soal
            'question_type' => 'choice',
            'question_text' => $row['soal'], 
            'options' => [
                'A' => $row['opsi_a'] ?? '',
                'B' => $row['opsi_b'] ?? '',
                'C' => $row['opsi_c'] ?? '',
                'D' => $row['opsi_d'] ?? '',
                'E' => $row['opsi_e'] ?? null,
            ],
            'correct_answer' => strtoupper(trim($row['kunci'] ?? 'A')),
            'score_weight' => isset($row['bobot']) ? (int) $row['bobot'] : 2,
            'tags' => $row['materi_kd'] ?? null,
        ]);
    }

     /**
     * Proses Import Soal dari Excel ke Bank Soal
     */
    public function importQuestions(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new BankQuestionsImport($id), $request->file('file'));
            return back()->with('success', 'Soal-soal dari Excel berhasil diimport ke Bank Soal!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport soal: ' . $e->getMessage());
        }
    }

    /**
     * Download Template Excel untuk Bank Soal
     */
    public function downloadTemplate()
    {
        $headers = ['soal', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e', 'kunci', 'bobot', 'materi_kd'];
        
        $callback = function() use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            // Contoh baris
            fputcsv($file, ['Ibukota Indonesia adalah?', 'Jakarta', 'Bandung', 'Surabaya', 'Medan', '', 'A', '2', 'Geografi']);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=template_bank_soal.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
    }
}