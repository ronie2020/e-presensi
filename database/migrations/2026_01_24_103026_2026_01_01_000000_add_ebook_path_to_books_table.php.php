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
        // Kita menggunakan Schema::table (bukan create) untuk mengedit tabel yg sudah ada
        Schema::table('books', function (Blueprint $table) {
            // Cek dulu apakah kolom sudah ada (untuk menghindari error duplicate column)
            if (!Schema::hasColumn('books', 'ebook_path')) {
                $table->string('ebook_path')->nullable()->after('cover_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'ebook_path')) {
                $table->dropColumn('ebook_path');
            }
        });
    }
};