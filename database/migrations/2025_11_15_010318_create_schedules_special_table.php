<?php

// INI BAGIAN YANG DIPERBAIKI (menggunakan backslash \)
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
        // Tabel ini untuk "Jadwal Khusus & Hari Libur"
        Schema::create('schedules_special', function (Blueprint $table) {
            $table->id();
            
            $table->date('date')->unique(); // Hanya boleh ada 1 jadwal khusus per tanggal
            $table->string('description')->nullable(); // Misal: "Ujian Akhir Semester"
            
            // Sesuai checkbox "Tandai sebagai Hari Libur"
            $table->boolean('is_holiday')->default(false); 
            
            // Kolom jam ini boleh null, untuk jaga-jaga jika ditandai sbg hari libur
            $table->time('start_in')->nullable();
            $table->time('end_in')->nullable();
            $table->time('start_out')->nullable();
            $table->time('end_out')->nullable();
            
           // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules_special');
    }
};