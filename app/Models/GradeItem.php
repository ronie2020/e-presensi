<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeItem extends Model
{
    // Izinkan kolom-kolom ini diisi secara massal
    protected $fillable = [
        'grade_record_id',
        'subject_id',
        'score',
        'predicate',
        'description',
    ];

    // Relasi balik ke Induk (GradeRecord)
    public function gradeRecord()
    {
        return $this->belongsTo(GradeRecord::class);
    }

    // Relasi ke Mata Pelajaran
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}