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
        Schema::table('learning_schedules', function (Blueprint $table) {
            // Menambahkan kolom day_type setelah kolom id.
            // Default diisi 'Selasa-Kamis' agar data lama tidak error (bisa diubah nanti di UI).
            $table->string('day_type', 50)->default('Selasa-Kamis')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_schedules', function (Blueprint $table) {
            $table->dropColumn('day_type');
        });
    }
};