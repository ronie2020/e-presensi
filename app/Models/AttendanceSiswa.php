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
     * Semua kolom yang di-create atau di-update dari Controller WAJIB ada di sini.
     */
    protected $fillable = [
        'student_id',
        'attendance_date', // Pastikan sesuai dengan controller (bukan 'date')
        'type',            // Masuk, Pulang, Dhuha, dll
        'activity',        // Kegiatan spesifik (misal: shalat dhuha)
        'status',          // Hadir, Terlambat, dll
        'time_in',
        'time_out',
        
        // --- PERBAIKAN: Tambahkan Kolom Lokasi ---
        'lat_in',          
        'long_in',         
        'lat_out',         
        'long_out',
        
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