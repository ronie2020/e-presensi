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
     */
    protected $table = 'attendances_siswa';
    
    /**
     * Kolom yang boleh diisi secara massal (Mass Assignment).
     * activity WAJIB ada di sini agar filter Dhuha/Dhuhur berfungsi.
     */
    protected $fillable = [
        'student_id',
        'attendance_date',
        'type',
        'activity', // <--- INI YANG TADINYA HILANG
        'status',
        'time_in',
        'time_out', // <--- Tambahkan ini juga untuk jam pulang
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