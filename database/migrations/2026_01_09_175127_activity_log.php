<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // CEK: Jika tabel activity_logs BELUM ADA, kita buat dulu
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->string('activity_type')->nullable()->index();
                $table->string('activity_name')->nullable();
                $table->text('description')->nullable();
                $table->integer('point_earned')->default(0);
                $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('cascade');
                $table->timestamps();
            });
        } else {
            // Jika tabel SUDAH ADA, kita baru tambah kolom-kolomnya
            Schema::table('activity_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('activity_logs', 'activity_type')) {
                    $table->string('activity_type')->after('id')->nullable()->index();
                }
                if (!Schema::hasColumn('activity_logs', 'activity_name')) {
                    $table->string('activity_name')->after('activity_type')->nullable();
                }
                if (!Schema::hasColumn('activity_logs', 'point_earned')) {
                    $table->integer('point_earned')->default(0)->after('description');
                }
                if (!Schema::hasColumn('activity_logs', 'student_id')) {
                    $table->foreignId('student_id')->after('id')->nullable()->constrained('students')->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        // Jika kita ingin menghapus total saat rollback
        // Schema::dropIfExists('activity_logs');
        
        // Atau hanya hapus kolomnya saja jika tabelnya tetap ingin ada
        if (Schema::hasTable('activity_logs')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                if (Schema::hasColumn('activity_logs', 'student_id')) {
                    $table->dropForeign(['student_id']);
                }
                $table->dropColumn(['activity_type', 'activity_name', 'point_earned', 'student_id']);
            });
        }
    }
};