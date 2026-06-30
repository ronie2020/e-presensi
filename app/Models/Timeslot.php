<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model
{
    use HasFactory;

    // Kolom yang diizinkan untuk diisi secara massal
    protected $fillable = [
        'name', 
        'day_of_week',
        'start_time', 
        'end_time', 
        'is_break', 
        'order_sequence'
    ];

    // Otomatis mengubah nilai 0/1 dari database menjadi boolean true/false
    protected $casts = [
        'is_break' => 'boolean',
    ];

    /**
     * Relasi: Satu Slot Waktu bisa memiliki Banyak Jadwal (Timetable)
     */
    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}