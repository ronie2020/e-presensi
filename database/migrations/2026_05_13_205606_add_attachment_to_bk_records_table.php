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
        Schema::table('bk_records', function (Blueprint $table) {
            // Menambahkan kolom untuk menyimpan nama/path file
            $table->string('attachment_path')->nullable()->after('result');
        });
    }
    public function down()
    {
        Schema::table('bk_records', function (Blueprint $table) {
            $table->dropColumn('attachment_path');
        });
    }
};
