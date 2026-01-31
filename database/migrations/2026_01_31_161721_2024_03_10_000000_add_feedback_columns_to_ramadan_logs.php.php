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
        Schema::table('ramadan_logs', function (Blueprint $table) {
            // Kolom untuk Laporan Jumat (Pastikan belum ada sebelumnya)
            if (!Schema::hasColumn('ramadan_logs', 'friday_khotib')) {
                $table->string('friday_khotib')->nullable();
            }
            if (!Schema::hasColumn('ramadan_logs', 'friday_summary')) {
                $table->text('friday_summary')->nullable();
            }

            // Kolom untuk Feedback/Nilai Guru
            if (!Schema::hasColumn('ramadan_logs', 'teacher_score')) {
                $table->integer('teacher_score')->nullable();
            }
            if (!Schema::hasColumn('ramadan_logs', 'teacher_note')) {
                $table->text('teacher_note')->nullable();
            }
            if (!Schema::hasColumn('ramadan_logs', 'teacher_verified_at')) {
                $table->timestamp('teacher_verified_at')->nullable();
            }
            if (!Schema::hasColumn('ramadan_logs', 'teacher_id')) {
                $table->unsignedBigInteger('teacher_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ramadan_logs', function (Blueprint $table) {
            $table->dropColumn([
                'friday_khotib', 
                'friday_summary', 
                'teacher_score', 
                'teacher_note', 
                'teacher_verified_at',
                'teacher_id'
            ]);
        });
    }
};