<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StudentPermit extends Model
{
    use HasFactory;

    protected $table = 'student_permits';

    protected $fillable = [
        'student_id',
        'pic_teacher_id',
        'reason_category',
        'notes',
        'time_out',
        'time_in',
        'duration_minutes',
        'status',
    ];

    protected $casts = [
        'time_out' => 'datetime',
        'time_in' => 'datetime',
        'duration_minutes' => 'integer', // Pastikan jadi integer
    ];

    /**
     * [PENTING] Tambahkan ini agar atribut komputasi (Accessor) 
     * ikut terbawa saat model di-return sebagai JSON ke Frontend.
     */
    protected $appends = [
        'minutes_elapsed',
        'is_overdue'
    ];

    // --- RELATIONSHIPS ---

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'pic_teacher_id');
    }

    // --- ACCESSORS (COMPUTED ATTRIBUTES) ---

    /**
     * Mengambil durasi menit.
     * Jika sudah kembali: ambil dari database.
     * Jika belum: hitung selisih waktu sekarang dengan waktu keluar.
     */
    public function getMinutesElapsedAttribute()
    {
        // Jika data lama atau sudah kembali, gunakan yang tersimpan di DB
        if ($this->status === 'RETURNED' && !is_null($this->duration_minutes)) {
            return (int) $this->duration_minutes;
        }

        // Safety check jika time_out belum ada
        if (!$this->time_out) {
            return 0;
        }

        // Hitung selisih real-time
        return (int) abs($this->time_out->diffInMinutes(Carbon::now()));
    }

    /**
     * Menentukan apakah siswa telat berdasarkan kategori alasan.
     */
    public function getIsOverdueAttribute()
    {
        if ($this->status === 'RETURNED') return false;

        // Tentukan batas waktu maksimal (dalam menit) per kategori
        $maxTime = match($this->reason_category) {
            'Toilet' => 10,
            'Barang Tertinggal' => 15,
            'UKS' => 45,
            'Panggilan Guru' => 60,
            default => 60
        };

        return $this->minutes_elapsed > $maxTime;
    }
}