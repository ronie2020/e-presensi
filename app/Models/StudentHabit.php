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
        
        // 1. Bangun & Mandi (Habit 1 & 2)
        'habit_1', 'habit_1_time', 'habit_1_note', // Bangun
        'habit_2', // Mandi & Rapi
        
        // 2. SHALAT (KOLOM BARU)
        'prayer_subuh', 
        'prayer_dhuha', 
        'prayer_dzuhur', 
        'prayer_ashar', 
        'prayer_maghrib', 
        'prayer_isya',
        
        // 3. Olahraga (Habit 3)
        'habit_3', 'habit_3_activity',
        
        // 4. Belajar (Habit 4)
        'habit_4', 'habit_4_subject',
        
        // 5. Makan Bergizi (Habit 5)
        'habit_5', 'habit_5_menu', 'mbg_taken_at',
        
        // 6. Bermasyarakat (Habit 6)
        'habit_6', 'habit_6_activity',
        
        // 7. Tidur (Habit 7)
        'habit_7', 'habit_7_time',
        
        // Bukti & Validasi
        'photo_path',
        'student_note',
        'teacher_id',
        'teacher_feedback',
        'validated_at'
    ];

    protected $casts = [
        'report_date' => 'date',
        'mbg_taken_at' => 'datetime',
        
        // Boolean Casting (Agar otomatis jadi true/false)
        'habit_1' => 'boolean',
        'habit_2' => 'boolean',
        'habit_3' => 'boolean',
        'habit_4' => 'boolean',
        'habit_5' => 'boolean',
        'habit_6' => 'boolean',
        'habit_7' => 'boolean',
        
        'prayer_subuh' => 'boolean',
        'prayer_dhuha' => 'boolean',
        'prayer_dzuhur' => 'boolean',
        'prayer_ashar' => 'boolean',
        'prayer_maghrib' => 'boolean',
        'prayer_isya' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}