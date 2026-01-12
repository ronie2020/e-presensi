<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_habits', function (Blueprint $table) {
            // Menambahkan kolom boolean (TinyInt) untuk status udzur
            // Default 0 (False/Tidak Udzur)
            // Diletakkan setelah report_date agar rapi
            $table->boolean('is_udzur_syar_i')->default(false)->after('report_date');
        });
    }

    public function down(): void
    {
        Schema::table('student_habits', function (Blueprint $table) {
            $table->dropColumn('is_udzur_syar_i');
        });
    }
};