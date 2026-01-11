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
            // Kita gunakan ->nullable() karena tidak semua siswa mungkin mengisi ini setiap hari
            
            if (!Schema::hasColumn('student_habits', 'odoa_surah')) {
                $table->string('odoa_surah')->nullable()->after('prayer_isya');
            }
            
            if (!Schema::hasColumn('student_habits', 'odoa_ayat')) {
                $table->string('odoa_ayat')->nullable()->after('odoa_surah');
            }
            
            if (!Schema::hasColumn('student_habits', 'odoa_audio_path')) {
                $table->string('odoa_audio_path')->nullable()->after('odoa_ayat');
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