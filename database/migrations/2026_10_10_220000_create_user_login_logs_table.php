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
        Schema::create('user_login_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('user_type')->default('user')->index(); // 'user' (Guru/Staff/Admin) or 'student'
            $table->string('name')->nullable();
            $table->string('identifier')->nullable()->index(); // Email, NIP, or NISN
            $table->string('role')->nullable()->index(); // Admin, Guru, Wali Kelas, Siswa, dll.
            $table->string('guard')->default('web'); // web, student
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device', 30)->nullable(); // Desktop, Mobile, Tablet
            $table->string('browser', 50)->nullable(); // Chrome, Safari, Firefox, dll.
            $table->string('platform', 50)->nullable(); // Windows, Android, iOS, dll.
            $table->string('status', 20)->default('success')->index(); // success, failed
            $table->timestamp('login_at')->useCurrent()->index();
            $table->timestamp('logout_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_login_logs');
    }
};
