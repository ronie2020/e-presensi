<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration (Update ke Timetables)
     */
    public function up(): void
    {
        Schema::table('teaching_sessions', function (Blueprint $table) {
            // 1. Hapus ikatan Foreign Key yang lama
            // Sesuai dengan pesan error Anda, nama constraint-nya adalah: teaching_sessions_schedule_id_foreign
            $table->dropForeign('teaching_sessions_schedule_id_foreign');
            
            // 2. Buat ikatan Foreign Key yang baru menunjuk ke tabel 'timetables'
            $table->foreign('schedule_id')
                  ->references('id')
                  ->on('timetables')
                  ->onDelete('cascade');
        });
    }

    /**
     * Kembalikan seperti semula jika sewaktu-waktu di-rollback (Downgrade ke Schedules)
     */
    public function down(): void
    {
        Schema::table('teaching_sessions', function (Blueprint $table) {
            // Hapus ikatan ke timetables
            $table->dropForeign(['schedule_id']);
            
            // Kembalikan ikatan ke schedules (tabel lama)
            $table->foreign('schedule_id')
                  ->references('id')
                  ->on('schedules')
                  ->onDelete('cascade');
        });
    }
};