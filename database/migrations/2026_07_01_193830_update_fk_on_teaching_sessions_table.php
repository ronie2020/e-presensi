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
        Schema::disableForeignKeyConstraints();

        // Cari semua Foreign Key constraint yang menempel pada kolom schedule_id di teaching_sessions
        $foreignKeys = \Illuminate\Support\Facades\DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = 'teaching_sessions' 
              AND COLUMN_NAME = 'schedule_id' 
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        Schema::table('teaching_sessions', function (Blueprint $table) use ($foreignKeys) {
            foreach ($foreignKeys as $fk) {
                $table->dropForeign($fk->CONSTRAINT_NAME);
            }

            // Buat ikatan Foreign Key yang baru menunjuk ke tabel 'timetables'
            $table->foreign('schedule_id')
                  ->references('id')
                  ->on('timetables')
                  ->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Kembalikan seperti semula jika sewaktu-waktu di-rollback (Downgrade ke Schedules)
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        $foreignKeys = \Illuminate\Support\Facades\DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = 'teaching_sessions' 
              AND COLUMN_NAME = 'schedule_id' 
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        Schema::table('teaching_sessions', function (Blueprint $table) use ($foreignKeys) {
            foreach ($foreignKeys as $fk) {
                $table->dropForeign($fk->CONSTRAINT_NAME);
            }

            // Kembalikan ikatan ke schedules (tabel lama)
            $table->foreign('schedule_id')
                  ->references('id')
                  ->on('schedules')
                  ->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }
};