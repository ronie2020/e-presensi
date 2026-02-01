<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lms_submissions', function (Blueprint $table) {
            // Tambahkan kolom 'status' jika belum ada
            if (!Schema::hasColumn('lms_submissions', 'status')) {
                $table->string('status')->default('submitted')->after('grade');
            }
            
            // Tambahkan kolom 'teacher_feedback' jika belum ada (jaga-jaga)
            if (!Schema::hasColumn('lms_submissions', 'teacher_feedback')) {
                $table->text('teacher_feedback')->nullable()->after('grade');
            }
        });
    }

    public function down()
    {
        Schema::table('lms_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('lms_submissions', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('lms_submissions', 'teacher_feedback')) {
                $table->dropColumn('teacher_feedback');
            }
        });
    }
};