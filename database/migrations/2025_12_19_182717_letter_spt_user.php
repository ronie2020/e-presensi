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
        // Cek dulu apakah tabelnya sudah ada biar tidak error jika dijalankan ulang
        if (!Schema::hasTable('letter_spt_user')) {
            Schema::create('letter_spt_user', function (Blueprint $table) {
                $table->id();
                
                // Relasi ke tabel SPT (letter_spts)
                // Jika data SPT dihapus, maka data penugasan ini juga ikut terhapus (cascade)
                $table->foreignId('letter_spt_id')
                      ->constrained('letter_spts')
                      ->onDelete('cascade');
                
                // Relasi ke tabel Users/Pegawai
                // Jika User dihapus, data penugasan ini juga ikut terhapus (cascade)
                $table->foreignId('user_id')
                      ->constrained('users')
                      ->onDelete('cascade');

                // Opsional: Timestamps jika ingin mencatat kapan user ditugaskan
                // $table->timestamps(); 
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_spt_user');
    }
};