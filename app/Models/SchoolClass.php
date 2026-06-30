<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolClass extends Model
{
    use HasFactory;

    /**
     * PENTING: Memberi tahu Laravel agar model ini menggunakan tabel 'classes'
     */
    protected $table = 'classes';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'name',
        'homeroom_teacher_id',
    ];

    /**
     * Hubungan: Satu Kelas memiliki BANYAK Siswa.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /**
     * Hubungan: Satu Kelas memiliki SATU Wali Kelas (User).
     */
    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }

    /** * Digunakan sebagai syarat wajib sebelum generate jadwal.
     */
    public function teachingLoads(): HasMany
    {
        return $this->hasMany(TeachingLoad::class, 'class_id');
    }

    /**
     * [FITUR TIMETABLE] Hubungan: Satu Kelas memiliki banyak Jadwal Pelajaran (Timetable final).
     */
    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class, 'class_id');
    }
}