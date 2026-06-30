<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timeslots', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Jam ke-1", "Istirahat 1"
            $table->time('start_time'); // Contoh: 07:15:00
            $table->time('end_time'); // Contoh: 08:00:00
            $table->boolean('is_break')->default(false); // Penanda waktu istirahat
            $table->integer('order_sequence'); // Urutan jam (1, 2, 3, dst.)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timeslots');
    }
};