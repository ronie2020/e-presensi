<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tambah kolom Data Pribadi, Keahlian, dan Hobi ke tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('agama')->nullable();
            $table->string('kewarganegaraan')->default('Indonesia');
            $table->string('status_pernikahan')->nullable();
            $table->text('keahlian')->nullable(); // Nanti diisi teks dipisah koma
            $table->text('hobi')->nullable();     // Nanti diisi teks dipisah koma
        });

        // 2. Buat tabel baru khusus untuk Riwayat Pendidikan (karena pendidikan bisa lebih dari 1)
        Schema::create('teacher_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('institution');
            $table->string('degree')->nullable();
            $table->string('start_year', 4)->nullable();
            $table->string('end_year', 4)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_educations');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'tempat_lahir', 'tanggal_lahir', 'alamat', 'jenis_kelamin',
                'agama', 'kewarganegaraan', 'status_pernikahan', 'keahlian', 'hobi'
            ]);
        });
    }
};