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
        // 1. Tambah kolom audio pada cbt_questions
        if (Schema::hasTable('cbt_questions')) {
            Schema::table('cbt_questions', function (Blueprint $table) {
                if (!Schema::hasColumn('cbt_questions', 'audio_path')) {
                    $table->string('audio_path')->nullable()->after('question_image');
                }
                if (!Schema::hasColumn('cbt_questions', 'audio_play_limit')) {
                    $table->integer('audio_play_limit')->default(2)->after('audio_path');
                }
            });
        }

        // 2. Tambah kolom audio pada cbt_question_banks jika ada
        if (Schema::hasTable('cbt_question_banks')) {
            Schema::table('cbt_question_banks', function (Blueprint $table) {
                if (!Schema::hasColumn('cbt_question_banks', 'audio_path')) {
                    $table->string('audio_path')->nullable();
                }
                if (!Schema::hasColumn('cbt_question_banks', 'audio_play_limit')) {
                    $table->integer('audio_play_limit')->default(2);
                }
            });
        }

        // 3. Tambah tracking latency & last_seen pada cbt_student_exams
        if (Schema::hasTable('cbt_student_exams')) {
            Schema::table('cbt_student_exams', function (Blueprint $table) {
                if (!Schema::hasColumn('cbt_student_exams', 'latency_ms')) {
                    $table->integer('latency_ms')->nullable()->after('ip_address');
                }
                if (!Schema::hasColumn('cbt_student_exams', 'last_seen_at')) {
                    $table->timestamp('last_seen_at')->nullable()->after('latency_ms');
                }
            });
        }

        // 4. Tabel Pesan Siaran & Teguran Proktor (cbt_messages)
        if (!Schema::hasTable('cbt_messages')) {
            Schema::create('cbt_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cbt_exam_id')->constrained('cbt_exams')->onDelete('cascade');
                $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('cascade');
                $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null');
                $table->text('message');
                $table->enum('type', ['info', 'warning', 'urgent'])->default('info');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_messages');

        if (Schema::hasTable('cbt_student_exams')) {
            Schema::table('cbt_student_exams', function (Blueprint $table) {
                if (Schema::hasColumn('cbt_student_exams', 'latency_ms')) {
                    $table->dropColumn(['latency_ms', 'last_seen_at']);
                }
            });
        }

        if (Schema::hasTable('cbt_question_banks')) {
            Schema::table('cbt_question_banks', function (Blueprint $table) {
                if (Schema::hasColumn('cbt_question_banks', 'audio_path')) {
                    $table->dropColumn(['audio_path', 'audio_play_limit']);
                }
            });
        }

        if (Schema::hasTable('cbt_questions')) {
            Schema::table('cbt_questions', function (Blueprint $table) {
                if (Schema::hasColumn('cbt_questions', 'audio_path')) {
                    $table->dropColumn(['audio_path', 'audio_play_limit']);
                }
            });
        }
    }
};

