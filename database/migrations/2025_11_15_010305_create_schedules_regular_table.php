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
        Schema::create('schedules_regular', function (Blueprint $table) {
            $table->id();
            
            // --- PERBAIKAN DI SINI ---
            // Ganti 'day_type' menjadi 'day_name' agar cocok dengan Controller
            // Kolom ini akan diisi: Monday, Tuesday, Wednesday, dst.
            $table->string('day_name')->unique(); 
            
            $table->time('start_in'); 
            $table->time('end_in');   
            $table->time('start_out'); 
            $table->time('end_out');   
            
            // Opsional: Aktifkan timestamps jika perlu, tapi di kode Anda dikomentari
            // $table->timestamps();
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