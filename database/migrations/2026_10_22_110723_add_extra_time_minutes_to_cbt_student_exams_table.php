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
        if (Schema::hasTable('cbt_student_exams')) {
            Schema::table('cbt_student_exams', function (Blueprint $table) {
                if (!Schema::hasColumn('cbt_student_exams', 'extra_time_minutes')) {
                    $table->integer('extra_time_minutes')->default(0)->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('cbt_student_exams')) {
            Schema::table('cbt_student_exams', function (Blueprint $table) {
                if (Schema::hasColumn('cbt_student_exams', 'extra_time_minutes')) {
                    $table->dropColumn('extra_time_minutes');
                }
            });
        }
    }
};
