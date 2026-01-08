<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'student_id',
        'category',
        'incident_date',
        'location',
        'description',
        'evidence_path',
        'is_anonymous',
        'status',
    ];

    /**
     * Relasi ke Model Student.
     * Pastikan Model Student ada di App\Models\Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}