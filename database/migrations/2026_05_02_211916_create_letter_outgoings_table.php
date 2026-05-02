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
        Schema::create('letter_outgoings', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_agenda')->nullable();
            $table->string('nomor_surat');
            $table->string('tujuan_surat');
            $table->string('sifat_surat');
            $table->date('tgl_surat');
            $table->text('perihal');
            $table->string('file_path')->nullable(); // Untuk menyimpan lampiran/scan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_outgoings');
    }
};