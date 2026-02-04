<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    // Asumsi nama tabel di database adalah 'loans'
    protected $table = 'loans'; 

    protected $guarded = [];

    // Relasi ke Siswa
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Relasi ke Buku
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}