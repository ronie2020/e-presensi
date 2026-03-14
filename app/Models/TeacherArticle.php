<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherArticle extends Model
{
    protected $fillable = ['user_id', 'title', 'category', 'excerpt', 'url', 'image_path', 'published_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}