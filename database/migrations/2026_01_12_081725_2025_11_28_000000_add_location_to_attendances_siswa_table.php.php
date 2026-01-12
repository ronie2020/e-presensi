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
            // Menambahkan kolom koordinat lokasi yang dipakai di Controller
            $table->string('lat_in')->nullable()->after('time_in');
            $table->string('long_in')->nullable()->after('lat_in');
            
            $table->string('lat_out')->nullable()->after('time_out');
            $table->string('long_out')->nullable()->after('lat_out');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances_siswa', function (Blueprint $table) {
            $table->dropColumn(['lat_in', 'long_in', 'lat_out', 'long_out']);
        });
    }
};