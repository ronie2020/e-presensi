<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtracurricularMember extends Model
{
    protected $guarded = [];

    public function student()
    {
        // Sesuaikan local key 'student_id' jika di tabel students primary key-nya bukan 'student_id'
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }
}