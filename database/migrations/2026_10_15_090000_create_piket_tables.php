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
        // 1. Master Jadwal Piket Mingguan
        Schema::create('piket_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('day_name', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->string('post_location')->nullable()->default('Lobi Utama'); // Pos: Lobi Utama, Gerbang 5S, Ruang KBM, dll.
            $table->boolean('is_coordinator')->default(false);
            $table->integer('order_number')->default(1);
            $table->timestamps();

            $table->index(['day_name', 'academic_year_id']);
        });

        // 2. Buku Jurnal Piket Harian
        Schema::create('piket_journals', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->foreignId('coordinator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('weather')->nullable()->default('Cerah');
            $table->text('general_notes')->nullable(); // Catatan umum / kejadian / insiden harian
            $table->enum('status', ['draft', 'submitted', 'verified'])->default('draft');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // 3. Catatan Guru Tidak Hadir & Penugasan Inval (Pengganti)
        Schema::create('piket_invals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('piket_journal_id')->constrained('piket_journals')->cascadeOnDelete();
            $table->foreignId('absent_teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('reason')->default('Izin'); // Sakit, Izin, Dinas Luar / SPPD, Tanpa Keterangan
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->string('timeslot_info')->nullable(); // Jam ke-1 s.d 2 (07.15 - 08.35)
            $table->foreignId('inval_teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('assignment_notes')->nullable(); // Tugas yang ditinggalkan guru / instruksi inval
            $table->string('status')->default('Menunggu Pengganti'); // Menunggu Pengganti, Terisi, Mandiri Berikan Tugas
            $table->timestamps();
        });

        // 4. Catatan Buku Tamu Meja Piket
        Schema::create('piket_guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('piket_journal_id')->constrained('piket_journals')->cascadeOnDelete();
            $table->string('guest_name');
            $table->string('institution')->nullable(); // Asal instansi / organisasi
            $table->string('phone')->nullable();
            $table->string('meet_with')->nullable(); // Bertemu dengan (Kepala Sekolah, Guru X, Siswa Y)
            $table->text('purpose')->nullable(); // Keperluan / agenda
            $table->time('time_in');
            $table->time('time_out')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_vip')->default(false); // Tamu VIP untuk ditampilkan di Digital Signage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piket_guests');
        Schema::dropIfExists('piket_invals');
        Schema::dropIfExists('piket_journals');
        Schema::dropIfExists('piket_schedules');
    }
};
