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
        Schema::table('cbt_questions', function (Blueprint $table) {
            if (!Schema::hasColumn('cbt_questions', 'difficulty')) {
                $table->string('difficulty')->default('sedang')->after('tags');
            }
            if (!Schema::hasColumn('cbt_questions', 'bloom_taxonomy')) {
                $table->string('bloom_taxonomy')->default('C2')->after('difficulty');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_questions', function (Blueprint $table) {
            $table->dropColumn(['difficulty', 'bloom_taxonomy']);
        });
    }
};
