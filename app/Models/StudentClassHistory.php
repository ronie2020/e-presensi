<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClassHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi ke tabel classes
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    // Relasi balik ke tabel students
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}