<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'cbt_exam_id', 'question_text', 'question_image',
        'options', 'correct_answer', 'score_weight'
    ];

    // Menyimpan opsi sebagai array JSON otomatis
    protected $casts = [
        'options' => 'array', 
    ];

    public function exam() {
        return $this->belongsTo(CbtExam::class, 'cbt_exam_id');
    }
}