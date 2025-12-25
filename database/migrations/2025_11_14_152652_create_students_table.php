<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            
            // 1. IDENTITAS UTAMA
            $table->string('student_id')->unique()->nullable(); 
            $table->string('nis')->nullable();
            $table->string('nisn')->nullable()->unique(); 
            $table->string('name');
            $table->string('nickname')->nullable(); 
            
            // KELAS
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            
            // 2. BIODATA PRIBADI
            $table->string('nik', 16)->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('pob')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('dob')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('religion')->nullable();
            $table->string('citizenship')->default('WNI');
            
            // 3. DATA KELUARGA
            $table->integer('birth_order')->nullable();
            $table->integer('siblings_count')->nullable();
            $table->integer('step_siblings_count')->nullable();
            $table->integer('adoptive_siblings_count')->nullable();
            $table->enum('orphan_status', ['Yatim', 'Piatu', 'Yatim Piatu', 'Lengkap'])->default('Lengkap');
            $table->string('daily_language')->nullable();
            
            // 4. ALAMAT & KONTAK
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('living_with')->nullable();
            $table->string('distance_to_school')->nullable();
            $table->string('transport_mode')->nullable();
            
            // 5. KESEHATAN
            $table->string('blood_type')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->text('history_disease')->nullable();
            $table->text('physical_abnormalities')->nullable();
            
            // 6. DATA ORANG TUA (AYAH)
            $table->string('father_name')->nullable();
            $table->string('father_pob')->nullable();
            $table->string('father_birth_year')->nullable();
            $table->string('father_education')->nullable();
            $table->string('father_job')->nullable();
            $table->string('father_income')->nullable();
            
            // 7. DATA ORANG TUA (IBU)
            $table->string('mother_name')->nullable();
            $table->string('mother_pob')->nullable();
            $table->string('mother_birth_year')->nullable();
            $table->string('mother_education')->nullable();
            $table->string('mother_job')->nullable();
            $table->string('mother_income')->nullable();
            
            // 8. DATA WALI
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship')->nullable();
            $table->string('guardian_job')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_pob')->nullable();
            $table->date('guardian_dob')->nullable();
            $table->string('guardian_citizenship')->nullable();
            // TAMBAHAN KOLOM WALI
            $table->text('guardian_address')->nullable(); // <-- Ditambahkan
            $table->string('guardian_income')->nullable();  // <-- Ditambahkan
            
            // KONTAK ORTU (PPDB Compatibility)
            $table->string('parent_phone')->nullable();
            $table->string('parent_wa_number')->nullable();
            $table->string('parent_income')->nullable();
            
            // 9. DATA AKADEMIK
            $table->string('school_origin')->nullable();
            $table->string('prev_diploma_no')->nullable();
            $table->date('prev_exam_date')->nullable();
            $table->date('accepted_date')->nullable();
            // TAMBAHAN KOLOM PINDAHAN
            $table->string('transfer_from_school')->nullable(); // <-- Ditambahkan
            
            // 10. PRESTASI & BEASISWA
            $table->text('achievements')->nullable();
            $table->string('iq_score')->nullable();
            $table->text('scholarship_info')->nullable();

            // 11. DATA KELULUSAN / PINDAH / DO
            $table->date('graduated_date')->nullable();
            $table->string('graduated_diploma_no')->nullable();
            $table->string('continuing_to_school')->nullable();
            $table->text('continuing_school_address')->nullable();

            $table->date('leaving_date')->nullable();
            $table->string('leaving_reason')->nullable();
            $table->string('leaving_to_school')->nullable();
            $table->string('leaving_class')->nullable();

            $table->date('dropout_date')->nullable();
            $table->string('dropout_reason')->nullable();
            
            // 12. LAINNYA
            $table->string('rfid_id')->nullable()->unique();
            $table->string('photo_path')->nullable();
            $table->string('status')->default('active'); 
            $table->date('join_date')->nullable();
            $table->text('general_notes')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};