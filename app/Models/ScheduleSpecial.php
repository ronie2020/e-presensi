<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleSpecial extends Model
{
    use HasFactory;

    /**
     * PENTING: Memberi tahu Laravel agar model ini menggunakan tabel 'schedules_special'
     */
    protected $table = 'schedules_special';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'date',
        'description',
        'is_holiday',
        'start_in',
        'end_in',
        'start_out',
        'end_out',
    ];
   
    /**
     * Tambahkan ini:
     * Tipe data
     * Kita ingin 'is_holiday' otomatis menjadi boolean (true/false)
     * dan 'date' menjadi objek Carbon (Tanggal).
     */
    protected $casts = [
        'date' => 'date',
        'is_holiday' => 'boolean',
    ];

    /**
     * Tambahkan ini:
     * Memberi tahu Laravel bahwa tabel ini tidak memiliki
     * kolom created_at dan updated_at.
     */
    public $timestamps = false;
}