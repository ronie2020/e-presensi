<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtStudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'cbt_student_exam_id', 
        'cbt_question_id', 
        'answer', 
        'is_correct'
    ];

    /**
     * Relasi ke Soal
     * Penting agar $ans->question bisa diakses di Controller saat finish
     */
    public function question() {
        return $this->belongsTo(CbtQuestion::class, 'cbt_question_id');
    }
}