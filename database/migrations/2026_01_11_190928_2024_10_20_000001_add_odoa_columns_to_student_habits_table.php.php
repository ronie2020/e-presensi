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
        Schema::table('student_habits', function (Blueprint $table) {
            // Menambahkan kolom untuk fitur ODOA (One Day One Ayat)
            // Saya menghapus '->after(...)' agar tidak error jika kolom referensi tidak ada
            
            if (!Schema::hasColumn('student_habits', 'odoa_surah')) {
                $table->string('odoa_surah')->nullable();
            }
            
            if (!Schema::hasColumn('student_habits', 'odoa_ayat')) {
                $table->string('odoa_ayat')->nullable();
            }
            
            if (!Schema::hasColumn('student_habits', 'odoa_audio_path')) {
                $table->string('odoa_audio_path')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_habits', function (Blueprint $table) {
            $table->dropColumn(['odoa_surah', 'odoa_ayat', 'odoa_audio_path']);
        });
    }
};