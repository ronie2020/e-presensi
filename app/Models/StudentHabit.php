<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentHabit extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'report_date',
        'habit_1', 'habit_1_time', 'habit_1_note',
        'habit_2',
        'habit_3', 'habit_3_activity',
        'habit_4', 'habit_4_subject',
        'habit_5', 'habit_5_menu',
        'habit_6', 'habit_6_activity',
        'habit_7', 'habit_7_time',
        'photo_path',
        'student_note',
        'teacher_id',
        'teacher_feedback',
        'validated_at'
    ];

    protected $casts = [
        'report_date' => 'date',
        'habit_1' => 'boolean',
        'habit_2' => 'boolean',
        'habit_3' => 'boolean',
        'habit_4' => 'boolean',
        'habit_5' => 'boolean',
        'habit_6' => 'boolean',
        'habit_7' => 'boolean',
    ];

    // Relasi ke Siswa
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relasi ke Guru (Validator)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}