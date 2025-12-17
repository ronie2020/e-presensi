<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsQuizQuestion extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Cast options ke array agar otomatis jadi JSON saat simpan/ambil
    protected $casts = [
        'options' => 'array',
    ];

    public function assignment() {
        return $this->belongsTo(LmsAssignment::class);
    }
}