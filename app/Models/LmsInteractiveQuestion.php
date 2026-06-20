<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsInteractiveQuestion extends Model
{
    use HasFactory;

    protected $table = 'lms_interactive_questions';
    protected $guarded = ['id'];

    public function assignment()
    {
        return $this->belongsTo(LmsAssignment::class, 'assignment_id');
    }

    public function options()
    {
        return $this->hasMany(LmsInteractiveOption::class, 'interactive_question_id');
    }
}