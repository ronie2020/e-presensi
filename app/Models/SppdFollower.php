<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppdFollower extends Model
{
    use HasFactory;

    protected $table = 'sppd_followers';

    protected $fillable = [
        'sppd_id',
        'user_id',
        'nama',
        'nip', // Diganti dari tgl_lahir
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}