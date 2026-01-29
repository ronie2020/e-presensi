<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk tabel ramadan_logs.
     */
    public function up(): void
    {
        Schema::create('ramadan_logs', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel students
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            
            // Data harian
            $table->date('date');
            $table->boolean('is_fasting')->default(true);
            
            // Data ibadah dalam format JSON agar fleksibel
            $table->json('prayers')->nullable(); // {subuh: true, dzuhur: true, ...}
            $table->json('sunnah_deeds')->nullable(); // {tarawih: true, dhuha: true, ...}
            
            // Tilawah & Murojaah
            $table->string('tadarus_surah')->nullable();
            $table->integer('tadarus_ayah')->nullable();
            $table->string('murojaah_surah')->nullable();
            
            $table->timestamps();
            
            // Indeks untuk mempercepat pencarian harian
            $table->unique(['student_id', 'date']);
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('ramadan_logs');
    }
};