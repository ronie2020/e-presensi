<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingSession extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'teaching_sessions';

    // Izinkan semua kolom diisi (atau gunakan fillable)
    protected $guarded = ['id'];

    // Relasi ke Jadwal
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // Relasi ke Guru
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Relasi ke Absensi Siswa di sesi ini
    public function attendances()
    {
        return $this->hasMany(ClassAttendance::class);
    }
}