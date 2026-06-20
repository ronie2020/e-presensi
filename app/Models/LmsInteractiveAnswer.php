<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsInteractiveAnswer extends Model
{
    use HasFactory;

    protected $table = 'lms_interactive_answers';
    protected $guarded = ['id'];

    public function submission()
    {
        return $this->belongsTo(LmsSubmission::class, 'submission_id');
    }

    public function question()
    {
        return $this->belongsTo(LmsInteractiveQuestion::class, 'interactive_question_id');
    }

    public function selectedOption()
    {
        return $this->belongsTo(LmsInteractiveOption::class, 'selected_option_id');
    }
}