<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'type',        // Jenis Scan: 'Makan', 'Perpus', dll
        'description', // Keterangan: 'Ambil Makan Siang'
        'scanned_at',  // Waktu Scan
        'notes'
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    // Relasi ke Siswa
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}