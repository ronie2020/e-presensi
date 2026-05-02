<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('letter_incomings', function (Blueprint $table) {
            // 1. Menambahkan kolom yang belum ada di migration awal
            // tetapi ada di tampilan index & create
            $table->string('nomor_agenda')->after('id');
            $table->string('sifat_surat')->after('nomor_surat');

            // 2. Mengubah nama kolom agar sesuai dengan input 'name' di form
            // dan pemanggilan di halaman index
            $table->renameColumn('pengirim', 'asal_surat');
            $table->renameColumn('tgl_terima', 'tgl_diterima');
        });
    }

    public function down(): void
    {
        Schema::table('letter_incomings', function (Blueprint $table) {
            $table->dropColumn(['nomor_agenda', 'sifat_surat']);
            $table->renameColumn('asal_surat', 'pengirim');
            $table->renameColumn('tgl_diterima', 'tgl_terima');
        });
    }
};