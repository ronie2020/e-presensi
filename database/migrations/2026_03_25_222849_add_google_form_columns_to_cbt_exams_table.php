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
        Schema::table('cbt_exams', function (Blueprint $table) {
            // Menambahkan 2 kolom baru setelah kolom title
            $table->string('exam_type')->default('cbt')->after('title');
            $table->text('google_form_url')->nullable()->after('exam_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_exams', function (Blueprint $table) {
            // Menghapus kolom jika dilakukan rollback
            $table->dropColumn(['exam_type', 'google_form_url']);
        });
    }
};