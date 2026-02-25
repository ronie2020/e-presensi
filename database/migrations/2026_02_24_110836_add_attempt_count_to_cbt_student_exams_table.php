<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::table('cbt_student_exams', function (Blueprint $table) {
            $table->integer('attempt_count')->default(1)->after('status');
        });
    }

        public function down()
    {
        Schema::table('cbt_student_exams', function (Blueprint $table) {
            $table->dropColumn('attempt_count');
        });
    }
};
