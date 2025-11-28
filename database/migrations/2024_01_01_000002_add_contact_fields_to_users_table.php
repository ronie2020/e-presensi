<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom kontak setelah NIP
            $table->string('phone')->nullable()->after('nip'); // No WA/HP
            $table->string('instagram')->nullable()->after('phone');
            $table->string('tiktok')->nullable()->after('instagram');
            $table->string('facebook')->nullable()->after('tiktok');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'instagram', 'tiktok', 'facebook']);
        });
    }
};