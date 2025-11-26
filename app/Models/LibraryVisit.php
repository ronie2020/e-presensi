<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryVisit extends Model
{
    protected $fillable = ['student_id', 'date', 'time'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}