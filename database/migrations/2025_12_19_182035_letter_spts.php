<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Utama SPT
        Schema::create('letter_spts', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Surat Masuk (Dasar Surat)
            $table->foreignId('letter_incoming_id')->nullable()->constrained('letter_incomings')->onDelete('set null');
            
            $table->string('nomor_spt');
            $table->text('untuk');           // Maksud Penugasan
            $table->string('tempat_tujuan'); // Tempat
            
            $table->date('tgl_berangkat');
            $table->date('tgl_kembali');
            $table->integer('lama_hari')->default(1);
            
            // Penanda tangan (Kepala Sekolah)
            $table->string('pejabat_nama')->default('TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.');
            $table->string('pejabat_nip')->default('19820928 201101 1 002');
            
            $table->timestamps();
        });

        // 2. Tabel Pivot (Many-to-Many) untuk Pegawai yang ditugaskan
        Schema::create('letter_spt_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_spt_id')->constrained('letter_spts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_spt_user');
        Schema::dropIfExists('letter_spts');
    }
};