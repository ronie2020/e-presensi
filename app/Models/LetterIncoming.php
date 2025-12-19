<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterIncoming extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika sesuai standar, tapi biar aman kita tulis)
    protected $table = 'letter_incomings';

    protected $fillable = [
        'nomor_surat',
        'pengirim',
        'perihal',
        'tgl_surat',
        'tgl_terima',
        'file_path',
        'status_disposisi',
    ];

    // Casting agar otomatis jadi objek Carbon (Date)
    protected $casts = [
        'tgl_surat' => 'date',
        'tgl_terima' => 'date',
    ];
}