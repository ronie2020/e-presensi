<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. TAMBAHKAN KOLOM PANGKAT KE TABEL USERS
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'pangkat')) {
                $table->string('pangkat')->nullable()->after('position'); 
            }
        });

        // 2. BUAT TABEL SPPDS
        if (!Schema::hasTable('sppds')) {
            Schema::create('sppds', function (Blueprint $table) {
                $table->id();
                $table->string('nomor_sppd');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
                
                $table->string('maksud_perjalanan');
                $table->string('alat_angkut')->nullable();
                $table->string('tempat_berangkat')->default('SMP Negeri 3 Lakbok');
                $table->string('tempat_tujuan');
                $table->integer('lama_hari')->default(1);
                $table->date('tgl_berangkat');
                $table->date('tgl_kembali');
                
                $table->string('instansi_pembayar')->default('SMP Negeri 3 Lakbok');
                $table->string('mata_anggaran')->nullable();
                $table->text('keterangan_lain')->nullable();

                // Data Pejabat Pemberi Perintah (Snapshot)
                $table->string('pejabat_nama')->default('TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.');
                $table->string('pejabat_nip')->default('19820928 201101 1 002');
                $table->string('pejabat_pangkat')->default('Penata, III/c');
                $table->string('pejabat_jabatan')->default('Kepala Sekolah');

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sppds');
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'pangkat')) {
                $table->dropColumn('pangkat');
            }
        });
    }
};