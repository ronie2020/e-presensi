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
        // Tabel ini untuk menyimpan 'Jenis Pelanggaran' dan 'Jenis Kebaikan'
        // Beserta poinnya (sesuai gambar image_9795be.png)
        Schema::create('discipline_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Misal: "Terlambat 15 menit", "Mewakili sekolah lomba"
            
            // Untuk membedakan form (Pelanggaran / Kebaikan)
            $table->enum('type', ['Pelanggaran', 'Kebaikan']); 
            
            // Poin (negatif untuk pelanggaran, positif untuk kebaikan)
            $table->integer('point_value'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discipline_types');
    }
};