<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kita gunakan Raw SQL agar lebih aman dan kompatibel untuk mengubah ENUM
        // Mengubah kolom 'type' untuk menambahkan 'Masuk' dan 'Pulang'
        DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN type ENUM('Harian', 'Dhuha', 'Dhuhur', 'Masuk', 'Pulang') DEFAULT 'Harian'");

        // Mengubah kolom 'status' untuk menambahkan 'Terlambat'
        DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN status ENUM('Hadir', 'Sakit', 'Izin', 'Alfa', 'Terlambat')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke kondisi semula jika di-rollback
        // Hati-hati: Data 'Masuk', 'Pulang', atau 'Terlambat' akan error/hilang jika di-rollback
        DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN type ENUM('Harian', 'Dhuha', 'Dhuhur') DEFAULT 'Harian'");
        DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN status ENUM('Hadir', 'Sakit', 'Izin', 'Alfa')");
    }
};