<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'student_id',
        'name',
        'class_id', // Pastikan nama kolom di DB Anda 'class_id' atau 'school_class_id'
        'rfid_id',
        'parent_wa_number',
    ];

    /**
     * Hubungan: Satu Siswa dimiliki oleh SATU Kelas.
     */
    public function schoolClass(): BelongsTo
    {
        // Perhatikan parameter kedua ('class_id' atau 'school_class_id') harus sesuai DB Anda
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Hubungan: Satu Siswa memiliki BANYAK data Absensi.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(AttendanceSiswa::class, 'student_id');
    }

    /**
     * --- BAGIAN INI YANG HILANG SEBELUMNYA ---
     * Hubungan: Satu Siswa memiliki BANYAK Catatan Disiplin.
     */
    public function disciplineRecords(): HasMany
    {
        return $this->hasMany(DisciplineRecord::class, 'student_id');
    }
}