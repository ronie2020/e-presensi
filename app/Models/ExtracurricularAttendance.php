<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtracurricularAttendance extends Model
{
    protected $guarded = [];

    public function student()
    {
        // PERBAIKAN: 
        // Parameter ke-3 diubah dari 'student_id' menjadi 'id'.
        // Ini memberitahu Laravel: "Cocokkan kolom student_id di tabel ini dengan kolom ID di tabel students"
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }
}