<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherMaterial extends Model
{
    protected $fillable = ['user_id', 'title', 'type', 'icon', 'file_path', 'file_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}