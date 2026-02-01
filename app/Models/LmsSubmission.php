<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsSubmission extends Model
{
    use HasFactory;

    protected $table = 'lms_submissions';

    protected $fillable = [
        'assignment_id',
        'student_id',
        'file_path',       
        'link_url',        
        'student_note',    
        'grade',           
        'teacher_feedback',
        'status',          
        'submitted_at',    
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'grade' => 'integer',
    ];

    public function assignment()
    {
        return $this->belongsTo(LmsAssignment::class, 'assignment_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * [PERBAIKAN PENTING]
     * Relasi ini mengarah ke model LmsSubmissionAnswer yang baru.
     * Tanpa ini, halaman penilaian guru akan kosong.
     */
    public function answers()
    {
        return $this->hasMany(LmsSubmissionAnswer::class, 'submission_id');
    }
}