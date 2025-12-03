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
        // 1. Tabel Ujian (CBT Exams)
        Schema::create('cbt_exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subject_name');
            $table->string('class_level');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('duration_minutes');
            $table->integer('passing_grade');
            $table->string('token', 6)->nullable(); // Kolom Token sudah ada di sini
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // 2. Tabel Bank Soal (CBT Questions)
        Schema::create('cbt_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_exam_id')->constrained('cbt_exams')->onDelete('cascade');
            $table->text('question_text');
            $table->string('question_image')->nullable();
            $table->json('options'); // Opsi A, B, C, D disimpan sebagai JSON
            $table->string('correct_answer');
            $table->integer('score_weight')->default(1);
            $table->timestamps();
        });

        // 3. Tabel Sesi Ujian Siswa (CBT Student Exams)
        Schema::create('cbt_student_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_exam_id')->constrained('cbt_exams')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->decimal('total_score', 8, 2)->nullable();
            $table->enum('status', ['ongoing', 'finished'])->default('ongoing');
            $table->timestamps();
        });

        // 4. Tabel Jawaban Siswa (CBT Student Answers)
        Schema::create('cbt_student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_student_exam_id')->constrained('cbt_student_exams')->onDelete('cascade');
            $table->foreignId('cbt_question_id')->constrained('cbt_questions')->onDelete('cascade');
            $table->string('answer')->nullable(); // Jawaban siswa (A/B/C/D)
            $table->boolean('is_correct')->nullable(); // Status benar/salah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_student_answers');
        Schema::dropIfExists('cbt_student_exams');
        Schema::dropIfExists('cbt_questions');
        Schema::dropIfExists('cbt_exams');
    }
};