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
        Schema::table('lms_submissions', function (Blueprint $table) {
            // Kolom untuk menyimpan Link (Google Drive/Youtube/dll)
            // Ditaruh setelah file_path agar rapi
            $table->text('link_url')->nullable()->after('file_path');

            // Kolom untuk membedakan apakah siswa upload file atau kirim link
            // Default 'file' agar data lama tetap dianggap file
            $table->string('submission_type')->default('file')->after('grade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lms_submissions', function (Blueprint $table) {
            $table->dropColumn(['link_url', 'submission_type']);
        });
    }
};