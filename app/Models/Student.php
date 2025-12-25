<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; // <-- TAMBAHKAN INI

class Student extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    /**
     * DAFTAR KOLOM YANG BOLEH DIISI (MASS ASSIGNMENT)
     */
    protected $fillable = [
        // 1. IDENTITAS UTAMA
        'student_id', 
        'nis', 
        'nisn', 
        'name', 
        'nickname', 
        'class_id',
        
        // 2. BIODATA PRIBADI
        'nik', 
        'gender', 
        'pob',          
        'dob',          
        'birth_place',  
        'birth_date',   
        'religion', 
        'citizenship',
        
        // 3. DATA KELUARGA
        'birth_order', 
        'siblings_count', 
        'step_siblings_count', 
        'adoptive_siblings_count', 
        'orphan_status', 
        'daily_language',
        
        // 4. ALAMAT & KONTAK
        'address', 
        'phone', 
        'living_with', 
        'distance_to_school', 
        'transport_mode',
        
        // 5. KESEHATAN
        'blood_type', 
        'weight', 
        'height', 
        'history_disease', 
        'physical_abnormalities',
        
        // 6. DATA ORANG TUA (AYAH)
        'father_name', 
        'father_pob', 
        'father_birth_year', 
        'father_education', 
        'father_job', 
        'father_income',
        
        // 7. DATA ORANG TUA (IBU)
        'mother_name', 
        'mother_pob', 
        'mother_birth_year', 
        'mother_education', 
        'mother_job', 
        'mother_income',
        
        // 8. DATA WALI
        'guardian_name', 
        'guardian_relationship', 
        'guardian_job', 
        'guardian_phone', 
        'guardian_pob', 
        'guardian_dob', 
        'guardian_citizenship',
        // --- TAMBAHAN (Wajib ada agar tersimpan) ---
        'guardian_address', 
        'guardian_income',
        
        // KONTAK ORTU (PPDB Compatibility)
        'parent_phone', 
        'parent_wa_number', 
        'parent_income',
        
        // 9. DATA AKADEMIK & SEJARAH
        'school_origin', 
        'prev_diploma_no', 
        'prev_exam_date', 
        'accepted_date',
        // --- TAMBAHAN (Wajib ada agar tersimpan) ---
        'transfer_from_school',
        
        // 10. PRESTASI & BEASISWA
        'achievements', 
        'iq_score', 
        'scholarship_info',
        
        // 11. DATA KELULUSAN / PINDAH / DO
        'graduated_date', 
        'graduated_diploma_no', 
        'continuing_to_school', 
        'continuing_school_address',
        'leaving_date', 
        'leaving_reason', 
        'leaving_to_school', 
        'leaving_class',
        'dropout_date', 
        'dropout_reason',
        
        // 12. LAINNYA
        'rfid_id', 
        'photo_path', 
        'status', 
        'join_date', 
        'general_notes'
    ];

    /**
     * Password untuk login siswa (default menggunakan NIS/Student ID)
     */
    public function getAuthPassword()
    {
        return $this->student_id; 
    }

    /**
     * Relasi ke Kelas
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Relasi ke Data Kelulusan (Manajemen SKL & Nilai)
     * INI YANG MENYEBABKAN ERROR SEBELUMNYA
     */
    public function graduation(): HasOne
    {
        // Pastikan Model Graduation ada di App\Models\Graduation
        return $this->hasOne(Graduation::class, 'student_id');
    }

    /**
     * Relasi ke Absensi
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(AttendanceSiswa::class, 'student_id');
    }

    /**
     * Relasi ke Catatan Disiplin
     */
    public function disciplineRecords(): HasMany
    {
        return $this->hasMany(DisciplineRecord::class, 'student_id');
    }
}