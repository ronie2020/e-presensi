<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiaisonBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'title',
        'message',
        'type', // info, warning, achievement, call
        'is_read_by_parent',
    ];

    // Relasi ke Siswa
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relasi ke Guru (User)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}