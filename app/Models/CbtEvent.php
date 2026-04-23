<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtEvent extends Model
{
    protected $fillable = ['name', 'description', 'is_active'];

    public function exams()
    {
        return $this->hasMany(CbtExam::class);
    }
}