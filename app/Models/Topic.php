<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['subject_id', 'title', 'description', 'order_number', 'is_published'];

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function materials() {
        return $this->hasMany(Material::class)->orderBy('order_in_topic', 'asc');
    }

    public function assignments() {
        return $this->hasMany(Assignment::class)->orderBy('order_in_topic', 'asc');
    }
}