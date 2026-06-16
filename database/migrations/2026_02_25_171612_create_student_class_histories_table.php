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
        Schema::create('student_class_histories', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke siswa: Jika siswa dihapus, wajar jika riwayatnya ikut dihapus
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            
            // Relasi ke kelas: Dibuat nullable dan set null agar riwayat aman jika kelas dihapus
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('set null');
            
            // Tahun ajaran saat siswa tersebut berada di kelas ini
            $table->string('academic_year'); // Contoh: "2023/2024"
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_class_histories');
    }
};