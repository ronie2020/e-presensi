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
        // 1. Update ENUM status untuk menyertakan 'Uzur Syar'i'
        DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN status ENUM('Hadir', 'Sakit', 'Izin', 'Alfa', 'Terlambat', 'Uzur Syar\'i') NOT NULL");

        // 2. Ubah kolom time_in agar boleh NULL (nullable)
        // Ini agar input manual Izin/Uzur tidak error saat jam tidak diisi
        DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN time_in TIME NULL");

        // 3. Ubah juga kolom time_out jika ada agar boleh NULL
        // Berdasarkan log error Anda, kolom ini juga dikirimkan sebagai null
        try {
            DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN time_out TIME NULL");
        } catch (\Exception $e) {
            // Abaikan jika kolom time_out memang tidak ada di tabel Anda
        }
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        // Kembalikan ke definisi awal (Tanpa Uzur Syar'i dan NOT NULL)
        DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN status ENUM('Hadir', 'Sakit', 'Izin', 'Alfa', 'Terlambat') NOT NULL");
        
        // Kembalikan time_in menjadi NOT NULL (Hati-hati: ini akan error jika ada data NULL di DB)
        // DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN time_in TIME NOT NULL");
    }
};