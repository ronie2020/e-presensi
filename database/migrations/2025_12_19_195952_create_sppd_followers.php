<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus tabel lama jika ada (untuk membersihkan sisa error sebelumnya)
        Schema::dropIfExists('sppd_followers');

        // Buat Tabel Baru Langsung dengan Kolom NIP
        Schema::create('sppd_followers', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke SPPD (Jika SPPD dihapus, pengikut ikut terhapus)
            $table->foreignId('sppd_id')->constrained('sppds')->onDelete('cascade');
            
            // Relasi ke User (Opsional, jika user dihapus, set null)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->string('nama');
            $table->string('nip')->nullable(); // Langsung kita buat kolom NIP disini
            $table->string('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sppd_followers');
    }
};