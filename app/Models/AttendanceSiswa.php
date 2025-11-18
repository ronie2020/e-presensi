<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceSiswa extends Model
{
    use HasFactory;

    /**
     * PENTING: Memberi tahu Laravel agar model ini menggunakan tabel 'attendances_siswa'
     * (Karena nama model 'AttendanceSiswa' tidak jamak menjadi 'AttendanceSiswas')
     */
    protected $table = 'attendances_siswa';
    
    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'student_id',
        'attendance_date',
        'type',
        'status',
        'time_in',
        'notes',
    ];

    /**
     * Hubungan: Satu data Absensi dimiliki oleh SATU Siswa.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}