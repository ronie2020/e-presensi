<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('schedules')) {
            Schema::create('schedules', function (Blueprint $table) {
                $table->id();
                
                // Urutan dependensi sudah aman sekarang:
                // 1. classes (dibuat tgl 14)
                $table->foreignId('school_class_id')->constrained('classes')->onDelete('cascade');
                
                // 2. subjects (dibuat tgl 26) - KITA ADA DI TGL 27 SEKARANG, JADI AMAN
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                
                $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
                
                $table->enum('day', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
                $table->time('start_time');
                $table->time('end_time');
                
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};