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
        Schema::table('learning_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('learning_schedules', 'repeat_count')) {
                // Jumlah pengulangan bel (1 = sekali, 2 = dua kali, dst). Default 1x.
                $table->integer('repeat_count')->default(1)->after('audio_file');
            }
        });
    }

    public function down(): void
    {
        Schema::table('learning_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('learning_schedules', 'repeat_count')) {
                $table->dropColumn('repeat_count');
            }
        });
    }
};
