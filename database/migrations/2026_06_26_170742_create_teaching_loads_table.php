<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_loads', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel eksisting Anda
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            
            $table->integer('hours_per_week'); // Jumlah JP dalam seminggu (Misal: 4)
            
            $table->timestamps();
            
            // Mencegah duplikasi data: 1 Guru hanya punya 1 beban untuk 1 Mapel di 1 Kelas yang sama
            $table->unique(['teacher_id', 'subject_id', 'class_id']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_loads');
    }
};