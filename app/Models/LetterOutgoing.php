<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterOutgoing extends Model
{
    use HasFactory;

    protected $table = 'letter_outgoings';

    protected $fillable = [
        'nomor_agenda',
        'nomor_surat',
        'tujuan_surat',
        'sifat_surat',
        'tgl_surat',
        'perihal',
        'file_path',
    ];

    protected $casts = [
        'tgl_surat' => 'date',
    ];

    // Relasi: 1 Surat Keluar bisa memicu 1 atau banyak SPT
    public function spts()
    {
        return $this->hasMany(LetterSpt::class, 'surat_keluar_id');
    }
}