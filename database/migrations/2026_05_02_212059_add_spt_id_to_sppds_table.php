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
        Schema::table('sppds', function (Blueprint $table) {
            // Menambahkan kolom spt_id untuk mengetahui dasar penugasan SPPD
            $table->unsignedBigInteger('spt_id')->nullable()->after('id');
            
            // Opsional: Membuat Foreign Key Constraint
            $table->foreign('spt_id')
                  ->references('id')->on('letter_spts')
                  ->onDelete('cascade'); // Jika SPT dihapus, SPPD terkait ikut terhapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sppds', function (Blueprint $table) {
            // Drop foreign key dan kolom jika di-rollback
            $table->dropForeign(['spt_id']);
            $table->dropColumn('spt_id');
        });
    }
};