<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtQuestion extends Model
{
    use HasFactory;

    // UPDATE: Tambahkan 'cbt_question_bank_id' ke fillable
    protected $fillable = [
        'cbt_exam_id', 
        'cbt_question_bank_id', 
        'question_type',
        'question_text', 
        'question_image',
        'options', 
        'correct_answer', 
        'score_weight'
    ];

    protected $casts = [
        'options' => 'array', 
    ];

    public function exam() {
        return $this->belongsTo(CbtExam::class, 'cbt_exam_id');
    }

    // UPDATE: Tambahkan Relasi ke Bank Soal
    public function bank() {
        return $this->belongsTo(CbtQuestionBank::class, 'cbt_question_bank_id');
    }
}