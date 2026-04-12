<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel bk_chats.
     */
    public function up(): void
    {
        Schema::create('bk_chats', function (Blueprint $blueprint) {
            $blueprint->id();
            
            // Relasi ke tiket konseling
            $blueprint->foreignId('bk_session_id')
                  ->constrained('bk_sessions')
                  ->onDelete('cascade');

            // Pengirim (Bisa Guru/User atau Siswa)
            $blueprint->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $blueprint->foreignId('student_id')->nullable()->constrained('students')->onDelete('set null');

            // Konten Pesan
            $blueprint->text('message');
            $blueprint->string('sender_type'); // 'teacher' atau 'student'
            
            $blueprint->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('bk_chats');
    }
};