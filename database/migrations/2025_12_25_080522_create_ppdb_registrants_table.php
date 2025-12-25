<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ppdb_registrants', function (Blueprint $table) {
            $table->id();
            
            // --- IDENTITAS PENDAFTARAN ---
            // Format: PPDB-2025-001 (Dibuat otomatis di Controller)
            $table->string('registration_number')->unique(); 
            // Tahun ajaran agar data bisa dipisah per tahun (misal: 2025/2026)
            $table->string('academic_year', 9); 
            
            // --- DATA PRIBADI SISWA ---
            $table->string('nisn', 10)->unique(); // Kunci utama validasi
            $table->string('nik', 16)->nullable();
            $table->string('full_name');
            $table->enum('gender', ['L', 'P']);
            $table->string('birth_place');
            $table->date('birth_date');
            $table->text('address');
            $table->string('religion')->default('Islam');
            
            // --- DATA ASAL SEKOLAH ---
            $table->string('school_origin'); // Nama SD/MI asal
            $table->string('npsn_school_origin')->nullable();
            
            // --- DATA ORANG TUA / WALI ---
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('parent_phone', 15); // Nomor WA Aktif
            $table->string('parent_job')->nullable();
            
            // --- DATA SELEKSI ---
            // Pilihan Jalur: Zonasi, Prestasi, Afirmasi, Perpindahan Tugas
            $table->enum('track', ['zonasi', 'prestasi', 'afirmasi', 'pindah_tugas']);
            // Nilai rata-rata rapor (semester 7-11 / kls 4-6) untuk perankingan
            $table->decimal('average_grade', 5, 2)->default(0); 
            // Jarak rumah ke sekolah (dalam meter/km) - opsional jika zonasi manual
            $table->integer('distance_in_meters')->nullable(); 
            
            // --- UPLOAD DOKUMEN (Path File) ---
            $table->string('file_photo')->nullable(); // Pas Foto
            $table->string('file_kk')->nullable();    // Kartu Keluarga
            $table->string('file_akta')->nullable();  // Akta Kelahiran
            $table->string('file_grades')->nullable();// Scan Rapor/SKL
            $table->string('file_kip')->nullable();   // KIP/KPS (Khusus Afirmasi)
            
            // --- STATUS ---
            // pending: baru daftar
            // verified: berkas dicek admin & valid
            // accepted: lulus seleksi
            // rejected: tidak lulus / berkas salah
            $table->enum('status', ['pending', 'verified', 'accepted', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable(); // Catatan jika ditolak (misal: Scan KK buram)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_registrants');
    }
};