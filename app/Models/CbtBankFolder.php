<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtBankFolder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'author_id',
    ];

    /**
     * Relasi: Satu Folder memiliki Banyak Bank Soal (Mapel)
     */
    public function banks()
    {
        return $this->hasMany(CbtQuestionBank::class, 'cbt_bank_folder_id');
    }

    /**
     * Relasi ke User (Pembuat)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}