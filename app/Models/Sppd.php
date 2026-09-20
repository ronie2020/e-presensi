<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\SppdFollower;

class Sppd extends Model
{
    use HasFactory;

    protected $table = 'sppds';

    protected $fillable = [
        'spt_id',
        'nomor_sppd',
        'status',
        'catatan_kepala',
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
        'biaya_transport',
        'biaya_penginapan',
        'uang_harian',
        'pejabat_nama',
        'pejabat_nip',
        'pejabat_pangkat',
        'pejabat_jabatan',
    ];

    protected $casts = [
        'tgl_berangkat'   => 'date',
        'tgl_kembali'     => 'date',
        'biaya_transport' => 'decimal:2',
        'biaya_penginapan'=> 'decimal:2',
        'uang_harian'     => 'decimal:2',
    ];

    // ===== LABEL STATUS =====
    public static function statusLabel(): array
    {
        return [
            'draft'     => ['label' => 'Draft',     'color' => 'slate'],
            'submitted' => ['label' => 'Diajukan',  'color' => 'amber'],
            'approved'  => ['label' => 'Disetujui', 'color' => 'sky'],
            'selesai'   => ['label' => 'Selesai',   'color' => 'emerald'],
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabel()[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::statusLabel()[$this->status]['color'] ?? 'slate';
    }

    // ===== ACCESSOR TOTAL BIAYA =====
    public function getTotalBiayaAttribute(): float
    {
        return (float)($this->biaya_transport ?? 0)
             + (float)($this->biaya_penginapan ?? 0)
             + (float)($this->uang_harian ?? 0);
    }

    // ===== SCOPES STATUS =====
    public function scopeDraft($query)       { return $query->where('status', 'draft'); }
    public function scopeSubmitted($query)   { return $query->where('status', 'submitted'); }
    public function scopeApproved($query)    { return $query->where('status', 'approved'); }
    public function scopeSelesai($query)     { return $query->where('status', 'selesai'); }

    // ===== RELASI =====
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spt()
    {
        return $this->belongsTo(LetterSpt::class, 'spt_id');
    }

    public function followers()
    {
        return $this->hasMany(SppdFollower::class, 'sppd_id');
    }
}