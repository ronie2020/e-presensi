<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtracurricularAttendance extends Model
{
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }
}