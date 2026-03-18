<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherArticle extends Model
{
   protected $fillable = [
        'user_id', 
        'title', 
        'category', 
        'excerpt', 
        'url', 
        'published_at', 
        'image_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}