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
        // Kita gunakan nama tabel 'lms_submission_answers' agar lebih rapi.
        // Jika error sebelumnya mencari 'lms_quiz_answers', itu karena Modelnya mengarah ke sana.
        // Nanti kita perbaiki arah Modelnya ke tabel ini.
        Schema::create('lms_submission_answers', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel submission utama
            $table->foreignId('submission_id')
                  ->constrained('lms_submissions')
                  ->onDelete('cascade');
            
            // Relasi ke soal
            $table->foreignId('question_id')
                  ->constrained('lms_quiz_questions')
                  ->onDelete('cascade');
            
            // Menyimpan teks jawaban (termasuk essay panjang)
            $table->longText('answer_text')->nullable();
            
            // Nilai per butir soal
            $table->decimal('points', 8, 2)->default(0);
            
            // Status benar/salah (Null = belum dinilai/essay, 1 = benar, 0 = salah)
            $table->boolean('is_correct')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_submission_answers');
    }
};