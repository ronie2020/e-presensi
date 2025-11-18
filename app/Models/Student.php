<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; // Impor SoftDeletes

class Student extends Model
{
    use HasFactory, SoftDeletes; // Gunakan SoftDeletes

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'student_id',
        'name',
        'class_id',
        'rfid_id',
        'parent_wa_number',
    ];

    /**
     * Hubungan: Satu Siswa dimiliki oleh SATU Kelas.
     */
    public function schoolClass(): BelongsTo
    {
        // Kita gunakan nama 'schoolClass' karena 'class' adalah kata kunci PHP
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Hubungan: Satu Siswa memiliki BANYAK data Absensi.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(AttendanceSiswa::class, 'student_id');
    }
}