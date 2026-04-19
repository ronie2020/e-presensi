<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id', 
        'copy_code', 
        'status', 
        'condition'
    ];

    /**
     * Relasi: Satu fisik eksemplar merujuk pada SATU Judul Buku Induk.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}