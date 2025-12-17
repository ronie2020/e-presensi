<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // ==========================================
    // KONFIGURASI SESUAI FILE YANG KAMU KIRIM
    // ==========================================
    protected $table_users    = 'users';           
    protected $table_students = 'students';        
    protected $table_subjects = 'subjects';        
    
    // Sesuai file '2025_11_14...create_classes_table.php' kamu:
    protected $table_classes  = 'classes';  
    
    public function up()
    {
        // 1. Bersihkan tabel lama (jika ada sisa error)
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('lms_submissions');
        Schema::dropIfExists('lms_assignments');
        Schema::dropIfExists('lms_materials');
        Schema::enableForeignKeyConstraints();

        // 2. Tabel Materi Pelajaran
        Schema::create('lms_materials', function (Blueprint $table) {
            $table->id();
            
            // Relasi Guru
            $table->unsignedBigInteger('teacher_id');
            $table->foreign('teacher_id')->references('id')->on($this->table_users)->onDelete('cascade');
            
            // Relasi Mapel
            $table->unsignedBigInteger('subject_id');
            $table->foreign('subject_id')->references('id')->on($this->table_subjects)->onDelete('cascade');
            
            // Relasi Kelas (Menggunakan BIGINT sesuai $table->id() di tabel classes)
            $table->unsignedBigInteger('class_id')->nullable(); 
            $table->foreign('class_id')->references('id')->on($this->table_classes)->onDelete('set null');
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable(); 
            $table->string('video_link')->nullable(); 
            $table->enum('type', ['document', 'video', 'link'])->default('document');
            $table->timestamps();
        });

        // 3. Tabel Penugasan (Kantong Tugas)
        Schema::create('lms_assignments', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('teacher_id');
            $table->foreign('teacher_id')->references('id')->on($this->table_users)->onDelete('cascade');

            $table->unsignedBigInteger('subject_id');
            $table->foreign('subject_id')->references('id')->on($this->table_subjects)->onDelete('cascade');

            // Relasi Kelas
            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on($this->table_classes)->onDelete('cascade');
            
            $table->string('title');
            $table->text('description'); 
            $table->dateTime('deadline');
            $table->boolean('allow_late_submission')->default(false); 
            $table->timestamps();
        });

        // 4. Tabel Pengumpulan Tugas Siswa
        Schema::create('lms_submissions', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('assignment_id')->constrained('lms_assignments')->onDelete('cascade');
            
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on($this->table_students)->onDelete('cascade');
            
            $table->string('file_path')->nullable(); 
            $table->text('student_note')->nullable(); 
            $table->dateTime('submitted_at'); 
            
            $table->integer('grade')->nullable(); 
            $table->text('teacher_feedback')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('lms_submissions');
        Schema::dropIfExists('lms_assignments');
        Schema::dropIfExists('lms_materials');
        Schema::enableForeignKeyConstraints();
    }
};