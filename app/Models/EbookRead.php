<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EbookRead extends Model
{
    // Nama tabel di database
    protected $table = 'ebook_reads';
    
    // Kolom yang boleh diisi
    protected $fillable = ['book_id', 'student_id'];

    // Relasi ke Buku
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Relasi ke Siswa
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}