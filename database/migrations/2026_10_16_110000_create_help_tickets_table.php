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
        Schema::create('help_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code', 30)->unique();
            $table->enum('reporter_type', ['siswa', 'guru', 'umum'])->default('siswa');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->string('name', 150);
            $table->string('identity_number', 50)->nullable(); // NISN atau NIP
            $table->string('class_or_role', 100)->nullable(); // Kelas 7A, atau Guru Mapel
            $table->string('phone', 30)->nullable(); // No WhatsApp
            $table->string('category', 100); // Akun & Login, CBT & Token, dll
            $table->string('page_url')->nullable(); // Halaman yang mengalami error
            $table->text('description'); // Rincian kendala
            $table->string('screenshot_path')->nullable(); // Bukti tangkapan layar
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_tickets');
    }
};
