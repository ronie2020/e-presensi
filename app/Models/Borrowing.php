<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',    // Siapa yang pinjam
        'book_id',       // Buku apa
        'borrow_date',   // Tgl Pinjam
        'due_date',      // Tgl Jatuh Tempo (Wajib Kembali)
        'return_date',   // Tgl Dikembalikan (Real)
        'status',        // borrowed, returned, lost, damaged
        'type',          // regular, textbook
        'item_code',     // Kode Eksemplar Fisik
        'fine_amount',   // Denda
        'fine_paid',     // Apakah denda sudah dibayar
        'fine_paid_at',  // Waktu pembayaran denda
        'fine_paid_by',  // Petugas yang konfirmasi lunas
        'is_extended',       // Apakah sudah pernah diperpanjang
        'extension_count',   // Berapa kali diperpanjang
        'extended_at',       // Waktu perpanjangan terakhir
        'notes',         // Catatan kondisi
        'served_by',     // Petugas yang melayani
    ];

    protected $casts = [
        'borrow_date'   => 'datetime',
        'due_date'      => 'datetime',
        'return_date'   => 'datetime',
        'fine_paid_at'  => 'datetime',
        'extended_at'   => 'datetime',
        'fine_paid'     => 'boolean',
        'is_extended'   => 'boolean',
    ];

    /**
     * Relasi: Transaksi ini milik SATU Siswa.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Relasi: Transaksi ini meminjam SATU Buku.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    /**
     * Relasi: Transaksi ini dilayani oleh SATU Petugas (User).
     */
    public function server()
    {
        return $this->belongsTo(User::class, 'served_by');
    }

    /**
     * Helper: Hitung Keterlambatan (Hari)
     */
    public function getOverdueDaysAttribute(): int
    {
        $due = Carbon::parse($this->due_date);

        if ($this->return_date) {
            $return = Carbon::parse($this->return_date);
            return $return->greaterThan($due) ? $return->diffInDays($due) : 0;
        }

        $now = Carbon::now();
        return $now->greaterThan($due) ? $now->diffInDays($due) : 0;
    }

    /**
     * Helper: Apakah buku sedang overdue (belum kembali & sudah lewat jatuh tempo)
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'borrowed' && Carbon::now()->gt($this->due_date);
    }

    /**
     * Helper: Apakah bisa diperpanjang (maks 1x, belum overdue)
     */
    public function getCanExtendAttribute(): bool
    {
        return $this->status === 'borrowed'
            && $this->extension_count < 1
            && !Carbon::now()->gt($this->due_date);
    }

    /**
     * Helper: Apakah denda perlu dibayar (ada denda, belum lunas)
     */
    public function getHasPendingFineAttribute(): bool
    {
        return ($this->fine_amount ?? 0) > 0 && !$this->fine_paid;
    }
}