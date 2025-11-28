<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolActivity extends Model
{
    use HasFactory;

    // Tambahkan ini untuk mengatasi error MassAssignmentException
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'video_url',
    ];
}