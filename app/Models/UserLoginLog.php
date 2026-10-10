<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginLog extends Model
{
    use HasFactory;

    protected $table = 'user_login_logs';

    protected $fillable = [
        'user_id',
        'user_type',
        'name',
        'identifier',
        'role',
        'guard',
        'ip_address',
        'user_agent',
        'device',
        'browser',
        'platform',
        'status',
        'login_at',
        'logout_at',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    /**
     * Relasi opsional ke User (jika user_type = user)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi opsional ke Student (jika user_type = student)
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id');
    }

    /**
     * Scope: Hanya yang sukses login
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope: Filter berdasarkan Peran
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope: Filter hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('login_at', now()->toDateString());
    }

    /**
     * Scope: Rentang Waktu
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('login_at', [$startDate, $endDate]);
    }
}
