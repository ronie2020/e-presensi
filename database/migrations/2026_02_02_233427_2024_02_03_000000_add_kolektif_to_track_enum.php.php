<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Menambahkan opsi 'kolektif' ke dalam kolom ENUM track
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE ppdb_registrants MODIFY COLUMN track ENUM('zonasi', 'prestasi', 'afirmasi', 'pindah_tugas', 'kolektif') NOT NULL");
        }
    }

    /**
     * Mengembalikan ke kondisi semula
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE ppdb_registrants MODIFY COLUMN track ENUM('zonasi', 'prestasi', 'afirmasi', 'pindah_tugas') NOT NULL");
        }
    }
};