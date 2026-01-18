<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleRegular extends Model
{
    use HasFactory;

    /**
     * PENTING: Memberi tahu Laravel agar model ini menggunakan tabel 'schedules_regular'
     */
    protected $table = 'schedules_regular';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        // 'day_type', // <--- Dihapus karena kolomnya sudah tidak ada di DB lokal
        'day_name',    // Kolom pengganti (berisi 'Biasa' atau 'Jumat')
        'start_in',
        'end_in',
        'start_out',
        'end_out',
    ];

    public $timestamps = false;
}