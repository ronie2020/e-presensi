<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN status ENUM('Hadir', 'Sakit', 'Izin', 'Alfa', 'Terlambat', 'Uzur Syar\'i') NOT NULL");
            DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN time_in TIME NULL");
            try {
                DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN time_out TIME NULL");
            } catch (\Exception $e) {
                // Abaikan jika kolom time_out memang tidak ada di tabel Anda
            }
        }
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN status ENUM('Hadir', 'Sakit', 'Izin', 'Alfa', 'Terlambat') NOT NULL");
        }
    }
};