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
        Schema::table('achievements', function (Blueprint $table) {
            // Menambahkan kolom certificate_path setelah kolom video_link
            $table->string('certificate_path')->nullable()->after('video_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            // Menghapus kolom certificate_path jika migrasi di-rollback
            $table->dropColumn('certificate_path');
        });
    }
};