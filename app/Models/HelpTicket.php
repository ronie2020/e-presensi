<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpTicket extends Model
{
    use HasFactory;

    protected $table = 'help_tickets';

    protected $fillable = [
        'ticket_code',
        'reporter_type',
        'user_id',
        'student_id',
        'name',
        'identity_number',
        'class_or_role',
        'phone',
        'category',
        'page_url',
        'description',
        'screenshot_path',
        'status',
        'admin_notes',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Scope filter untuk pencarian & status
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['status'] ?? false, function ($q, $status) {
            $q->where('status', $status);
        });

        $query->when($filters['reporter_type'] ?? false, function ($q, $type) {
            $q->where('reporter_type', $type);
        });

        $query->when($filters['category'] ?? false, function ($q, $cat) {
            $q->where('category', $cat);
        });

        $query->when($filters['search'] ?? false, function ($q, $search) {
            $q->where(function($sub) use ($search) {
                $sub->where('ticket_code', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('identity_number', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        });
    }
}
