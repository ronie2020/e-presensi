<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'school_class_id',
        'subject_id',
        'teacher_id',
        'day',
        'start_time',
        'end_time',
    ];

    /**
     * RELASI BARU (OPTIMASI):
     * Mengambil sesi mengajar KHUSUS HARI INI saja.
     * Digunakan untuk Eager Loading di Dashboard agar tidak query berulang.
     */
    public function todaySession()
    {
        return $this->hasOne(TeachingSession::class, 'schedule_id')
                    ->whereDate('date', Carbon::today());
    }

    /**
     * ACCESSOR: Membersihkan format start_time.
     */
    public function getCleanStartTimeAttribute()
    {
        $val = 0;
        if (str_contains($this->start_time, ':')) {
            $val = intval(Carbon::parse($this->start_time)->second); 
        }
        if ($val == 0) return intval($this->start_time);
        return $val;
    }

    /**
     * ACCESSOR: Membersihkan format end_time.
     */
    public function getCleanEndTimeAttribute()
    {
        $val = 0;
        if (str_contains($this->end_time, ':')) {
            $val = intval(Carbon::parse($this->end_time)->second);
        }
        if ($val == 0) return intval($this->end_time);
        return $val;
    }

    // --- RELASI BAWAAN ---

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function teachingSessions()
    {
        return $this->hasMany(TeachingSession::class, 'schedule_id');
    }
}