<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            
            // Tambahkan onDelete('set null') agar aman jika user dihapus
            $table->foreignId('homeroom_teacher_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null'); // <-- TAMBAHAN INI
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};