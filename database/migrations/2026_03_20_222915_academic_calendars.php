<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('academic_calendars', function (Blueprint $table) {
            $table->id();
            
            // Nama kegiatan (Contoh: "Libur Semester", "Ujian Tengah Semester")
            $table->string('title');
            
            // Deskripsi opsional
            $table->text('description')->nullable();
            
            // Waktu mulai dan selesai
            // Menggunakan datetime agar mendukung event berwaktu spesifik
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            
            // Apakah kegiatan berlangsung seharian penuh?
            $table->boolean('is_all_day')->default(true);
            
            // Kategori kegiatan untuk filter & reporting
            // Contoh: 'libur', 'ujian', 'kegiatan', 'nasional'
            $table->string('type')->default('kegiatan');
            
            // Warna untuk FullCalendar (opsional, jika ingin dioverride dari warna default tipe)
            $table->string('background_color')->nullable(); // contoh: '#ef4444'
            $table->string('border_color')->nullable();     // contoh: '#b91c1c'
            $table->string('text_color')->nullable();       // contoh: '#ffffff'
            
            // (Opsional) Jika sistem Anda menggunakan relasi Tahun Ajaran
            // $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Kembalikan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_calendars');
    }
};