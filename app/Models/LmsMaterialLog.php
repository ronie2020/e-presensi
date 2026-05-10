<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmsMaterialLog extends Model
{
    protected $fillable = [
        'student_id',
        'material_id',
    ];
}
