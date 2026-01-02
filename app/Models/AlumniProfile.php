<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumniProfile extends Model
{
    use HasFactory;

    // Izinkan semua kolom diisi
    protected $guarded = ['id'];

    /**
     * Relasi ke Siswa
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}