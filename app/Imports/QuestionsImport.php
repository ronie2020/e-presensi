<?php

namespace App\Imports;

use App\Models\CbtQuestion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToCollection, WithHeadingRow
{
    protected $target_id;
    protected $is_bank;

    // Tambahkan parameter $is_bank dengan default false
    public function __construct($target_id, $is_bank = false)
    {
        $this->target_id = $target_id;
        $this->is_bank = $is_bank;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Jika kolom 'soal' kosong pada baris ini, lewati (skip)
            if (!isset($row['soal']) || empty(trim($row['soal']))) {
                continue;
            }

            // Menyusun opsi jawaban ke dalam bentuk array (JSON)
            $options = [
                'A' => $row['opsi_a'] ?? '',
                'B' => $row['opsi_b'] ?? '',
                'C' => $row['opsi_c'] ?? '',
                'D' => $row['opsi_d'] ?? '',
                'E' => $row['opsi_e'] ?? '',
            ];

            // Membersihkan nilai yang null agar tidak tersimpan sebagai null di array
            $options = array_filter($options, function($value) {
                return !is_null($value) && $value !== '';
            });

            // Menyimpan baris soal ke dalam Database sesuai target (Bank atau Ujian)
            CbtQuestion::create([
                'cbt_exam_id'          => $this->is_bank ? null : $this->target_id,
                'cbt_question_bank_id' => $this->is_bank ? $this->target_id : null,
                'question_type'        => 'choice', // Secara default dari excel kita anggap Pilihan Ganda
                'question_text'        => $row['soal'],
                'options'              => $options, // Tergantung dari konfigurasi $casts di model CbtQuestion (pastikan ada 'options' => 'array')
                'correct_answer'       => strtoupper(trim($row['jawaban'] ?? 'A')),
                'score_weight'         => (int) ($row['bobot'] ?? 2),
            ]);
        }
    }
}