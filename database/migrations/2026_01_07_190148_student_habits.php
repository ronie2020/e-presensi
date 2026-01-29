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
            
            // Status Udzur Syar'i (Haid/Sakit)
            $table->boolean('is_udzur_syar_i')->default(false);
            
            // --- 7 KEBIASAAN ---
            
            // 1. Bangun Pagi, Mandi & Rapi
            $table->boolean('habit_1')->default(false); 
            $table->string('habit_1_time', 10)->nullable(); 
            $table->string('habit_1_note')->nullable(); 
            $table->boolean('habit_2')->default(false); 

            // 2. Shalat Tepat Waktu
            $table->boolean('prayer_subuh')->default(false);
            $table->boolean('prayer_dhuha')->default(false);
            $table->boolean('prayer_dzuhur')->default(false);
            $table->boolean('prayer_ashar')->default(false);
            $table->boolean('prayer_maghrib')->default(false);
            $table->boolean('prayer_isya')->default(false);

            // Fitur ODOA (One Day One Ayat)
            $table->string('odoa_surah')->nullable();
            $table->string('odoa_ayat')->nullable();
            $table->string('odoa_audio_path')->nullable(); 

            // 3. Olahraga
            $table->boolean('habit_3')->default(false);
            $table->string('habit_3_activity')->nullable(); 

            // 4. Makan Bergizi (Habit 5)
            $table->boolean('habit_5')->default(false);
            $table->text('habit_5_menu')->nullable(); 
            $table->timestamp('mbg_taken_at')->nullable(); 

            // 5. Gemar Belajar (Habit 4)
            $table->boolean('habit_4')->default(false);
            $table->string('habit_4_subject')->nullable(); 

            // 6. Bermasyarakat (Bantu Orang Tua)
            $table->boolean('habit_6')->default(false);
            $table->text('habit_6_activity')->nullable(); 

            // 7. Tidur Cukup
            $table->boolean('habit_7')->default(false);
            $table->string('habit_7_time', 10)->nullable(); 

            // --- BUKTI & VALIDASI ---
            $table->string('photo_path')->nullable(); 
            $table->text('student_note')->nullable(); 
            
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null'); 
            $table->text('teacher_feedback')->nullable(); 
            $table->timestamp('validated_at')->nullable(); 

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