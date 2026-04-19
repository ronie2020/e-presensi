<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_copies', function (Blueprint $table) {
            $table->id();
            
            // Relasi Induk: Jika buku induk dihapus, semua eksemplar ikut terhapus (cascade)
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            
            // Barcode unik per fisik buku (Misal: B24-15-001)
            $table->string('copy_code')->unique(); 
            
            // Status masing-masing buku fisik
            $table->enum('status', ['available', 'borrowed', 'lost', 'damaged'])->default('available'); 
            
            // Catatan kondisi fisik (Misal: Sampul terlipat, Halaman 5 hilang)
            $table->string('condition')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_copies');
    }
};