<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ebook_reads', function (Blueprint $table) {
            $table->id();
            // Buku yang dibaca
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            
            // Siswa yang membaca (Nullable: untuk jaga-jaga jika ada mode tamu, tapi idealnya tercatat)
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            
            // Waktu baca (created_at otomatis mencatat timestamp)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebook_reads');
    }
};