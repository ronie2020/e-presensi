<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan nama tabel sesuai dengan database Anda
        // Biasanya bernama 'cbt_exam_answers' atau 'cbt_answers'
        $tableName = 'cbt_exam_answers'; 

        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                
                // Cek jika nama kolomnya 'answer' (Standar)
                if (Schema::hasColumn($tableName, 'answer')) {
                    // Ubah jadi LONGTEXT agar muat novel sekalipun
                    $table->longText('answer')->nullable()->change();
                }

                // Cek jika nama kolomnya 'student_answer' (Alternatif)
                if (Schema::hasColumn($tableName, 'student_answer')) {
                    $table->longText('student_answer')->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = 'cbt_exam_answers';

        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'answer')) {
                    $table->text('answer')->nullable()->change();
                }
                if (Schema::hasColumn($tableName, 'student_answer')) {
                    $table->text('student_answer')->nullable()->change();
                }
            });
        }
    }
};