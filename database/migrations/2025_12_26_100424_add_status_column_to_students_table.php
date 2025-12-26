<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kita hanya mengupdate tabel 'students', BUKAN membuat tabel baru
        Schema::table('students', function (Blueprint $table) {
            // Cek dulu biar tidak error kalau kolomnya ternyata sudah ada
            if (!Schema::hasColumn('students', 'status')) {
                // Tambahkan kolom 'status' setelah kolom 'join_date'
                $table->string('status')->default('active')->after('join_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};