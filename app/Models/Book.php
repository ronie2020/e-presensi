<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_code',      // Kode Barcode
        'title',          // Judul
        'is_textbook',    // Penanda Buku Paket
        'category_id',    // ID Kategori
        'author',         // Pengarang
        'publisher',      // Penerbit
        'year',           // Tahun Terbit
        'purchase_date',  // Tanggal Pembelian
        'isbn',
        'stock',          // Jumlah Stok
        'shelf_location', // Lokasi Rak
        'description',
        'cover_path',     // Foto Cover
        'ebook_path',     // e_book 
    ];

    /**
     * Relasi: Satu Buku termasuk dalam SATU Kategori.
     */
    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'category_id');
    }

    /**
     * Relasi: Satu Buku bisa dipinjam BANYAK kali (history).
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'book_id');
    }

      /**
     * RELASI BARU: 1 Judul Buku punya BANYAK Eksemplar Fisik
     */
    public function copies()
    {
        return $this->hasMany(BookCopy::class, 'book_id');
    }
    
    /**
     * Helper: Cek apakah stok tersedia
     */
    public function isAvailable()
    {
        return $this->stock > 0;
    }
}