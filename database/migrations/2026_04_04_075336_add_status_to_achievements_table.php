<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            // Tambahkan kolom status dengan default 'approved' (agar data lama otomatis dianggap valid)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};