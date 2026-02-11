<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamadanLog extends Model
{
    use HasFactory;

    protected $table = 'ramadan_logs';

    protected $fillable = [
        'student_id',
        'date',
        'is_fasting',
        'prayers',       // Disimpan sebagai array/json
        'sunnah_deeds',  // Disimpan sebagai array/json
        'tadarus_surah',
        'tadarus_ayah',
        'murojaah_surah',
        
        // [TAMBAHAN PENTING] Kolom Khusus Jumat
        'friday_khotib',
        'friday_summary',

        // [TAMBAHAN PENTING] Kolom Penilaian Guru
        'teacher_id',
        'teacher_score',
        'teacher_note',
        'teacher_verified_at',

        // === [FIX] TAMBAHKAN INI AGAR DATA KULTUM TERSIMPAN ===
        'kultum_penceramah',
        'kultum_summary',
    ];

    /**
     * Casting kolom JSON ke array secara otomatis.
     */
    protected $casts = [
        'is_fasting' => 'boolean',
        'prayers' => 'array',
        'sunnah_deeds' => 'array',
        'date' => 'date',
        'teacher_verified_at' => 'datetime',
    ];

    /**
     * Relasi ke model Student.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Relasi ke Guru (Penilai)
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id'); 
    }
}