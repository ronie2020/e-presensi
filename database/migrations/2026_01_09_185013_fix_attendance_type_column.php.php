<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Perbaikan: Mengubah kolom 'type' menjadi VARCHAR(50) agar bisa menampung "Keagamaan", "Makan", dll.
        // Kita menggunakan Raw SQL agar lebih pasti berhasil tanpa dependency tambahan.
        
        $tableName = 'attendances_siswa'; // Sesuai error log Anda

        // Cek dulu apakah tabelnya pakai nama plural standar Laravel
        if (Schema::hasTable('attendance_siswas')) {
            $tableName = 'attendance_siswas';
        }

        DB::statement("ALTER TABLE `$tableName` MODIFY COLUMN `type` VARCHAR(50) NOT NULL");
    }

    public function down()
    {
        // Tidak perlu rollback
    }
};