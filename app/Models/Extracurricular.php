<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    protected $guarded = [];

    public function members()
    {
        return $this->hasMany(ExtracurricularMember::class, 'extracurricular_id');
    }

    public function attendances()
    {
        return $this->hasMany(ExtracurricularAttendance::class);
    }
}