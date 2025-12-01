<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeRecord extends Model
{
    protected $fillable = [
        'student_id',
        'academic_year',
        'semester',
        'class_name',
        'report_date',
        'absent_s',
        'absent_i',
        'absent_a',
        'notes',
        'is_promoted',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function items()
    {
        return $this->hasMany(GradeItem::class);
    }

    // Tambahan: Relasi ke Ekskul
    public function extracurriculars()
    {
        return $this->hasMany(ExtracurricularRecord::class);
    }
}