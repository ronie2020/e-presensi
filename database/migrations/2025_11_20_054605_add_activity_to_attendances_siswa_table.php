<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendances_siswa', function (Blueprint $table) {
            // Menambahkan kolom 'activity' setelah kolom 'type'.
            // Kolom ini akan menyimpan 'Dhuha' atau 'Duhur' untuk absensi Keagamaan.
            $table->string('activity', 50)->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances_siswa', function (Blueprint $table) {
            // Ketika rollback, hapus kolom 'activity'.
            $table->dropColumn('activity');
        });
    }
};