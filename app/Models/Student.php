<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // [UBAH] Ganti Model jadi Authenticatable
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable; // [TAMBAH] Tambahkan Notifiable

class Student extends Authenticatable // [UBAH] Extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        // Identitas Utama
        'student_id',
        'name',
        'class_id',
        'rfid_id',
        'parent_wa_number',

        // --- TAMBAHAN BUKU INDUK (A. KETERANGAN PRIBADI) ---
        'nickname',           
        'gender',             
        'pob',                
        'dob',                
        'religion',           
        'citizenship',        
        'birth_order',        
        'siblings_count',     
        'step_siblings_count',
        'adoptive_siblings_count', 
        'orphan_status',      
        'daily_language',     

        // --- B. TEMPAT TINGGAL ---
        'address',            
        'phone',              
        'living_with',        
        'distance_to_school', 
        'transport_mode',     

        // --- C. KESEHATAN ---
        'weight',             
        'height',             
        'blood_type',         
        'history_disease',    
        'physical_abnormalities', 

        // --- D. PENDIDIKAN SEBELUMNYA ---
        'prev_school_name',   
        'prev_diploma_no',    
        'prev_exam_date',     
        'accepted_date',      
        'transfer_from_school', 

        // --- E. DATA ORANG TUA (AYAH) ---
        'father_name',
        'father_pob',
        'father_birth_year',
        'father_education',
        'father_job',
        'father_income',

        // --- E. DATA ORANG TUA (IBU) ---
        'mother_name',
        'mother_pob',
        'mother_birth_year',
        'mother_education',
        'mother_job',
        'mother_income',

        // --- F. DATA WALI (Jika Ada) ---
        'guardian_name',
        'guardian_relationship',
        'guardian_education',
        'guardian_job',
        'guardian_income',
        'guardian_address',

        // --- G. INTELEGENSI & PRESTASI ---
        'iq_score',
        'achievements',

        // --- LAINNYA ---
        'photo_path',         

         'guardian_pob', 
         'guardian_dob', 
         'guardian_citizenship',
        'scholarship_info',
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
        'general_notes'
    ];

    /**
     * [TAMBAH] Karena siswa login tanpa password (hanya NISN),
     * kita override fungsi ini.
     */
    public function getAuthPassword()
    {
        return $this->student_id; 
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(AttendanceSiswa::class, 'student_id');
    }

    public function disciplineRecords(): HasMany
    {
        return $this->hasMany(DisciplineRecord::class, 'student_id');
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'student_id');
    }
}