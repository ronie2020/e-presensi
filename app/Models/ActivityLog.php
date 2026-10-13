<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'student_id',
        
        // --- KOLOM BARU (SESUAI CONTROLLER) ---
        'activity_type', // Pengganti 'type' (Meal, Religious, Extracurricular)
        'activity_name', // Nama detail (Shalat Dhuha, Menu MBG)
        'point_earned',  // Poin penghargaan
        
        // --- KOLOM LAMA (DIPERTAHANKAN AGAR AMAN) ---
        'description',   
        'notes',
        'scanned_at',    
        'type',          // Simpan jaga-jaga jika kode lama masih pakai ini
    ];

   /**
     * Relasi ke Siswa
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->type)) {
                $model->type = $model->activity_type ?? 'Aktivitas';
            }
            if (empty($model->activity_type)) {
                $model->activity_type = $model->type;
            }
        });
    }

    public function getTypeAttribute()
    {
        return $this->attributes['type'] ?? $this->activity_type;
    }
}