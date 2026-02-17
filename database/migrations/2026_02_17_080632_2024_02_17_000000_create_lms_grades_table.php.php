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
        Schema::create('lms_grades', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Tugas/Ujian
            $table->unsignedBigInteger('lms_assignment_id');
            
            // Relasi ke Siswa
            $table->unsignedBigInteger('student_id');
            
            // Kolom Nilai
            $table->integer('score')->default(0);
            $table->string('status')->default('graded'); // graded, remedial, etc.
            $table->timestamp('graded_at')->nullable();
            
            $table->timestamps();

            // Foreign Keys (Opsional, aktifkan jika perlu strict relation)
            // $table->foreign('lms_assignment_id')->references('id')->on('lms_assignments')->onDelete('cascade');
            // $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_grades');
    }
};