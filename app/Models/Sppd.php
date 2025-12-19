<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Sppd extends Model
{
    use HasFactory;

    // Nama tabel di database (sesuai migration)
    protected $table = 'sppds';

    // Kolom yang boleh diisi (Mass Assignment)
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
        // Data Pejabat Pemberi Perintah
        'pejabat_nama',
        'pejabat_nip',
        'pejabat_pangkat',
        'pejabat_jabatan',
    ];

    /**
     * Relasi ke Pegawai (User)
     * Setiap SPPD dimiliki oleh satu Pegawai.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}