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
        Schema::create('attendances_siswa', function (Blueprint $table) {
            $table->id();
            
            // Menghubungkan ke siswa yang absen
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            $table->date('attendance_date'); // Tanggal absen
            
            // PERBAIKAN 1: Menambahkan 'Masuk' dan 'Pulang' ke tipe absen
            // Agar support data dari QR Code yang mengirim 'Masuk'
            $table->enum('type', ['Harian', 'Dhuha', 'Dhuhur', 'Masuk', 'Pulang'])->default('Harian'); 
            
            // PERBAIKAN 2: Menambahkan 'Terlambat' ke status
            // Ini solusi untuk error "Data truncated"
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alfa', 'Terlambat']); 
            
            $table->time('time_in'); // Jam berapa siswa tercatat absen
            $table->text('notes')->nullable(); // Catatan (misal: "Terlambat 15 menit")
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances_siswa');
    }
};