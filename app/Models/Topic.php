<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LmsMaterial;
use App\Models\LmsAssignment;

class Topic extends Model
{
    protected $fillable = [
        'subject_id',
        'grade_level',
        'class_id',
        'title',
        'description',
        'order_number',
        'is_published'
    ];

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass() {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function materials() {
        return $this->hasMany(LmsMaterial::class)->orderBy('order_in_topic', 'asc');
    }

    public function assignments() {
        return $this->hasMany(LmsAssignment::class)->orderBy('order_in_topic', 'asc');
    }

    /**
     * Dapatkan label kelas / tingkat untuk tampilan UI
     */
    public function getTargetBadgeAttribute()
    {
        if ($this->schoolClass) {
            return 'Kelas ' . $this->schoolClass->name;
        }

        if ($this->grade_level) {
            return 'Tingkat ' . $this->grade_level;
        }

        return 'Semua Tingkat';
    }
}