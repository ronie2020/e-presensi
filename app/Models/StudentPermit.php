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
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'pic_teacher_id');
    }

    /**
     * PERBAIKAN: Gunakan (int) casting agar angka bulat bersih
     */
    public function getMinutesElapsedAttribute()
    {
        if ($this->status === 'RETURNED') {
            return (int) $this->duration_minutes;
        }

        // Hitung selisih menit, ambil nilai absolut, lalu bulatkan ke integer
        return (int) abs($this->time_out->diffInMinutes(Carbon::now()));
    }

    public function getIsOverdueAttribute()
    {
        if ($this->status === 'RETURNED') return false;

        $maxTime = match($this->reason_category) {
            'Toilet' => 10,
            'Barang Tertinggal' => 15,
            'UKS' => 45,
            default => 60
        };

        return $this->minutes_elapsed > $maxTime;
    }
}