<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbRegistrant extends Model
{
    use HasFactory;

    // Kolom-kolom yang boleh diisi secara massal (Mass Assignment)
    protected $fillable = [
        'registration_number',
        'academic_year',
        'nisn',
        'nik',
        'full_name',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'religion',
        'school_origin',
        'npsn_school_origin',
        'father_name',
        'mother_name',
        'parent_phone',
        'parent_job',
        'track',
        'average_grade',
        'distance_in_meters',
        'file_photo',
        'file_kk',
        'file_akta',
        'file_grades',
        'file_kip',
        'status',
        'admin_note',
    ];

    // Casting tipe data agar otomatis sesuai format saat diambil
    protected $casts = [
        'birth_date' => 'date',
        'average_grade' => 'decimal:2',
    ];
}