<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Ubah tipe kolom 'type' dari ENUM menjadi VARCHAR(50) agar fleksibel
        DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN type VARCHAR(50) NOT NULL DEFAULT 'Harian'");
        
        // Opsional: Jika kolom 'activity' belum ada (cek tabel Anda), kita tambahkan sekalian.
        // Karena di controller ada kode 'activity' => $activity
        if (!Schema::hasColumn('attendances_siswa', 'activity')) {
            Schema::table('attendances_siswa', function (Blueprint $table) {
                $table->string('activity')->nullable()->after('type');
            });
        }
    }

    public function down()
    {
        // Kembalikan ke kondisi awal jika rollback (Opsional)
        // DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN type ENUM('Harian', 'Dhuha', 'Dhuhur') NOT NULL DEFAULT 'Harian'");
    }
};