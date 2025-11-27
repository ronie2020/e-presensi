<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Siswa', 'Guru', 'Sekolah']); // Siapa yang berprestasi
            
            // Jika Siswa, relasikan ke tabel students
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('cascade');
            
            // Jika Guru/Sekolah, isi nama manual
            $table->string('name_manual')->nullable(); 
            
            $table->string('title'); // Juara 1 Lomba...
            $table->enum('level', ['Sekolah', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional', 'Internasional']);
            $table->date('date');
            $table->text('description')->nullable();
            
            // Media
            $table->string('photo_path')->nullable(); // Foto Dokumentasi
            $table->string('video_link')->nullable(); // Link Youtube/IG (Hemat storage)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};