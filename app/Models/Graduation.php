<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Graduation extends Model
{
    use HasFactory;

    // Pastikan field ini sesuai dengan kolom di database
    protected $fillable = [
        'student_id',
        'academic_year',
        'status',
        'announcement_date',
        'notes',
        'skl_number',
        'average_score',
    ];

    protected $casts = [
        'announcement_date' => 'datetime',
        'average_score' => 'float',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}