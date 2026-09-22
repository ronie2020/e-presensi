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
        // Tambah kolom cover_image ke lms_materials
        Schema::table('lms_materials', function (Blueprint $table) {
            if (!Schema::hasColumn('lms_materials', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('type');
            }
            if (!Schema::hasColumn('lms_materials', 'is_bulk')) {
                $table->boolean('is_bulk')->default(false)->after('cover_image');
            }
            if (!Schema::hasColumn('lms_materials', 'target_grade')) {
                $table->string('target_grade')->nullable()->after('is_bulk');
            }
            if (!Schema::hasColumn('lms_materials', 'total_classes')) {
                $table->unsignedInteger('total_classes')->default(1)->after('target_grade');
            }
        });

        // Tambah kolom is_bulk ke lms_assignments jika belum ada
        Schema::table('lms_assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('lms_assignments', 'is_bulk')) {
                $table->boolean('is_bulk')->default(false)->after('allow_late_submission');
            }
            if (!Schema::hasColumn('lms_assignments', 'target_grade')) {
                $table->string('target_grade')->nullable()->after('is_bulk');
            }
            if (!Schema::hasColumn('lms_assignments', 'total_classes')) {
                $table->unsignedInteger('total_classes')->default(1)->after('target_grade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lms_materials', function (Blueprint $table) {
            $table->dropColumn(['cover_image', 'is_bulk', 'target_grade', 'total_classes']);
        });

        Schema::table('lms_assignments', function (Blueprint $table) {
            $table->dropColumn(['is_bulk', 'target_grade', 'total_classes']);
        });
    }
};
