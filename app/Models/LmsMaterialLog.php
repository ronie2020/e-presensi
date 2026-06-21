<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmsMaterialLog extends Model
{
    protected $fillable = [
        'student_id',
        'material_id',
        'time_spent_seconds', // Tambahan untuk melacak durasi baca/nonton
    ];

    public function student() 
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function material() 
    {
        return $this->belongsTo(LmsMaterial::class, 'material_id');
    }
}
