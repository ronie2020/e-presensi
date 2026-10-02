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
        Schema::dropIfExists('lms_discussions');

        Schema::create('lms_discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('lms_materials')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable(); // ID guru/admin dari tabel users
            $table->unsignedBigInteger('student_id')->nullable(); // ID siswa dari tabel students
            $table->string('author_name');
            $table->string('author_role')->default('Siswa'); // Siswa, Guru, Admin
            $table->foreignId('parent_id')->nullable()->constrained('lms_discussions')->onDelete('cascade');
            $table->text('comment');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_discussions');
    }
};
