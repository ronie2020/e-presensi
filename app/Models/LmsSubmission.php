<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsSubmission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function assignment() {
        return $this->belongsTo(LmsAssignment::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}
