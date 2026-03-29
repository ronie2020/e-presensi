<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cbt_questions', function (Blueprint $table) {
            // Menambahkan kolom tags (nullable) setelah score_weight
            $table->string('tags')->nullable()->after('score_weight');
        });
    }

    public function down()
    {
        Schema::table('cbt_questions', function (Blueprint $table) {
            $table->dropColumn('tags');
        });
    }
};