<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Kolom yang boleh diisi secara massal.
     * Sudah ditambahkan semua field Buku Induk agar bisa disimpan.
     */
    protected $fillable = [
        // Identitas Utama (Sudah ada sebelumnya)
        'student_id',
        'name',
        'class_id',
        'rfid_id',
        'parent_wa_number',

        // --- TAMBAHAN BUKU INDUK (A. KETERANGAN PRIBADI) ---
        'nickname',           // Nama Panggilan
        'gender',             // L/P
        'pob',                // Tempat Lahir
        'dob',                // Tanggal Lahir
        'religion',           // Agama
        'citizenship',        // Kewarganegaraan
        'birth_order',        // Anak ke-
        'siblings_count',     // Saudara Kandung
        'step_siblings_count',// Saudara Tiri
        'adoptive_siblings_count', // Saudara Angkat
        'orphan_status',      // Yatim/Piatu
        'daily_language',     // Bahasa Sehari-hari

        // --- B. TEMPAT TINGGAL ---
        'address',            // Alamat Lengkap
        'phone',              // No Telp
        'living_with',        // Tinggal Bersama
        'distance_to_school', // Jarak ke Sekolah
        'transport_mode',     // Kendaraan

        // --- C. KESEHATAN ---
        'weight',             // Berat Badan
        'height',             // Tinggi Badan
        'blood_type',         // Golongan Darah
        'history_disease',    // Riwayat Penyakit
        'physical_abnormalities', // Kelainan Jasmani

        // --- D. PENDIDIKAN SEBELUMNYA ---
        'prev_school_name',   // Asal Sekolah
        'prev_diploma_no',    // No Ijazah
        'prev_exam_date',     // Tanggal Ijazah
        'accepted_date',      // Diterima Tanggal
        'transfer_from_school', // Pindahan Dari

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
        'photo_path',         // Foto Siswa

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
     * Hubungan: Satu Siswa dimiliki oleh SATU Kelas.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Hubungan: Satu Siswa memiliki BANYAK data Absensi.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(AttendanceSiswa::class, 'student_id');
    }

    /**
     * Hubungan: Satu Siswa memiliki BANYAK Catatan Disiplin.
     */
    public function disciplineRecords(): HasMany
    {
        return $this->hasMany(DisciplineRecord::class, 'student_id');
    }

    /**
     * Hubungan: Satu Siswa memiliki BANYAK Peminjaman Buku.
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'student_id');
    }
}