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
        Schema::create('system_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('user_type')->nullable()->index(); // 'user' or 'student'
            $table->string('user_name')->nullable();
            $table->string('role')->nullable()->index();
            $table->string('module', 50)->index(); // auth, kbm, presensi, lms, cbt, disiplin, perpus, surat, students, grades, settings
            $table->string('action', 50)->index(); // login, logout, create, update, delete, import, export, verify, reset
            $table->text('description');
            $table->string('subject_type')->nullable(); // Model class e.g. App\Models\TeachingSession
            $table->unsignedBigInteger('subject_id')->nullable()->index();
            $table->json('old_values')->nullable(); // Nilai sebelum diubah
            $table->json('new_values')->nullable(); // Nilai sesudah diubah
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method', 10)->nullable(); // GET, POST, PUT, DELETE, dll.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_audit_logs');
    }
};
