<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Contoh: Upacara Hari Pahlawan
            $table->string('location')->nullable(); // Contoh: Lapangan Utama
            $table->date('event_date'); // Tanggal kegiatan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agendas');
    }
};