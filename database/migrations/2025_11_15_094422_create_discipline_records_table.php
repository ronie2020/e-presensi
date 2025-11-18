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
        // Tabel ini adalah 'log' atau catatan dari setiap kejadian
        Schema::create('discipline_records', function (Blueprint $table) {
            $table->id();
            
            // Siswa mana yang melakukan
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            // Melakukan apa (pelanggaran/kebaikan)
            $table->foreignId('discipline_type_id')->constrained('discipline_types')->onDelete('restrict');
            
            // Dicatat oleh siapa (Guru/Admin)
            $table->foreignId('recorded_by_user_id')->constrained('users');
            
            $table->text('notes')->nullable(); // Detail Kejadian (opsional)
            $table->date('date'); // Tanggal kejadian
            
            $table->timestamps(); // (Waktu pencatatan)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discipline_records');
    }
};