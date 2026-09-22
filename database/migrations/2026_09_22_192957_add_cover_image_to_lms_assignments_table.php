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
        Schema::table('lms_assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('lms_assignments', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lms_assignments', function (Blueprint $table) {
            if (Schema::hasColumn('lms_assignments', 'cover_image')) {
                $table->dropColumn('cover_image');
            }
        });
    }
};
