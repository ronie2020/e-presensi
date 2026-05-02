<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterSpt extends Model
{
    use HasFactory;

    protected $table = 'letter_spts';

    protected $fillable = [
        'letter_incoming_id',
        'surat_keluar_id', // BARU: Menampung ID Surat Keluar
        'nomor_spt',
        'untuk',
        'tempat_tujuan',
        'tgl_berangkat',
        'tgl_kembali',
        'lama_hari',
        'pejabat_nama',
        'pejabat_nip',
    ];

    protected $casts = [
        'tgl_berangkat' => 'date',
        'tgl_kembali' => 'date',
    ];

    // Relasi ke Surat Masuk
    public function letterIncoming()
    {
        return $this->belongsTo(LetterIncoming::class, 'letter_incoming_id');
    }

    // BARU: Relasi ke Surat Keluar
    public function letterOutgoing()
    {
        return $this->belongsTo(LetterOutgoing::class, 'surat_keluar_id');
    }

    // Relasi Pegawai (Pivot)
    public function users()
    {
        return $this->belongsToMany(User::class, 'letter_spt_user', 'letter_spt_id', 'user_id');
    }

    // BARU: Relasi ke SPPD (1 SPT bisa punya banyak SPPD jika guru nya banyak)
    public function sppds()
    {
        return $this->hasMany(Sppd::class, 'spt_id');
    }
}