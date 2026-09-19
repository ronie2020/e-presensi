<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['waiting', 'ready', 'borrowed', 'cancelled', 'expired'])->default('waiting');
            $table->timestamp('notified_at')->nullable(); // kapan siswa diberi tahu bukunya siap
            $table->timestamp('expires_at')->nullable();   // batas 3 hari untuk ambil
            $table->timestamps();

            $table->index(['book_id', 'status']);
            $table->index(['student_id', 'status']);
            // Satu siswa hanya boleh 1 reservasi aktif per buku
            $table->unique(['student_id', 'book_id', 'status'], 'unique_active_reservation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_reservations');
    }
};
