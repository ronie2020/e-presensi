<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamadanLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'is_fasting',
        'prayers',
        'sunnah_deeds',
        'tadarus_surah',
        'tadarus_ayah',
        'murojaah_surah',
    ];

    /**
     * Casting kolom JSON ke array secara otomatis.
     */
    protected $casts = [
        'prayers' => 'array',
        'sunnah_deeds' => 'array',
        'date' => 'date',
        'is_fasting' => 'boolean',
    ];

    /**
     * Relasi ke model Student.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}