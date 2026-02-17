<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Menambahkan kolom 'score' ke tabel jawaban siswa
        // Ganti 'cbt_student_answers' sesuai nama tabel Anda jika berbeda
        if (Schema::hasTable('cbt_student_answers')) {
            Schema::table('cbt_student_answers', function (Blueprint $table) {
                if (!Schema::hasColumn('cbt_student_answers', 'score')) {
                    $table->decimal('score', 8, 2)->nullable()->default(0)->after('answer');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('cbt_student_answers')) {
            Schema::table('cbt_student_answers', function (Blueprint $table) {
                if (Schema::hasColumn('cbt_student_answers', 'score')) {
                    $table->dropColumn('score');
                }
            });
        }
    }
};