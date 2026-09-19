<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sppds', function (Blueprint $table) {
            if (!Schema::hasColumn('sppds', 'status')) {
                $table->enum('status', ['draft', 'submitted', 'approved', 'selesai'])
                      ->default('draft')
                      ->after('nomor_sppd');
            }
            if (!Schema::hasColumn('sppds', 'catatan_kepala')) {
                $table->text('catatan_kepala')->nullable()->after('status');
            }
            if (!Schema::hasColumn('sppds', 'biaya_transport')) {
                $table->decimal('biaya_transport', 15, 2)->nullable()->after('keterangan_lain');
            }
            if (!Schema::hasColumn('sppds', 'biaya_penginapan')) {
                $table->decimal('biaya_penginapan', 15, 2)->nullable()->after('biaya_transport');
            }
            if (!Schema::hasColumn('sppds', 'uang_harian')) {
                $table->decimal('uang_harian', 15, 2)->nullable()->after('biaya_penginapan');
            }
        });

        // Set semua SPPD lama ke status 'selesai'
        DB::table('sppds')->update(['status' => 'selesai']);
    }

    public function down(): void
    {
        Schema::table('sppds', function (Blueprint $table) {
            $columns = ['status', 'catatan_kepala', 'biaya_transport', 'biaya_penginapan', 'uang_harian'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('sppds', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
