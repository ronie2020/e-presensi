<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsMaterial extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass() {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    // RELASI BARU: Ke Tabel Attachments
    public function attachments() {
        return $this->hasMany(LmsMaterialAttachment::class, 'material_id');
    }

    public function topic() {
    return $this->belongsTo(Topic::class);
    }
}