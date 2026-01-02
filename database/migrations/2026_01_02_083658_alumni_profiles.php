<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            // Data Kontak Terkini
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            
            // Status Aktivitas (Tracer Study)
            // Kuliah, Bekerja, Wirausaha, Mencari Kerja, Gap Year
            $table->string('activity_status')->default('Mencari Kerja');
            
            // Jika Kuliah
            $table->string('campus_name')->nullable();
            $table->string('campus_major')->nullable(); // Jurusan
            $table->year('campus_entry_year')->nullable();
            
            // Jika Bekerja / Wirausaha
            $table->string('company_name')->nullable();
            $table->string('position')->nullable();
            $table->string('industry_sector')->nullable(); // Bidang Industri
            
            // Testimoni
            $table->text('testimony')->nullable();
            $table->integer('rating')->default(5); // Rating kepuasan terhadap sekolah (1-5)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_profiles');
    }
};