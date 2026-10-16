<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiketSchedule extends Model
{
    use HasFactory;

    protected $table = 'piket_schedules';

    protected $fillable = [
        'academic_year_id',
        'user_id',
        'day_name',
        'post_location',
        'is_coordinator',
        'order_number',
    ];

    protected $casts = [
        'is_coordinator' => 'boolean',
        'order_number'   => 'integer',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }
}
