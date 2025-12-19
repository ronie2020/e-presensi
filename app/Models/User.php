<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\SchoolClass; 
use App\Models\Sppd; // Import SPPD

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',        
        'position',    
        'pangkat', // <--- PENTING: Wajib ada agar import CSV sukses
        'bio',         
        'photo_path', 
        'nip',
        'phone',
        'instagram',
        'tiktok',
        'facebook',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function isTeacher()
    {
        return in_array($this->role, ['Guru', 'Wali Kelas', 'Kepala Sekolah']);
    }

    public function homeroomClass()
    {
        return $this->hasOne(SchoolClass::class, 'homeroom_teacher_id');
    }

    // Relasi ke SPPD
    public function sppds()
    {
        return $this->hasMany(Sppd::class);
    }
}