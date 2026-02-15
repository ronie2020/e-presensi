<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtQuestionBank extends Model
{
    use HasFactory;

    protected $table = 'cbt_question_banks';

    protected $fillable = [
        'code',
        'title',
        'subject_name',
        'class_level',
        'author_id',
        'is_active',
    ];

    // Relasi: Satu Bank punya banyak Soal
    public function questions()
    {
        return $this->hasMany(CbtQuestion::class, 'cbt_question_bank_id');
    }

    // Relasi ke Pembuat (Guru)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}