<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherEducation extends Model
{
    // Tambahkan baris ini agar Laravel tidak salah mencari nama tabel
    protected $table = 'teacher_educations';

    protected $fillable = ['user_id', 'institution', 'degree', 'start_year', 'end_year'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}