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

        // DATA ORANG TUA
        'father_name',
        'mother_name',
        'parent_phone',
        'parent_job',
        'parent_income',

        // DATA SISWA (FIX C1: student_phone ditambahkan)
        'student_phone',

        // DATA JALUR & NILAI
        'track',
        'average_grade',
        'distance_in_meters',

        // FIX BUG 6: Nilai detail per mapel (JSON) — sebelumnya tidak ada sama sekali
        'grades_detail',

        // DATA PRESTASI
        'achievement_type',
        'achievement_name',
        'achievement_level',
        'achievement_rank',
        'file_achievement',

        // FILE DOKUMEN
        'file_photo',
        'file_kk',
        'file_akta',
        'file_grades',
        'file_kip',

        // STATUS
        'status',
        'admin_note',
    ];

    // Casting tipe data agar otomatis sesuai format saat diambil
    protected $casts = [
        'birth_date'    => 'date',
        'average_grade' => 'decimal:2',
        'grades_detail' => 'array', // FIX BUG 6: Otomatis decode JSON ke array PHP
    ];
}