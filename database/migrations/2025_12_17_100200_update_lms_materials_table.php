<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tambah kolom Resume di tabel utama
        Schema::table('lms_materials', function (Blueprint $table) {
            $table->longText('resume')->nullable()->after('description'); // Penjelasan lengkap/Rangkuman
        });

        // 2. Buat tabel baru untuk MULTIPLE FILE
        Schema::create('lms_material_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('lms_materials')->onDelete('cascade');
            $table->string('file_name')->nullable(); // Nama file asli
            $table->string('file_path'); // Path penyimpanan atau URL Link
            $table->string('file_type')->default('file'); // 'file', 'video', 'link'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lms_material_attachments');
        Schema::table('lms_materials', function (Blueprint $table) {
            $table->dropColumn('resume');
        });
    }
};