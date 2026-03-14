<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // Tabel Pengalaman & Pelatihan
        Schema::create('teacher_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('year', 10)->nullable();
            $table->string('title');
            $table->string('organizer')->nullable();
            $table->timestamps();
        });

        // Tabel Materi & Media
        Schema::create('teacher_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('type')->nullable(); // misal: PDF, Video, PPT
            $table->string('icon')->default('ph-file-text');
            $table->string('file_path')->nullable(); 
            $table->string('file_url')->nullable(); // Opsional jika link luar
            $table->timestamps();
        });

        // Tabel Portofolio & Pencapaian
        Schema::create('teacher_portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('year', 10)->nullable();
            $table->string('image_path'); // Wajib ada foto
            $table->timestamps();
        });

        // Tabel Artikel
        Schema::create('teacher_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('category')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('url')->nullable(); // Link ke artikel asli
            $table->string('image_path')->nullable();
            $table->date('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('teacher_articles');
        Schema::dropIfExists('teacher_portfolios');
        Schema::dropIfExists('teacher_materials');
        Schema::dropIfExists('teacher_experiences');
    }
};