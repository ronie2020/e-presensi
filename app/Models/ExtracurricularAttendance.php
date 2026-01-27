<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtracurricularAttendance extends Model
{
    use HasFactory;

    protected $table = 'extracurricular_attendances';

    /**
     * Kita gunakan $fillable agar lebih eksplisit.
     * Pastikan 'time_in' ada di sini.
     */
    protected $fillable = [
        'extracurricular_id',
        'student_id',
        'date',
        'status',
        'time_in', // <--- INI KUNCINYA
    ];

    public function student()
    {
        // Jika kolom primary key di tabel students adalah 'id',
        // maka default belongsTo(Student::class) sudah cukup.
        // Tapi jika Anda ingin eksplisit seperti file sebelumnya, boleh juga:
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }
}