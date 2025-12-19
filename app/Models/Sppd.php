<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\SppdFollower; // Import Model Follower

class Sppd extends Model
{
    use HasFactory;

    protected $table = 'sppds';

    protected $fillable = [
        'nomor_sppd',
        'user_id',
        'maksud_perjalanan',
        'alat_angkut',
        'tempat_berangkat',
        'tempat_tujuan',
        'lama_hari',
        'tgl_berangkat',
        'tgl_kembali',
        'instansi_pembayar',
        'mata_anggaran',
        'keterangan_lain',
        'pejabat_nama',
        'pejabat_nip',
        'pejabat_pangkat',
        'pejabat_jabatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI BARU: PENGIKUT
    public function followers()
    {
        return $this->hasMany(SppdFollower::class, 'sppd_id');
    }
}