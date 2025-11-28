<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('school_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul Kegiatan
            $table->text('description'); // Deskripsi Singkat
            $table->string('image_path')->nullable(); // Foto (Upload)
            $table->string('video_url')->nullable();  // Link Video (Opsional)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_activities');
    }
};