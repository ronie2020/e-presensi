<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'subject_name', 'class_level',
        'start_time', 'end_time', 'duration_minutes',
        'passing_grade', 'is_active', 'token'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function questions() {
        return $this->hasMany(CbtQuestion::class);
    }

    public function studentExams() {
        return $this->hasMany(CbtStudentExam::class);
    }
}