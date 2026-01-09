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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Siswa
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            // Data Log
            $table->string('type')->index(); // Contoh: 'Makan'
            $table->string('description')->nullable(); // Contoh: 'Makan Bergizi Gratis'
            $table->timestamp('scanned_at')->useCurrent(); // Waktu scan
            $table->text('notes')->nullable(); // Catatan tambahan
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};