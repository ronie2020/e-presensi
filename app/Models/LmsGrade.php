<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsGrade extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit (opsional, tapi disarankan)
    protected $table = 'lms_grades';

    // Mengizinkan semua kolom diisi (mass assignment) kecuali ID
    protected $guarded = ['id'];

    // Casting tipe data
    protected $casts = [
        'graded_at' => 'datetime',
        'score' => 'integer', // atau 'float' jika nilai desimal
    ];

    /**
     * Relasi ke Assignment (Tugas/Ujian)
     */
    public function assignment()
    {
        // Pastikan foreign key di database adalah 'lms_assignment_id'
        return $this->belongsTo(LmsAssignment::class, 'lms_assignment_id');
    }

    /**
     * Relasi ke Siswa
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}