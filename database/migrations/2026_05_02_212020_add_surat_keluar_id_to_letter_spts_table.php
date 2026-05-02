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
        Schema::table('letter_spts', function (Blueprint $table) {
            // Menambahkan kolom surat_keluar_id setelah kolom letter_incoming_id
            // nullable() karena SPT bisa saja dibuat dari Surat Masuk atau Surat Keluar
            $table->unsignedBigInteger('surat_keluar_id')->nullable()->after('letter_incoming_id');
            
            // Opsional: Membuat Foreign Key Constraint untuk integritas data
            $table->foreign('surat_keluar_id')
                  ->references('id')->on('letter_outgoings')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_spts', function (Blueprint $table) {
            // Drop foreign key dan kolom jika di-rollback
            $table->dropForeign(['surat_keluar_id']);
            $table->dropColumn('surat_keluar_id');
        });
    }
};