<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cbt_question_banks', function (Blueprint $table) {
            $table->foreignId('cbt_bank_folder_id')->nullable()->after('id')->constrained('cbt_bank_folders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_question_banks', function (Blueprint $table) {
            //
        });
    }
};
