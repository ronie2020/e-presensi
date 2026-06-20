<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsInteractiveOption extends Model
{
    use HasFactory;

    protected $table = 'lms_interactive_options';
    protected $guarded = ['id'];

    public function question()
    {
        return $this->belongsTo(LmsInteractiveQuestion::class, 'interactive_question_id');
    }
}