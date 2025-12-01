<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// 1. PENTING: Tambahkan import ini agar kenal model SchoolClass
use App\Models\SchoolClass; 

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
        'role',        
        'position',    
        'bio',         
        'photo_path', 
        'nip',
        // --- KONTAK & SOSMED (BARU) ---
        'phone',
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
    
    // Helper untuk cek apakah user ini Guru
    public function isTeacher()
    {
        return in_array($this->role, ['Guru', 'Wali Kelas', 'Kepala Sekolah']);
    }

    /**
     * [DITAMBAHKAN] Relasi Wali Kelas
     * Menghubungkan Guru (User) dengan Kelas yang dia ampuh.
     */
    public function homeroomClass()
    {
        // Seorang User (Wali Kelas) memiliki satu SchoolClass
        // Foreign key di tabel classes adalah 'homeroom_teacher_id'
        return $this->hasOne(SchoolClass::class, 'homeroom_teacher_id');
    }
}