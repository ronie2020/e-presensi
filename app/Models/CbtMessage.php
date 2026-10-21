<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'cbt_exam_id',
        'student_id',
        'sender_id',
        'message',
        'type',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function exam() {
        return $this->belongsTo(CbtExam::class, 'cbt_exam_id');
    }

    public function student() {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
