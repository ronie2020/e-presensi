<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // F. DETAIL WALI (Tambahan)
            $table->string('guardian_pob')->nullable()->after('guardian_name'); // Tempat Lahir Wali
            $table->date('guardian_dob')->nullable()->after('guardian_pob'); // Tanggal Lahir Wali
            $table->string('guardian_citizenship')->nullable()->after('guardian_dob'); // Kewarganegaraan Wali

            // G. PRESTASI & BEASISWA
            $table->text('scholarship_info')->nullable()->after('achievements'); // Penerimaan Beasiswa (Tahun, Kelas, Dari)

            // I. MENINGGALKAN SEKOLAH (TAMAT BELAJAR)
            $table->date('graduated_date')->nullable(); // Tanggal Tamat
            $table->string('graduated_diploma_no')->nullable(); // No Ijazah Lulus
            $table->string('continuing_to_school')->nullable(); // Melanjutkan ke sekolah...
            $table->text('continuing_school_address')->nullable(); // Alamat sekolah lanjut

            // I. MENINGGALKAN SEKOLAH (PINDAH)
            $table->date('leaving_date')->nullable(); // Tanggal Pindah
            $table->string('leaving_reason')->nullable(); // Alasan Pindah
            $table->string('leaving_to_school')->nullable(); // Pindah ke sekolah...
            $table->string('leaving_class')->nullable(); // Dari Kelas...

            // I. MENINGGALKAN SEKOLAH (PUTUS)
            $table->date('dropout_date')->nullable(); // Tanggal Putus
            $table->string('dropout_reason')->nullable(); // Alasan

            // J. LAIN-LAIN
            $table->text('general_notes')->nullable(); // Catatan penting selama belajar
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'guardian_pob', 'guardian_dob', 'guardian_citizenship',
                'scholarship_info',
                'graduated_date', 'graduated_diploma_no', 'continuing_to_school', 'continuing_school_address',
                'leaving_date', 'leaving_reason', 'leaving_to_school', 'leaving_class',
                'dropout_date', 'dropout_reason',
                'general_notes'
            ]);
        });
    }
};