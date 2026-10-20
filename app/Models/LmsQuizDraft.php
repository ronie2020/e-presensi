<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsQuizDraft extends Model
{
    use HasFactory;

    protected $table = 'lms_quiz_drafts';

    protected $fillable = [
        'student_id',
        'assignment_id',
        'question_id',
        'answer_text',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function assignment()
    {
        return $this->belongsTo(LmsAssignment::class, 'assignment_id');
    }

    public function question()
    {
        return $this->belongsTo(LmsQuizQuestion::class, 'question_id');
    }
}
