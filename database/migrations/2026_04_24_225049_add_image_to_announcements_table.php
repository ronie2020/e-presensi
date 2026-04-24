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
        Schema::table('announcements', function (Blueprint $table) {
            // Menambahkan kolom image dengan tipe string dan boleh kosong (nullable)
            $table->string('image')->nullable()->after('content');
        });
    }

    public function down()
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Menghapus kolom image jika proses migration di-rollback
            $table->dropColumn('image');
        });
    }
};
