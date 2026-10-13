<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles {
        hasRole as spatieHasRole;
        hasAnyRole as spatieHasAnyRole;
    }

    /**
     * Cek role dengan fallback ke kolom 'role' jika Spatie belum tersinkron
     */
    public function hasRole($roles, ?string $guard = null): bool
    {
        try {
            if ($this->spatieHasRole($roles, $guard)) {
                return true;
            }
        } catch (\Throwable $e) {}

        $roles = is_array($roles) ? $roles : [$roles];
        $userRole = $this->role ?? null;
        if (!empty($userRole)) {
            $userRoles = is_array($userRole) 
                ? $userRole 
                : (json_decode($userRole, true) ?? explode(',', $userRole));
            $userRoles = array_map('trim', (array) $userRoles);
            
            return !empty(array_intersect($roles, $userRoles));
        }

        return false;
    }

    /**
     * Cek any role dengan fallback ke kolom 'role' jika Spatie belum tersinkron
     */
    public function hasAnyRole(...$roles): bool
    {
        try {
            if ($this->spatieHasAnyRole(...$roles)) {
                return true;
            }
        } catch (\Throwable $e) {}

        $roles = collect($roles)->flatten();
        $userRole = $this->role ?? null;
        if (!empty($userRole)) {
            $userRoles = is_array($userRole) 
                ? $userRole 
                : (json_decode($userRole, true) ?? explode(',', $userRole));
            $userRoles = array_map('trim', (array) $userRoles);
            
            return $roles->intersect($userRoles)->isNotEmpty();
        }

        return false;
    }

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
        'pangkat',    
        'nip',        
        'bio',
        'photo_path',
        'phone',      
        'instagram',
        'tiktok',
        'facebook',        
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'jenis_kelamin',
        'agama',
        'status_pernikahan',
        'keahlian',
        'hobi',
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
   public function educations()
    {
        return $this->hasMany(TeacherEducation::class);
    }
    
    /**
     * Relasi ke Beban Mengajar (Teaching Loads)
     * Untuk mengecek guru ini punya jam mengajar di kelas mana saja
     */
    public function teachingLoads()
    {
        return $this->hasMany(TeachingLoad::class, 'teacher_id');
    }

    /**
     * Relasi ke Jadwal Mengajar (Timetables)
     * Untuk menarik data jadwal final milik guru ini
     */
    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'teacher_id');
    }
}