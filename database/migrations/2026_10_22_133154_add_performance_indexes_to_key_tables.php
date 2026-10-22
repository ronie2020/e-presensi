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
        // 1. Indeks Komposit untuk Absensi Siswa (Optimasi Jam 07:00 Pagi)
        if (Schema::hasTable('attendances_siswa')) {
            Schema::table('attendances_siswa', function (Blueprint $table) {
                $table->index(['attendance_date', 'type'], 'idx_attendances_date_type');
                $table->index(['attendance_date', 'status'], 'idx_attendances_date_status');
                $table->index(['student_id', 'attendance_date'], 'idx_attendances_student_date');
            });
        }

        // 2. Indeks Komposit untuk Sesi Ujian Siswa (Optimasi Real-time Polling CBT)
        if (Schema::hasTable('cbt_student_exams')) {
            Schema::table('cbt_student_exams', function (Blueprint $table) {
                $table->index(['cbt_exam_id', 'student_id'], 'idx_cbt_exam_student');
                $table->index(['cbt_exam_id', 'status'], 'idx_cbt_exam_status');
            });
        }

        // 3. Indeks Komposit untuk Jawaban Siswa CBT (Pencarian & Autosave Jawaban Kilat)
        if (Schema::hasTable('cbt_student_answers')) {
            Schema::table('cbt_student_answers', function (Blueprint $table) {
                $table->index(['cbt_student_exam_id', 'cbt_question_id'], 'idx_cbt_answers_exam_question');
            });
        }

        // 4. Indeks Komposit untuk Siswa Aktif per Kelas
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                $table->index(['status', 'class_id'], 'idx_students_status_class');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('attendances_siswa')) {
            Schema::table('attendances_siswa', function (Blueprint $table) {
                $table->dropIndex('idx_attendances_date_type');
                $table->dropIndex('idx_attendances_date_status');
                $table->dropIndex('idx_attendances_student_date');
            });
        }

        if (Schema::hasTable('cbt_student_exams')) {
            Schema::table('cbt_student_exams', function (Blueprint $table) {
                $table->dropIndex('idx_cbt_exam_student');
                $table->dropIndex('idx_cbt_exam_status');
            });
        }

        if (Schema::hasTable('cbt_student_answers')) {
            Schema::table('cbt_student_answers', function (Blueprint $table) {
                $table->dropIndex('idx_cbt_answers_exam_question');
            });
        }

        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropIndex('idx_students_status_class');
            });
        }
    }
};
