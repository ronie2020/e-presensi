<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->boolean('fine_paid')->default(false)->after('fine_amount');
            $table->timestamp('fine_paid_at')->nullable()->after('fine_paid');
            $table->unsignedBigInteger('fine_paid_by')->nullable()->after('fine_paid_at'); // user id petugas
        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['fine_paid', 'fine_paid_at', 'fine_paid_by']);
        });
    }
};
