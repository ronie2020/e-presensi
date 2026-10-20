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
        if (!Schema::hasTable('lms_quiz_drafts')) {
            Schema::create('lms_quiz_drafts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('assignment_id')->constrained('lms_assignments')->onDelete('cascade');
                $table->foreignId('question_id')->constrained('lms_quiz_questions')->onDelete('cascade');
                $table->longText('answer_text')->nullable();
                $table->timestamps();

                $table->unique(['student_id', 'assignment_id', 'question_id'], 'lms_quiz_draft_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_quiz_drafts');
    }
};
