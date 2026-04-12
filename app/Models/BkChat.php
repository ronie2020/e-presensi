<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkChat extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     * * @var array
     */
    protected $fillable = [
        'bk_session_id',
        'user_id',
        'student_id',
        'message',
        'sender_type', // 'teacher' atau 'student'
    ];

    /**
     * Relasi ke Sesi BK utama.
     */
    public function session()
    {
        return $this->belongsTo(BkSession::class, 'bk_session_id');
    }

    /**
     * Relasi ke Guru (User) jika pengirim adalah guru.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Siswa jika pengirim adalah siswa.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}