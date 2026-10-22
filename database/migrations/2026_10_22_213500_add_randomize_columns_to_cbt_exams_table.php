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
        Schema::table('cbt_exams', function (Blueprint $table) {
            if (!Schema::hasColumn('cbt_exams', 'randomize_questions')) {
                $table->boolean('randomize_questions')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('cbt_exams', 'randomize_options')) {
                $table->boolean('randomize_options')->default(false)->after('randomize_questions');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_exams', function (Blueprint $table) {
            $table->dropColumn(['randomize_questions', 'randomize_options']);
        });
    }
};
