<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SchoolClass;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_of_week', 
        'timeslot_id', 
        'class_id', 
        'teacher_id', 
        'subject_id', 
        'status'
    ];

    /**
     * Relasi ke Jam Pelajaran
     */
    public function timeslot()
    {
        return $this->belongsTo(Timeslot::class, 'timeslot_id');
    }

    /**
     * Relasi ke Kelas
     */
    public function studentClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Relasi ke Guru
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Relasi ke Mata Pelajaran
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * BARU: Relasi ke Jurnal Mengajar
     */
    public function teachingSessions()
    {
        return $this->hasMany(TeachingSession::class, 'schedule_id');
    }

    /**
     * BARU: Relasi untuk mengecek apakah hari ini guru sudah membuka jurnal
     */
    public function todaySession()
    {
        return $this->hasOne(TeachingSession::class, 'schedule_id')->whereDate('date', \Carbon\Carbon::today('Asia/Jakarta'));
    }

    public function teachingLoad()
    {
        return $this->belongsTo(TeachingLoad::class, 'teaching_load_id');
    }
}