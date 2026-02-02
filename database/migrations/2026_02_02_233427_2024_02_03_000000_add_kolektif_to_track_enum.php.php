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
        // Menggunakan Raw SQL agar kompatibel dengan modifikasi ENUM di MySQL
        DB::statement("ALTER TABLE ppdb_registrants MODIFY COLUMN track ENUM('zonasi', 'prestasi', 'afirmasi', 'pindah_tugas', 'kolektif') NOT NULL");
    }

    /**
     * Mengembalikan ke kondisi semula
     */
    public function down(): void
    {
        // Peringatan: Data dengan track 'kolektif' mungkin akan error/hilang jika di-rollback
        DB::statement("ALTER TABLE ppdb_registrants MODIFY COLUMN track ENUM('zonasi', 'prestasi', 'afirmasi', 'pindah_tugas') NOT NULL");
    }
};