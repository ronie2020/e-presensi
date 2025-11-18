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
        // Tabel ini untuk menyimpan "Jadwal Hari Biasa" dan "Jadwal Hari Jum'at"
        Schema::create('schedules_regular', function (Blueprint $table) {
            $table->id();
            
            // Kolom untuk membedakan 'Senin-Kamis' atau 'Jumat'
            $table->string('day_type')->unique(); 
            
            $table->time('start_in'); // Masuk Mulai
            $table->time('end_in');   // Masuk Akhir
            $table->time('start_out'); // Pulang Mulai
            $table->time('end_out');   // Pulang Akhir
            
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules_regular');
    }
};