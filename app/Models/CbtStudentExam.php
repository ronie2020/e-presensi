<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtStudentExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'cbt_exam_id', 'student_id', 'started_at',
        'finished_at', 'total_score', 'status',
        'ip_address', 'user_agent'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function exam() {
        return $this->belongsTo(CbtExam::class, 'cbt_exam_id');
    }

    // Menggunakan relasi ke User karena auth()->id() adalah user
    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function answers() {
        return $this->hasMany(CbtStudentAnswer::class);
    }
}