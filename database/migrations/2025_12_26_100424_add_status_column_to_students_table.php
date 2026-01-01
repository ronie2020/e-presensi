<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Cek dulu biar tidak error kalau kolomnya ternyata sudah ada
            if (!Schema::hasColumn('students', 'status')) {
                // [PERBAIKAN] Ubah 'join_date' menjadi 'id' agar aman
                $table->string('status')->default('active')->after('id');
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