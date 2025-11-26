<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->date('date'); // Tanggal kunjungan
            $table->time('time'); // Jam masuk
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_visits');
    }
};