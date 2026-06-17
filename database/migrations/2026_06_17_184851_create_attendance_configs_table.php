<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('attendance_configs', function (Blueprint $table) {
        $table->id();
        $table->time('dhuha_start')->default('07:30:00');
        $table->time('dhuha_end')->default('08:00:00');
        $table->time('makan_start')->default('09:00:00');
        $table->time('makan_end')->default('10:00:00');
        $table->time('dhuhur_start')->default('11:45:00');
        $table->time('dhuhur_end')->default('13:30:00');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('attendance_configs');
    }
};