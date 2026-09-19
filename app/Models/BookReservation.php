<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BookReservation extends Model
{
    protected $fillable = [
        'student_id',
        'book_id',
        'status',         // waiting, ready, borrowed, cancelled, expired
        'notified_at',
        'expires_at',
    ];

    protected $casts = [
        'notified_at' => 'datetime',
        'expires_at'  => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /** Apakah reservasi sudah kadaluarsa (tidak diambil dalam 3 hari) */
    public function getIsExpiredAttribute(): bool
    {
        return $this->status === 'ready'
            && $this->expires_at
            && Carbon::now()->gt($this->expires_at);
    }
}
