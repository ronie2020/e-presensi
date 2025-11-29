<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Master Ekstrakurikuler
        Schema::create('extracurriculars', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Basket, Pramuka
            $table->string('coach_name')->nullable(); // Nama Pembina
            $table->string('schedule')->nullable(); // Contoh: Senin, 15:00
            $table->string('icon')->nullable(); // Class icon (misal: ph-basketball)
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Anggota Ekskul (Pivot Table)
        Schema::create('extracurricular_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracurricular_id')->constrained()->onDelete('cascade');
            // Pastikan tipe data 'student_id' sama dengan tabel students Anda (string/integer)
            $table->string('student_id')->index(); 
            $table->timestamps();

            // Mencegah satu siswa terdaftar ganda di ekskul yang sama
            $table->unique(['extracurricular_id', 'student_id']);
        });

        // 3. Tabel Absensi Ekskul
        Schema::create('extracurricular_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracurricular_id')->constrained()->onDelete('cascade');
            $table->string('student_id')->index();
            $table->date('date');
            $table->time('time_in');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('extracurricular_attendances');
        Schema::dropIfExists('extracurricular_members');
        Schema::dropIfExists('extracurriculars');
    }
};