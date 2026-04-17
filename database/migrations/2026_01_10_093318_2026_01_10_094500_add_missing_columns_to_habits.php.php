<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('student_habits', function (Blueprint $table) {
            
            // 1. Cek & Tambah Kolom Waktu Makan (mbg_taken_at)
            if (!Schema::hasColumn('student_habits', 'mbg_taken_at')) {
                $table->timestamp('mbg_taken_at')->nullable()->after('habit_5_menu');
            }

            // 2. Cek & Tambah Kolom-kolom Shalat (Jika belum ada)
            if (!Schema::hasColumn('student_habits', 'prayer_subuh')) {
                $table->boolean('prayer_subuh')->default(false)->after('habit_2');
                $table->boolean('prayer_dhuha')->default(false)->after('prayer_subuh');
                $table->boolean('prayer_dzuhur')->default(false)->after('prayer_dhuha');
                $table->boolean('prayer_ashar')->default(false)->after('prayer_dzuhur');
                $table->boolean('prayer_maghrib')->default(false)->after('prayer_ashar');
                $table->boolean('prayer_isya')->default(false)->after('prayer_maghrib');
            }
        });
    }

    public function down()
    {
        Schema::table('student_habits', function (Blueprint $table) {
            // Hapus kolom jika rollback
            if (Schema::hasColumn('student_habits', 'mbg_taken_at')) {
                $table->dropColumn('mbg_taken_at');
            }
            if (Schema::hasColumn('student_habits', 'prayer_subuh')) {
                $table->dropColumn([
                    'prayer_subuh', 'prayer_dhuha', 'prayer_dzuhur', 
                    'prayer_ashar', 'prayer_maghrib', 'prayer_isya'
                ]);
            }
        });
    }
};