<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtExam extends Model
{
    use HasFactory;

    // KUNCI SOLUSI: Gunakan guarded agar SEMUA kolom otomatis diizinkan masuk ke database
    protected $guarded = ['id'];

    // Tetap pertahankan casts agar tipe datanya benar
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
        'randomize_questions' => 'boolean', 
        'randomize_options' => 'boolean',   
    ];

    public function questions() {
        return $this->hasMany(CbtQuestion::class);
    }

    public function studentExams() {
        return $this->hasMany(CbtStudentExam::class);
    }
   
    public function event()
    {
        return $this->belongsTo(CbtEvent::class, 'cbt_event_id');
    }
}