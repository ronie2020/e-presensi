<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtracurricularRecord extends Model
{
    // Izinkan pengisian massal untuk kolom-kolom ini
    protected $fillable = [
        'grade_record_id',
        'activity_name',
        'score',
        'description',
    ];

    // Relasi balik ke GradeRecord (Induk Nilai)
    public function gradeRecord()
    {
        return $this->belongsTo(GradeRecord::class);
    }
}