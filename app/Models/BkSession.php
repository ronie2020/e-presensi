<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkSession extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    // Relasi ke Siswa (Yang curhat)
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relasi ke Guru BK (Yang menangani)
    // Menggunakan model User karena Guru ada di tabel users
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Relasi ke Kategori Masalah
    public function category()
    {
        return $this->belongsTo(BkCategory::class, 'bk_category_id');
    }

    // Relasi ke Catatan Hasil (Jurnal)
    public function record()
    {
        return $this->hasOne(BkRecord::class);
    }
}