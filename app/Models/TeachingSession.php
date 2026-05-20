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

    // 1. Casting tipe data tanggal agar otomatis dikonversi menjadi objek Carbon
    protected $casts = [
        'date' => 'date',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    // 2. --- ACCESSORS UNTUK STATISTIK KEHADIRAN ---
    
    // Menghitung Jumlah Terlambat (Memprioritaskan hasil withCount dari Controller)
    public function getJmlTelatAttribute()
    {
        return $this->attributes['terlambat'] ?? $this->attendances()->whereIn('status', ['late', 'Terlambat'])->count();
    }

    // Menghitung Jumlah Alpha
    public function getJmlAlphaAttribute()
    {
        return $this->attributes['alpha'] ?? $this->attendances()->whereIn('status', ['alpha', 'Alfa', 'Alpha'])->count();
    }

    // Menghitung Total Hadir (Hadir + Terlambat)
    public function getTotalHadirAttribute()
    {
        $hadir = $this->attributes['hadir'] ?? $this->attendances()->whereIn('status', ['present', 'Hadir'])->count();
        return $hadir + $this->jml_telat;
    }

    // 3. --- ACCESSORS UNTUK RELASI BERSARANG (NESTED) ---
    // Memungkinkan pemanggilan $history->subject->name dan $history->schoolClass->name langsung di Blade
    
    public function getSubjectAttribute()
    {
        return $this->schedule ? $this->schedule->subject : null;
    }

    public function getSchoolClassAttribute()
    {
        return $this->schedule ? $this->schedule->schoolClass : null;
    }

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