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
        Schema::table('teaching_sessions', function (Blueprint $table) {
            // Judul tugas yang diberikan guru ke siswa di pertemuan ini
            $table->string('homework_title')->nullable()->after('material_coverage');
            // Instruksi / deskripsi tugas
            $table->text('homework_description')->nullable()->after('homework_title');
            // Tipe tugas: offline (tatap muka), file_upload, atau link
            $table->enum('homework_type', ['offline', 'file_upload', 'link'])->nullable()->after('homework_description');
            // Link tugas (jika tipe = link)
            $table->string('homework_link_url')->nullable()->after('homework_type');
            // Batas waktu pengumpulan tugas
            $table->dateTime('homework_deadline')->nullable()->after('homework_link_url');
            // FK ke LmsAssignment (diisi setelah auto-create, agar bisa di-link balik)
            $table->unsignedBigInteger('lms_assignment_id')->nullable()->after('homework_deadline');
            $table->foreign('lms_assignment_id')->references('id')->on('lms_assignments')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('teaching_sessions', function (Blueprint $table) {
            $table->dropForeign(['lms_assignment_id']);
            $table->dropColumn([
                'homework_title',
                'homework_description',
                'homework_type',
                'homework_link_url',
                'homework_deadline',
                'lms_assignment_id',
            ]);
        });
    }
};
