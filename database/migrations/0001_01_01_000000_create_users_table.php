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
            // Menambahkan kolom 'role' jika belum ada
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('Siswa')->after('email'); 
                // Default Siswa, nanti diubah jadi Guru/Admin saat input
            }

            // Kolom Khusus Profil Guru
            $table->string('position')->nullable()->after('role'); // Jabatan (Misal: Guru Matematika)
            $table->string('photo_path')->nullable()->after('position'); // Path Foto
            $table->text('bio')->nullable()->after('photo_path'); // Bio Singkat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'position', 'photo_path', 'bio']);
        });
    }
};