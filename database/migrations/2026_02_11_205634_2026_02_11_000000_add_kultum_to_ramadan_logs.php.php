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
        Schema::table('ramadan_logs', function (Blueprint $table) {
            // Menambahkan kolom setelah 'sunnah_deeds' agar rapi
            $table->string('kultum_penceramah')->nullable()->after('sunnah_deeds');
            $table->text('kultum_summary')->nullable()->after('kultum_penceramah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ramadan_logs', function (Blueprint $table) {
            $table->dropColumn(['kultum_penceramah', 'kultum_summary']);
        });
    }
};