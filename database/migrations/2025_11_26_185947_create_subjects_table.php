<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Matematika
            $table->string('code')->nullable(); // MTK
            $table->enum('group', ['A', 'B', 'C', 'P5'])->default('A'); // Kelompok Mapel (A=Umum, B=Mulok, P5=Projek)
            $table->integer('order')->default(0); // Urutan cetak di rapor
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};