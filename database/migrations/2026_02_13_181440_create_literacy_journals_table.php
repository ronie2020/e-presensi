<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('literacy_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); // Sesuaikan nama tabel siswa
            $table->string('title');
            $table->string('author')->nullable();
            $table->integer('pages_read')->default(0);
            $table->text('summary')->nullable(); // Hikmah/Ringkasan
            $table->string('proof_image')->nullable(); // Path foto bukti
            $table->timestamp('verified_at')->nullable(); // Validasi Guru/Ortu
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('literacy_journals');
    }
};