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
        Schema::table('users', function (Blueprint $table) {
            // Kita cek dulu agar tidak error jika kolom sudah ada
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('Guru')->after('email');
            }
            if (!Schema::hasColumn('users', 'position')) {
                $table->string('position')->nullable()->after('role'); // Jabatan
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('position'); // Bio
            }
            if (!Schema::hasColumn('users', 'photo_path')) {
                $table->string('photo_path')->nullable()->after('bio'); // Foto
            }
             if (!Schema::hasColumn('users', 'nip')) {
                $table->string('nip')->nullable()->after('bio'); // NIP
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'position', 'bio', 'photo_path', 'nip']);
        });
    }
};