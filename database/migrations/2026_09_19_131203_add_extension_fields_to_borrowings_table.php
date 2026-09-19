<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->boolean('is_extended')->default(false)->after('type');
            $table->tinyInteger('extension_count')->default(0)->after('is_extended');
            $table->timestamp('extended_at')->nullable()->after('extension_count');
        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['is_extended', 'extension_count', 'extended_at']);
        });
    }
};
