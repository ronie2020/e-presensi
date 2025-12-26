<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Perintah SQL langsung untuk mengubah kolom menjadi BOLEH KOSONG (NULL)
        // Kita asumsikan tipe datanya BIGINT UNSIGNED (standar foreignId di Laravel)
        DB::statement("ALTER TABLE students MODIFY class_id BIGINT UNSIGNED NULL");
    }

    public function down(): void
    {
        // Kembalikan ke TIDAK BOLEH KOSONG (NOT NULL) jika dibatalkan
        DB::statement("ALTER TABLE students MODIFY class_id BIGINT UNSIGNED NOT NULL");
    }
};