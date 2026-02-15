<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cbt_exam_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cbt_student_exam_id'); // Relasi ke Sesi Ujian
            $table->string('photo_path'); // Lokasi file foto
            $table->timestamp('captured_at'); // Waktu ambil foto
            $table->timestamps();

            // Foreign Key
            $table->foreign('cbt_student_exam_id')
                  ->references('id')->on('cbt_student_exams')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cbt_exam_photos');
    }
};