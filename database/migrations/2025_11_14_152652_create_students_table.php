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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique(); // NISN atau ID Unik Siswa
            $table->string('name');
            
            // Kolom ini menghubungkan siswa ke kelasnya (tabel 'classes')
            $table->foreignId('class_id')->constrained('classes');
            
            $table->string('rfid_id')->nullable()->unique(); // Opsional, untuk scan RFID
            $table->string('parent_wa_number')->nullable(); // Opsional, No. WA Orang Tua
            
            $table->softDeletes(); // Menambahkan 'deleted_at' untuk soft delete
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};