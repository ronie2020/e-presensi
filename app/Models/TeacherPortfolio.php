<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherPortfolio extends Model
{
    protected $fillable = ['user_id', 'title', 'year', 'image_path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}