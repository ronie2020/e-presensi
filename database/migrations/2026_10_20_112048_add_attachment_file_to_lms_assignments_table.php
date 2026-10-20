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
            if (!Schema::hasColumn('lms_assignments', 'file_path')) {
                $table->string('file_path')->nullable()->after('cover_image');
            }
            if (!Schema::hasColumn('lms_assignments', 'file_name')) {
                $table->string('file_name')->nullable()->after('file_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lms_assignments', function (Blueprint $table) {
            if (Schema::hasColumn('lms_assignments', 'file_name')) {
                $table->dropColumn('file_name');
            }
            if (Schema::hasColumn('lms_assignments', 'file_path')) {
                $table->dropColumn('file_path');
            }
        });
    }
};
