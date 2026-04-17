<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Cek apakah tabel ada untuk menghindari error
        if (Schema::hasTable('schedules_regular')) {
            Schema::table('schedules_regular', function (Blueprint $table) {
                // Cek apakah kolom day_name belum ada
                if (!Schema::hasColumn('schedules_regular', 'day_name')) {
                    // Tambahkan kolom day_name setelah id
                    // Tipe string, nullable (boleh kosong) untuk antisipasi data lama
                    $table->string('day_name', 20)->nullable()->after('id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasTable('schedules_regular')) {
            Schema::table('schedules_regular', function (Blueprint $table) {
                if (Schema::hasColumn('schedules_regular', 'day_name')) {
                    $table->dropColumn('day_name');
                }
            });
        }
    }
};