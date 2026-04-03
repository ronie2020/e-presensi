<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
         'type',
        'student_id',
        'name_manual',
        'title',
        'level',
        'date',
        'description',
        'photo_path',
        'video_link',
        'certificate_path'
    ];

    /**
     * Relasi ke Siswa (Jika tipe = Siswa)
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Helper untuk mendapatkan Nama yang Berprestasi
     */
    public function getAchieverNameAttribute()
    {
        if ($this->type === 'Siswa' && $this->student) {
            return $this->student->name;
        }
        return $this->name_manual;
    }
}