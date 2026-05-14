<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPointHistory extends Model
{
    protected $fillable = ['student_id', 'academic_year', 'class_name', 'final_score', 'notes'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
