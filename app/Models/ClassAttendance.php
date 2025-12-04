<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassAttendance extends Model
{
    use HasFactory;

    // Nama tabel di database (sesuai migrasi Anda)
    protected $table = 'class_attendances';

    // Kolom yang boleh diisi
    protected $guarded = ['id'];

    // Relasi ke Sesi Mengajar (Jurnal Guru)
    public function teachingSession()
    {
        return $this->belongsTo(TeachingSession::class);
    }

    // Relasi ke Siswa
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}