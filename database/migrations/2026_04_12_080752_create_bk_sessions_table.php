<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambah kolom rating.
     */
    public function up(): void
    {
        Schema::table('bk_sessions', function (Blueprint $table) {
            // Menambahkan kolom rating, feedback, dan timestamp penilaian
            if (!Schema::hasColumn('bk_sessions', 'rating')) {
                $table->integer('rating')->nullable()->after('response_message');
            }
            if (!Schema::hasColumn('bk_sessions', 'student_feedback')) {
                $table->text('student_feedback')->nullable()->after('rating');
            }
            if (!Schema::hasColumn('bk_sessions', 'feedback_at')) {
                $table->timestamp('feedback_at')->nullable()->after('student_feedback');
            }
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('bk_sessions', function (Blueprint $table) {
            $table->dropColumn(['rating', 'student_feedback', 'feedback_at']);
        });
    }
};