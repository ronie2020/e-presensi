<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->string('day_of_week'); // Contoh: "Senin", "Selasa"
            
            // Relasi
            $table->foreignId('timeslot_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            
            $table->enum('status', ['draft', 'published'])->default('published');
            
            $table->timestamps();
            
            // Mencegah 1 kelas memiliki 2 mata pelajaran yang berbeda di hari dan jam yang sama
            $table->unique(['day_of_week', 'timeslot_id', 'class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};