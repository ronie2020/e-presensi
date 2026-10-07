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
        Schema::table('teaching_sessions', function (Blueprint $table) {
            // Status apakah materi sudah tuntas disampaikan di pertemuan ini
            $table->enum('material_status', ['selesai', 'belum_selesai'])->nullable()->after('activities');
            // Catatan progress materi (sejauh mana materi disampaikan, agar pertemuan berikutnya tahu harus lanjut dari mana)
            $table->text('material_coverage')->nullable()->after('material_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teaching_sessions', function (Blueprint $table) {
            $table->dropColumn(['material_status', 'material_coverage']);
        });
    }
};
