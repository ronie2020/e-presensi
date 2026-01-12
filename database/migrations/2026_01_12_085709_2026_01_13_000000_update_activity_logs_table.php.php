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
        Schema::table('activity_logs', function (Blueprint $table) {
            // 1. Tambah kolom activity_type (Meal, Religious, Extracurricular)
            if (!Schema::hasColumn('activity_logs', 'activity_type')) {
                $table->string('activity_type')->after('id')->nullable()->index();
            }

            // 2. Tambah kolom activity_name (Shalat Dhuha, Menu MBG, dll)
            if (!Schema::hasColumn('activity_logs', 'activity_name')) {
                $table->string('activity_name')->after('activity_type')->nullable();
            }

            // 3. Tambah kolom point_earned (Poin penghargaan)
            if (!Schema::hasColumn('activity_logs', 'point_earned')) {
                $table->integer('point_earned')->default(0)->after('description');
            }

            // 4. Pastikan kolom student_id ada (relasi ke siswa)
            // Jika tabel activity_logs sebelumnya belum punya student_id, kita buat sekalian
            if (!Schema::hasColumn('activity_logs', 'student_id')) {
                $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['activity_type', 'activity_name', 'point_earned', 'student_id']);
        });
    }
};