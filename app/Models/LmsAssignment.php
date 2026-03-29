<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsAssignment extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    
    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass() {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function submissions() {
        return $this->hasMany(LmsSubmission::class, 'assignment_id');
    }
    
    // RELASI BARU KE SOAL
    public function questions() {
        return $this->hasMany(LmsQuizQuestion::class, 'assignment_id');
    }

    public function isSubmittedBy($studentId) {
        return $this->submissions()->where('student_id', $studentId)->exists();
    }
}