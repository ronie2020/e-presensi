<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Sesi Mengajar (Jurnal Guru)
        Schema::create('teaching_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users'); // Guru yang mengajar saat itu
            $table->date('date');
            $table->timestamp('started_at')->nullable(); // Waktu guru klik "Mulai"
            $table->timestamp('ended_at')->nullable();   // Waktu guru klik "Selesai"
            
            // Kolom Jurnal / Materi
            $table->string('topic')->nullable();         // Judul Materi
            $table->text('activities')->nullable();      // Catatan aktivitas / Tugas
            $table->string('reference_link')->nullable(); // Link Materi (Gdrive/Youtube)
            
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
        });

        // 2. Tabel Absensi Per Mapel
        Schema::create('class_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teaching_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['present', 'late', 'alpha', 'sick', 'permission']);
            $table->timestamp('scanned_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_attendances');
        Schema::dropIfExists('teaching_sessions');
    }
};