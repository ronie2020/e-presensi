<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsDiscussion extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'user_id',
        'student_id',
        'author_name',
        'author_role',
        'parent_id',
        'comment',
        'is_verified'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function material()
    {
        return $this->belongsTo(LmsMaterial::class, 'material_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function parent()
    {
        return $this->belongsTo(LmsDiscussion::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(LmsDiscussion::class, 'parent_id')->orderBy('created_at', 'asc');
    }
}
