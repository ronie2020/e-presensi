<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Induk Transaksi Nilai (Per Siswa Per Semester)
        Schema::create('grade_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('academic_year'); // Contoh: 2023/2024
            $table->enum('semester', ['1', '2']); // Semester 1 atau 2
            $table->string('class_name'); // Menyimpan nama kelas saat nilai ini didapat (snapshot)
            
            // Data Kehadiran (Sakit/Izin/Alpa) diambil snapshot-nya disini
            $table->integer('absent_s')->default(0); // Sakit
            $table->integer('absent_i')->default(0); // Izin
            $table->integer('absent_a')->default(0); // Alpa
            
            // Catatan Wali Kelas / Keputusan
            $table->text('notes')->nullable(); // Catatan naik kelas/lulus
            $table->boolean('is_promoted')->nullable(); // Naik kelas?
            $table->date('report_date')->nullable(); // Tanggal Rapor
            
            $table->timestamps();
        });

        // Tabel Detail Nilai Per Mapel
        Schema::create('grade_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_record_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            
            $table->integer('score')->nullable(); // Nilai Angka (0-100)
            $table->string('predicate')->nullable(); // Predikat (A/B/C)
            $table->text('description')->nullable(); // Deskripsi Capaian Kompetensi
            
            $table->timestamps();
        });

        // Tabel Ekstrakurikuler
        Schema::create('extracurricular_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_record_id')->constrained()->onDelete('cascade');
            $table->string('activity_name'); // Nama Ekskul (Pramuka, Futsal)
            $table->string('score')->nullable(); // Nilai (Baik/Sangat Baik)
            $table->text('description')->nullable(); // Keterangan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extracurricular_records');
        Schema::dropIfExists('grade_items');
        Schema::dropIfExists('grade_records');
    }
};