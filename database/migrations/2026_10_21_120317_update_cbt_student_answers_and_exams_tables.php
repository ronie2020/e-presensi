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
        if (Schema::hasTable('cbt_student_answers')) {
            Schema::table('cbt_student_answers', function (Blueprint $table) {
                if (Schema::hasColumn('cbt_student_answers', 'answer')) {
                    $table->longText('answer')->nullable()->change();
                }
            });
        }

        if (Schema::hasTable('cbt_student_exams')) {
            Schema::table('cbt_student_exams', function (Blueprint $table) {
                if (!Schema::hasColumn('cbt_student_exams', 'violation_count')) {
                    $table->integer('violation_count')->default(0)->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('cbt_student_answers')) {
            Schema::table('cbt_student_answers', function (Blueprint $table) {
                if (Schema::hasColumn('cbt_student_answers', 'answer')) {
                    $table->string('answer', 255)->nullable()->change();
                }
            });
        }

        if (Schema::hasTable('cbt_student_exams')) {
            Schema::table('cbt_student_exams', function (Blueprint $table) {
                if (Schema::hasColumn('cbt_student_exams', 'violation_count')) {
                    $table->dropColumn('violation_count');
                }
            });
        }
    }
};

