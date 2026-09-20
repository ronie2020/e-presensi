<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom yang hilang di ppdb_registrants:
     * 1. parent_income      - Penghasilan orang tua
     * 2. student_phone      - Nomor WA siswa (opsional)
     * 3. achievement_type   - Jenis prestasi (akademik/non_akademik)
     * 4. achievement_name   - Nama kejuaraan
     * 5. achievement_level  - Tingkat kejuaraan
     * 6. achievement_rank   - Peringkat kejuaraan
     * 7. file_achievement   - File sertifikat kejuaraan
     * 8. grades_detail      - Nilai per mapel dalam format JSON
     */
    public function up(): void
    {
        Schema::table('ppdb_registrants', function (Blueprint $table) {
            // Penghasilan orang tua (setelah parent_job)
            $table->string('parent_income')->nullable()->after('parent_job');

            // Nomor WA siswa (setelah parent_income)
            $table->string('student_phone', 15)->nullable()->after('parent_income');

            // Data prestasi (setelah distance_in_meters)
            $table->string('achievement_type')->nullable()->after('distance_in_meters');
            $table->string('achievement_name')->nullable()->after('achievement_type');
            $table->string('achievement_level')->nullable()->after('achievement_name');
            $table->string('achievement_rank')->nullable()->after('achievement_level');

            // File sertifikat kejuaraan (setelah file_kip)
            $table->string('file_achievement')->nullable()->after('file_kip');

            // Detail nilai per mata pelajaran dalam JSON (setelah average_grade)
            $table->json('grades_detail')->nullable()->after('average_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_registrants', function (Blueprint $table) {
            $table->dropColumn([
                'parent_income',
                'student_phone',
                'achievement_type',
                'achievement_name',
                'achievement_level',
                'achievement_rank',
                'file_achievement',
                'grades_detail',
            ]);
        });
    }
};
