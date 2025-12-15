<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('graduations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('academic_year'); // Misal: 2023/2024
            $table->string('status')->default('LULUS'); // LULUS, TIDAK LULUS, DITUNDA
            $table->date('announcement_date'); // Tanggal pengumuman resmi
            $table->text('notes')->nullable(); // Catatan tambahan
            
            // Field untuk SKL (Surat Keterangan Lulus)
            $table->string('skl_number')->nullable(); // Nomor Surat
            $table->float('average_score')->nullable(); // Nilai Rata-rata
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('graduations');
    }
};