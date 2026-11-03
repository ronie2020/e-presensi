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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'face_descriptor')) {
                $table->longText('face_descriptor')->nullable()->after('photo_path');
            }
            if (!Schema::hasColumn('students', 'face_photo_path')) {
                $table->string('face_photo_path')->nullable()->after('face_descriptor');
            }
            if (!Schema::hasColumn('students', 'face_status')) {
                $table->string('face_status', 30)->default('unregistered')->after('face_photo_path');
            }
            if (!Schema::hasColumn('students', 'face_registered_at')) {
                $table->timestamp('face_registered_at')->nullable()->after('face_status');
            }
            if (!Schema::hasColumn('students', 'face_verified_at')) {
                $table->timestamp('face_verified_at')->nullable()->after('face_registered_at');
            }
            if (!Schema::hasColumn('students', 'face_verified_by')) {
                $table->unsignedBigInteger('face_verified_by')->nullable()->after('face_verified_at');
            }
            if (!Schema::hasColumn('students', 'face_rejection_note')) {
                $table->string('face_rejection_note')->nullable()->after('face_verified_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $cols = [
                'face_descriptor',
                'face_photo_path',
                'face_status',
                'face_registered_at',
                'face_verified_at',
                'face_verified_by',
                'face_rejection_note',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('students', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

