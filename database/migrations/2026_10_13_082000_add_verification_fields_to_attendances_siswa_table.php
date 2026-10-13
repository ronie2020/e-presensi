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
        Schema::table('attendances_siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances_siswa', 'verify_method')) {
                $table->string('verify_method', 20)->default('qr')->after('status');
            }
            if (!Schema::hasColumn('attendances_siswa', 'confidence_score')) {
                $table->decimal('confidence_score', 5, 2)->nullable()->after('verify_method');
            }
            if (!Schema::hasColumn('attendances_siswa', 'scanned_by')) {
                $table->foreignId('scanned_by')->nullable()->after('confidence_score')->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances_siswa', function (Blueprint $table) {
            if (Schema::hasColumn('attendances_siswa', 'scanned_by')) {
                $table->dropForeign(['scanned_by']);
                $table->dropColumn('scanned_by');
            }
            if (Schema::hasColumn('attendances_siswa', 'confidence_score')) {
                $table->dropColumn('confidence_score');
            }
            if (Schema::hasColumn('attendances_siswa', 'verify_method')) {
                $table->dropColumn('verify_method');
            }
        });
    }
};
