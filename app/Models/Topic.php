<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LmsMaterial;
use App\Models\LmsAssignment;

class Topic extends Model
{
    protected $fillable = ['subject_id', 'title', 'description', 'order_number', 'is_published'];

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function materials() {
        return $this->hasMany(LmsMaterial::class)->orderBy('order_in_topic', 'asc');
    }

    public function assignments() {
        return $this->hasMany(LmsAssignment::class)->orderBy('order_in_topic', 'asc');
    }
}