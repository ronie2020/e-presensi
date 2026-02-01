<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsSubmissionAnswer extends Model
{
    use HasFactory;

    // Arahkan ke nama tabel yang Anda buat di Migration
    protected $table = 'lms_submission_answers';

    // Izinkan kolom-kolom ini diisi massal
    protected $fillable = [
        'submission_id',
        'question_id',
        'answer_text',
        'points',
        'is_correct'
    ];

    public function submission()
    {
        return $this->belongsTo(LmsSubmission::class, 'submission_id');
    }

    public function question()
    {
        return $this->belongsTo(LmsQuizQuestion::class, 'question_id');
    }
}