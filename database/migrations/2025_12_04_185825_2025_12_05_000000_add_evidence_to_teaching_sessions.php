<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teaching_sessions', function (Blueprint $table) {
            $table->string('photo_proof')->nullable()->after('reference_link'); // Path Foto
            $table->string('video_link')->nullable()->after('photo_proof');     // Link Video (Youtube/Drive)
        });
    }

    public function down()
    {
        Schema::table('teaching_sessions', function (Blueprint $table) {
            $table->dropColumn(['photo_proof', 'video_link']);
        });
    }
};