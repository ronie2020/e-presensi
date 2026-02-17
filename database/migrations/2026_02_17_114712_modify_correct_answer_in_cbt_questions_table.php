<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::table('cbt_questions', function (Blueprint $table) {
                // Ubah kolom correct_answer menjadi LONGTEXT agar bisa menampung jawaban sangat panjang
                $table->longText('correct_answer')->change();
            });
        }

        public function down()
        {
            Schema::table('cbt_questions', function (Blueprint $table) {
                // Kembalikan ke string biasa jika di-rollback (opsional)
                $table->string('correct_answer', 255)->change();
            });
        }
    };