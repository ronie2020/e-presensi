<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherExperience extends Model
{
    /**
     * Kolom yang diizinkan untuk diisi massal.
     * Sudah ditambahkan 'certificate_path' untuk fitur upload sertifikat.
     */
    protected $fillable = [
        'user_id', 
        'year', 
        'title', 
        'organizer', 
        'certificate_path'
    ];

    /**
     * Relasi ke model User
     * Setiap pengalaman dimiliki oleh satu user (guru).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}