<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('letter_incomings')) {
            Schema::create('letter_incomings', function (Blueprint $table) {
                $table->id();
                $table->string('nomor_surat');
                $table->string('pengirim'); // Asal Surat
                $table->text('perihal');    // Isi Ringkas
                $table->date('tgl_surat');
                $table->date('tgl_terima');
                
                // Menyimpan lokasi file PDF/Gambar jika diupload
                $table->string('file_path')->nullable();
                
                // Status disposisi (Belum / Sudah)
                $table->string('status_disposisi')->default('Belum');
                
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_incomings');
    }
};