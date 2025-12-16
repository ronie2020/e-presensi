<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <--- INI YANG KURANG SEBELUMNYA

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Menggunakan Raw SQL untuk mengubah kolom ENUM agar menerima 'Terlambat'
        // Pastikan nama tabel benar 'attendances_siswa'
        DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN status ENUM('Hadir', 'Sakit', 'Izin', 'Alfa', 'Terlambat') DEFAULT 'Hadir'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke kondisi semula jika di-rollback
        DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN status ENUM('Hadir', 'Sakit', 'Izin', 'Alfa') DEFAULT 'Hadir'");
    }
};