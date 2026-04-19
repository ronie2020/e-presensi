<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->boolean('is_textbook')->default(false)->after('title');
        });

        Schema::table('borrowings', function (Blueprint $table) {
            $table->enum('type', ['regular', 'textbook'])->default('regular')->after('status');
            $table->string('item_code')->nullable()->after('type'); // Menyimpan barcode spesifik per fisik buku
        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['type', 'item_code']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('is_textbook');
        });
    }
};