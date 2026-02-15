<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Buat Tabel Bank Soal
        Schema::create('cbt_question_banks', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable(); // Kode Bank (misal: MTK-7-01)
            $table->string('title'); // Judul (misal: Bank Soal Aljabar)
            $table->string('subject_name');
            $table->string('class_level'); // 7, 8, 9
            $table->unsignedBigInteger('author_id')->nullable(); // ID Guru pembuat
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Modifikasi Tabel Soal (cbt_questions)
        // Agar soal bisa nempel ke Bank Soal (jika exam_id null)
        Schema::table('cbt_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('cbt_question_bank_id')->nullable()->after('cbt_exam_id');
            $table->unsignedBigInteger('cbt_exam_id')->nullable()->change(); // Exam ID jadi nullable
            
            // Tambahkan Foreign Key (Opsional, untuk integritas data)
            // $table->foreign('cbt_question_bank_id')->references('id')->on('cbt_question_banks')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('cbt_questions', function (Blueprint $table) {
            $table->dropColumn('cbt_question_bank_id');
            // Kembalikan cbt_exam_id jadi tidak nullable jika perlu (hati-hati data hilang)
        });
        Schema::dropIfExists('cbt_question_banks');
    }
};