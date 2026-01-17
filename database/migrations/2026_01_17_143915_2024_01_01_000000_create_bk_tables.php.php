<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Kategori Konseling (Misal: Pribadi, Belajar, Karir)
        Schema::create('bk_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('color')->default('blue'); // Untuk warna label di UI
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Sesi Konseling (Tiket Pengajuan)
        Schema::create('bk_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            // Guru BK yang menangani (Nullable karena awal request belum ada guru)
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null'); 
            
            $table->foreignId('bk_category_id')->constrained('bk_categories');
            
            $table->text('initial_message'); // Keluhan awal siswa
            $table->string('method')->default('offline'); // offline (tatap muka) / online (chat/wa)
            
            $table->dateTime('scheduled_at')->nullable(); // Jadwal yang disepakati
            
            // Status alur: pending -> approved -> ongoing -> finished (atau rejected)
            $table->enum('status', ['pending', 'approved', 'ongoing', 'finished', 'rejected'])->default('pending');
            
            $table->text('response_message')->nullable(); // Pesan balasan dari guru saat approve/reject
            $table->timestamps();
        });

        // 3. Catatan Hasil (Jurnal Rahasia)
        Schema::create('bk_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bk_session_id')->constrained('bk_sessions')->onDelete('cascade');
            
            $table->text('problem_analysis')->nullable(); // Analisis Guru
            $table->text('solution')->nullable(); // Solusi/Saran
            $table->text('result')->nullable(); // Hasil akhir
            
            $table->boolean('is_confidential')->default(true); // Rahasia (Wali kelas gaboleh lihat)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bk_records');
        Schema::dropIfExists('bk_sessions');
        Schema::dropIfExists('bk_categories');
    }
};