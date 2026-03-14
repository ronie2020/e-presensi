<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // Tambahkan kolom baru ini agar bisa disimpan:
        'role',
        'position',
        'pangkat',    // <--- PENTING: Agar pangkat tersimpan
        'nip',        // <--- Agar NIP tersimpan
        'bio',
        'photo_path',
        'phone',      // <--- Kontak & Sosmed
        'instagram',
        'tiktok',
        'facebook',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    // Relasi (Opsional, jika belum ada biarkan saja)
    public function permits()
    {
        return $this->hasMany(StudentPermit::class, 'pic_teacher_id');
    }
    public function experiences() {
        return $this->hasMany(\App\Models\TeacherExperience::class);
    }
    public function materials() {
        return $this->hasMany(\App\Models\TeacherMaterial::class);
    }
    public function portfolios() {
        return $this->hasMany(\App\Models\TeacherPortfolio::class);
    }
    public function articles() {
        return $this->hasMany(\App\Models\TeacherArticle::class);
    }
}