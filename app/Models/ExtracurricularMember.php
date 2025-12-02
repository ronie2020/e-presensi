<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtracurricularMember extends Model
{
    protected $guarded = [];

    public function student()
    {
        // PERBAIKAN SAMA:
        // Gunakan 'id' sebagai parameter ketiga agar relasi terbaca dengan benar.
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }
}