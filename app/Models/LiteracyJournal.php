<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiteracyJournal extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'title',
        'author',
        'pages_read',
        'summary',
        'proof_image',
        'verified_at'
    ];

    protected $dates = ['verified_at'];

    // Relasi ke Siswa
   public function student()
    {
        return $this->belongsTo(Student::class)->withTrashed();
    }
}