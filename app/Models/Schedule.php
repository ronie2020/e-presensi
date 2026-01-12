<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // <--- PENTING: Baris ini WAJIB ADA untuk mengatasi error 500

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
     * ACCESSOR: Membersihkan format start_time.
     * Mengubah format database (misal "00:00:07" -> 7 atau "07:00:00" -> 7).
     * Panggil di blade dengan: $item->clean_start_time
     */
    public function getCleanStartTimeAttribute()
    {
        $val = 0;
        // Jika formatnya string waktu (ada titik dua :)
        if (str_contains($this->start_time, ':')) {
            // Ambil detiknya (sesuai logika asli Anda)
            $val = intval(Carbon::parse($this->start_time)->second); 
        }

        // Jika hasil parse 0 (atau formatnya sudah jam murni), kembalikan nilainya langsung
        if ($val == 0) {
            return intval($this->start_time);
        }
        
        return $val;
    }

    /**
     * ACCESSOR: Membersihkan format end_time.
     * Panggil di blade dengan: $item->clean_end_time
     */
    public function getCleanEndTimeAttribute()
    {
        $val = 0;
        if (str_contains($this->end_time, ':')) {
            $val = intval(Carbon::parse($this->end_time)->second);
        }

        if ($val == 0) {
            return intval($this->end_time);
        }

        return $val;
    }

    // --- RELASI ---

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
}