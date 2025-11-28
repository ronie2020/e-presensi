<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discipline_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['Pelanggaran', 'Kebaikan']); 
            $table->integer('point_value'); 
            
            // --- TAMBAHKAN INI ---
            $table->text('description')->nullable(); 
            // ---------------------

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discipline_types');
    }
};