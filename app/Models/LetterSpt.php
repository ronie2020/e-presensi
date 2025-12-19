<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LetterSpt extends Model
{
    use HasFactory;

    protected $table = 'letter_spts';

    protected $fillable = [
        'letter_incoming_id',
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

    // Relasi: SPT punya dasar Surat Masuk
    public function letterIncoming()
    {
        return $this->belongsTo(LetterIncoming::class, 'letter_incoming_id');
    }

    // Relasi: SPT menugaskan Banyak Pegawai
    public function users()
    {
        return $this->belongsToMany(User::class, 'letter_spt_user', 'letter_spt_id', 'user_id');
    }
}