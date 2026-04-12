<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'bk_category_id',
        'teacher_id',
        'initial_message',
        'response_message',
        'scheduled_at',
        'method',
        'status',
        'is_system_generated',
        // --- Kolom khusus untuk fitur Rating & Feedback ---
        'rating',           // Nilai bintang 1-5
        'student_feedback', // Ulasan tekstual dari siswa
        'feedback_at',      // Waktu siswa memberikan ulasan
    ];

      protected $casts = [
        'scheduled_at' => 'datetime',
        'feedback_at'  => 'datetime',
    ];

    // Relasi ke Siswa (Yang curhat)
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relasi ke Guru BK (Yang menangani)   
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Relasi ke Kategori Masalah
    public function category()
    {
        return $this->belongsTo(BkCategory::class, 'bk_category_id');
    }

    // Relasi ke Catatan Hasil (Jurnal)
    public function record()
    {
        return $this->hasOne(BkRecord::class);
    }
     
    // Relasi ke Chat BK (Pesan interaksi online).     
    public function chats()
    {
        return $this->hasMany(BkChat::class, 'bk_session_id');
    }
}