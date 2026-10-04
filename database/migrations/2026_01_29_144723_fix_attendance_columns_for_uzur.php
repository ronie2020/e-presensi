<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN status ENUM('Hadir', 'Sakit', 'Izin', 'Alfa', 'Terlambat', 'Uzur Syar\'i') NOT NULL");
            DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN time_in TIME NULL");
            DB::statement("ALTER TABLE attendances_siswa MODIFY COLUMN time_out TIME NULL");
        }
    }

    public function down(): void
    {
        // Tidak disarankan rollback ke NOT NULL jika sudah ada data NULL
    }
};