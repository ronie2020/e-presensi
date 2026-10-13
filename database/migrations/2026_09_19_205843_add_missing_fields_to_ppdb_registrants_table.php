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
            if (!Schema::hasColumn('ppdb_registrants', 'parent_income')) {
                $table->string('parent_income')->nullable()->after('parent_job');
            }

            // Nomor WA siswa (setelah parent_income)
            if (!Schema::hasColumn('ppdb_registrants', 'student_phone')) {
                $table->string('student_phone', 15)->nullable();
            }

            // Data prestasi (setelah distance_in_meters)
            if (!Schema::hasColumn('ppdb_registrants', 'achievement_type')) {
                $table->string('achievement_type')->nullable();
            }
            if (!Schema::hasColumn('ppdb_registrants', 'achievement_name')) {
                $table->string('achievement_name')->nullable();
            }
            if (!Schema::hasColumn('ppdb_registrants', 'achievement_level')) {
                $table->string('achievement_level')->nullable();
            }
            if (!Schema::hasColumn('ppdb_registrants', 'achievement_rank')) {
                $table->string('achievement_rank')->nullable();
            }

            // File sertifikat kejuaraan (setelah file_kip)
            if (!Schema::hasColumn('ppdb_registrants', 'file_achievement')) {
                $table->string('file_achievement')->nullable();
            }

            // Detail nilai per mata pelajaran dalam JSON (setelah average_grade)
            if (!Schema::hasColumn('ppdb_registrants', 'grades_detail')) {
                $table->json('grades_detail')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_registrants', function (Blueprint $table) {
            $columnsToDrop = array_filter([
                'parent_income',
                'student_phone',
                'achievement_type',
                'achievement_name',
                'achievement_level',
                'achievement_rank',
                'file_achievement',
                'grades_detail',
            ], fn($col) => Schema::hasColumn('ppdb_registrants', $col));

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
