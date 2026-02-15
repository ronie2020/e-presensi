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
        Schema::table('cbt_questions', function (Blueprint $table) {
            // Menambahkan kolom question_type setelah cbt_exam_id
            // Default 'choice' agar data lama tidak error
            $table->string('question_type')->default('choice')->after('cbt_exam_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_questions', function (Blueprint $table) {
            $table->dropColumn('question_type');
        });
    }
};