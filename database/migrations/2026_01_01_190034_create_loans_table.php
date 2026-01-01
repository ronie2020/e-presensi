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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel Siswa (students)
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            // Relasi ke tabel Buku (books)
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            
            $table->date('loan_date');              // Tanggal Pinjam
            $table->date('due_date');               // Tanggal Jatuh Tempo
            $table->date('return_date')->nullable();// Tanggal Kembali
            $table->decimal('fine', 10, 2)->default(0); // Denda
            
            $table->string('status')->default('borrowed'); // borrowed, returned, lost
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};