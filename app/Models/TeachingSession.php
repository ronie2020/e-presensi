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

    // 2. --- ACCESSORS UNTUK STATISTIK KEHADIRAN (LOGIKA LAMA DIPERTAHANKAN) ---
    
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
        // Mendukung panggilan baru maupun panggilan lama
        return $this->timetable ? $this->timetable->subject : ($this->schedule ? $this->schedule->subject : null);
    }

    public function getSchoolClassAttribute()
    {
        // Mendukung panggilan baru maupun panggilan lama
        return $this->timetable ? $this->timetable->studentClass : ($this->schedule ? $this->schedule->studentClass : null);
    }

    //------------------ schedule_id (ALIAS RELASI LAMA) ------------------//
    // Relasi ke Jadwal (Logika lama dihidupkan kembali sebagai Alias)
    // Trik: Kita arahkan ke \App\Models\Timetable agar aplikasi tidak error mencari Schedule.php
    public function schedule()
    {
        return $this->belongsTo(\App\Models\Timetable::class, 'schedule_id');
    }
    //------------------ schedule_id ------------------//

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

    // Relasi ke Jadwal Pelajaran (Timetable Baru)
    public function timetable()
    {
        return $this->belongsTo(\App\Models\Timetable::class, 'schedule_id');
    }
}