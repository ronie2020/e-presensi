<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingLoad extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id', 
        'subject_id', 
        'class_id', 
        'hours_per_week'
    ];

    /**
     * Relasi ke Guru (Tabel Users)
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Relasi ke Mata Pelajaran (Tabel Subjects)
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Relasi ke Kelas (Tabel Classes)
     */
    public function studentClass()
    {
        // Sesuaikan 'Classes::class' jika nama model untuk tabel classes Anda berbeda
        return $this->belongsTo(\App\Models\SchoolClass::class, 'class_id');
    }
}