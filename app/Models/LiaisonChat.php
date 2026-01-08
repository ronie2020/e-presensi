<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiaisonChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'message',
        'sender_type',
        'is_read'
    ];

    // Relasi (Opsional, untuk eager loading)
    public function student() { return $this->belongsTo(Student::class); }
    public function teacher() { return $this->belongsTo(User::class); }
}