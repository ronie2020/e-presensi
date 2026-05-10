<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lms_material_logs', function (Blueprint $table) {
            $table->id();
            // Menyambungkan ke ID Siswa
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            // Menyambungkan ke ID Materi
            $table->foreignId('material_id')->constrained('lms_materials')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_material_logs');
    }
};
