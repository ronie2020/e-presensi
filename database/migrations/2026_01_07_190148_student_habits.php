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
        Schema::create('student_habits', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Siswa
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            // Tanggal Laporan (Unique: 1 siswa 1 hari 1 laporan)
            $table->date('report_date');
            
            // --- 7 KEBIASAAN (DISESUAIKAN DENGAN URUTAN BARU) ---
            
            // 1. Bangun Pagi, Mandi & Rapi
            // (Menggabungkan Habit 1 & 2 secara konsep, tapi kolom tetap dipisah agar fleksibel)
            $table->boolean('habit_1')->default(false); // Status Bangun
            $table->string('habit_1_time', 10)->nullable(); // Jam Bangun (04:30)
            $table->string('habit_1_note')->nullable(); // Catatan tambahan
            
            $table->boolean('habit_2')->default(false); // Status Mandi & Rapi

            // 2. Shalat Tepat Waktu (Poin Baru - Pengganti konsep Habit 2 lama di UI)
            $table->boolean('prayer_subuh')->default(false);
            $table->boolean('prayer_dhuha')->default(false);  // Bisa diisi via Scanner
            $table->boolean('prayer_dzuhur')->default(false); // Bisa diisi via Scanner
            $table->boolean('prayer_ashar')->default(false);
            $table->boolean('prayer_maghrib')->default(false);
            $table->boolean('prayer_isya')->default(false);

            // 3. Olahraga (Habit 3)
            $table->boolean('habit_3')->default(false);
            $table->string('habit_3_activity')->nullable(); // Jenis Olahraga (Jogging/Senam)

            // 4. Makan Bergizi (Sebelumnya Habit 5, sekarang urutan ke-4 secara logika)
            // Nama kolom tetap habit_5 agar tidak merusak logika controller lama
            $table->boolean('habit_5')->default(false);
            $table->text('habit_5_menu')->nullable(); // Menu Makanan
            $table->timestamp('mbg_taken_at')->nullable(); // Waktu ambil makan (Integrasi Scanner)

            // 5. Gemar Belajar (Sebelumnya Habit 4, sekarang urutan ke-5 secara logika)
            // Nama kolom tetap habit_4
            $table->boolean('habit_4')->default(false);
            $table->string('habit_4_subject')->nullable(); // Mapel/Topik

            // 6. Bermasyarakat (Membantu Orang Tua)
            $table->boolean('habit_6')->default(false);
            $table->text('habit_6_activity')->nullable(); // Kegiatan

            // 7. Tidur Cukup
            $table->boolean('habit_7')->default(false);
            $table->string('habit_7_time', 10)->nullable(); // Jam Tidur (21:00)

            // --- BUKTI & VALIDASI ---
            $table->string('photo_path')->nullable(); // Foto Kolase
            $table->text('student_note')->nullable(); // Catatan harian siswa (Diary singkat)
            
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null'); // Guru yang memvalidasi
            $table->text('teacher_feedback')->nullable(); // Komentar Guru
            $table->timestamp('validated_at')->nullable(); // Waktu validasi

            $table->timestamps();

            // Mencegah duplikasi entri per hari per siswa
            $table->unique(['student_id', 'report_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_habits');
    }
};