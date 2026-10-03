<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->string('grade_level', 10)->nullable()->after('subject_id')->comment('Jenjang/Tingkat: 7, 8, 9');
            $table->foreignId('class_id')->nullable()->after('grade_level')->constrained('classes')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn(['grade_level', 'class_id']);
        });
    }
};
