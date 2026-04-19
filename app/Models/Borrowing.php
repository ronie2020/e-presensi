<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',   // Siapa yang pinjam
        'book_id',      // Buku apa
        'borrow_date',  // Tgl Pinjam
        'due_date',     // Tgl Jatuh Tempo (Wajib Kembali)
        'return_date',  // Tgl Dikembalikan (Real)
        'status',       // borrowed, returned, lost, damaged
        'type',         // regular, textbook
        'item_code',    // Kode Eksemplar Fisik
        'fine_amount',  // Denda
        'notes',        // Catatan kondisi
        'served_by',    // Petugas yang melayani
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
    public function getOverdueDaysAttribute()
    {
        if ($this->return_date) {
            // Jika sudah kembali, hitung selisih tgl kembali vs jatuh tempo
            $return = Carbon::parse($this->return_date);
            $due = Carbon::parse($this->due_date);
            return $return->greaterThan($due) ? $return->diffInDays($due) : 0;
        } else {
            // Jika belum kembali, hitung selisih hari ini vs jatuh tempo
            $now = Carbon::now();
            $due = Carbon::parse($this->due_date);
            return $now->greaterThan($due) ? $now->diffInDays($due) : 0;
        }
    }
}