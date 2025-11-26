<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = ['name', 'semester', 'is_active'];
    
    // Helper untuk mengambil tahun aktif
    public static function active()
    {
        return self::where('is_active', true)->first();
    }
}