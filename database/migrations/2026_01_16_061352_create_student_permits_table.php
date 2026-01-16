<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel ini khusus untuk mencatat 'transaksi' izin keluar-masuk jam pelajaran.
     */
    public function up(): void
    {
        Schema::create('student_permits', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel students
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            // Siapa guru piket yang menjaga saat itu (opsional, jika pakai auth)
            $table->foreignId('pic_teacher_id')->nullable()->constrained('users'); 

            // Jenis Izin & Alasan
            $table->enum('reason_category', ['Toilet', 'UKS', 'Dispensasi', 'Barang Tertinggal', 'Panggilan Guru', 'Lainnya']);
            $table->string('notes')->nullable(); // Catatan tambahan (misal: "Sakit perut")

            // Waktu Keluar & Masuk
            $table->dateTime('time_out');
            $table->dateTime('time_in')->nullable();
            
            // Durasi realisasi (dalam menit), diisi saat siswa kembali
            $table->integer('duration_minutes')->default(0);

            // Status Izin
            $table->enum('status', ['OUT', 'RETURNED'])->default('OUT');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_permits');
    }
};