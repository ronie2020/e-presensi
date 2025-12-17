<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Update tabel Assignment untuk dukung Tipe
        Schema::table('lms_assignments', function (Blueprint $table) {
            // 'file_upload' (default), 'quiz' (soal di web), 'link' (quizizz dll)
            $table->string('assignment_type')->default('file_upload')->after('description'); 
            $table->string('link_url')->nullable()->after('assignment_type'); // Untuk Quizizz/GForm
            $table->integer('duration_minutes')->nullable()->after('deadline'); // Durasi pengerjaan kuis
        });

        // 2. Buat tabel Bank Soal (Untuk tipe 'quiz')
        Schema::create('lms_quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('lms_assignments')->onDelete('cascade');
            $table->longText('question_text'); // Soal
            $table->string('question_type'); // 'multiple_choice', 'essay'
            
            // Opsi Jawaban (Disimpan sbg JSON biar simpel: ["A"=>"...", "B"=>"..."])
            $table->json('options')->nullable(); 
            
            $table->string('correct_answer')->nullable(); // Kunci Jawaban (A, B, C, D)
            $table->integer('points')->default(10); // Bobot nilai
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lms_quiz_questions');
        Schema::table('lms_assignments', function (Blueprint $table) {
            $table->dropColumn(['assignment_type', 'link_url', 'duration_minutes']);
        });
    }
};