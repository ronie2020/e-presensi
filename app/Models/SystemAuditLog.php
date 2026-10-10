<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemAuditLog extends Model
{
    use HasFactory;

    protected $table = 'system_audit_logs';

    protected $fillable = [
        'user_id',
        'user_type',
        'user_name',
        'role',
        'module',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'method',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relasi opsional ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope: Filter Modul
     */
    public function scopeModule($query, $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope: Filter Aksi
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Rentang Waktu
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
