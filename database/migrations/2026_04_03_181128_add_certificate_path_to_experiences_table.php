<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up() {
    Schema::table('teacher_experiences', function (Blueprint $table) {
        $table->string('certificate_path')->nullable()->after('organizer');
    });
}
public function down() {
    Schema::table('teacher_experiences', function (Blueprint $table) {
        $table->dropColumn('certificate_path');
    });
}

};
