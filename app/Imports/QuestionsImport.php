<?php

namespace App\Imports;

use App\Models\CbtQuestion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToModel, WithHeadingRow
{
    protected $examId;

    public function __construct($examId)
    {
        $this->examId = $examId;
    }

    /**
     * Mapping data dari baris Excel ke Database
     */
    public function model(array $row)
    {
        // Pastikan baris memiliki isi (validasi sederhana)
        if (!isset($row['soal']) || !isset($row['jawaban'])) {
            return null;
        }

        // Format Opsi menjadi JSON
        $options = [
            'A' => $row['opsi_a'],
            'B' => $row['opsi_b'],
            'C' => $row['opsi_c'] ?? null,
            'D' => $row['opsi_d'] ?? null,
            'E' => $row['opsi_e'] ?? null,
        ];

        // Bersihkan opsi kosong
        $options = array_filter($options, fn($value) => !is_null($value) && $value !== '');

        return new CbtQuestion([
            'cbt_exam_id' => $this->examId,
            'question_text' => $row['soal'],
            'question_image' => null, // Import Excel tidak mendukung gambar langsung
            'options' => $options,
            'correct_answer' => strtoupper($row['jawaban']), // Pastikan huruf besar
            'score_weight' => isset($row['bobot']) ? $row['bobot'] : 2,
        ]);
    }
}