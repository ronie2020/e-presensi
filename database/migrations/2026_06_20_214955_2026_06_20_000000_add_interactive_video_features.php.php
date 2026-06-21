<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
   public function up()
    {
        // 1. UPDATE TABEL lms_assignments
        // UBAH DARI ENUM MENJADI VARCHAR AGAR AMAN DI HOSTING DAN TIDAK ERROR DATA TRUNCATED
        DB::statement("ALTER TABLE lms_assignments MODIFY COLUMN assignment_type VARCHAR(50) NOT NULL DEFAULT 'file_upload'");

        Schema::table('lms_assignments', function (Blueprint $table) {
            // Tambahkan kolom youtube_url HANYA jika kolom tersebut belum ada
            if (!Schema::hasColumn('lms_assignments', 'youtube_url')) {
                $table->string('youtube_url')->nullable()->after('title');
            }
        });

        // 2. Buat Tabel Titik Kuis Video (interactive_questions)
        if (!Schema::hasTable('lms_interactive_questions')) {
            Schema::create('lms_interactive_questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('assignment_id')->constrained('lms_assignments')->onDelete('cascade');
                $table->integer('minute');
                $table->integer('second');
                $table->integer('total_seconds'); 
                $table->text('question_text');
                $table->timestamps();
            });
        }

        // 3. Buat Tabel Pilihan Ganda Video (interactive_options)
        if (!Schema::hasTable('lms_interactive_options')) {
            Schema::create('lms_interactive_options', function (Blueprint $table) {
                $table->id();
                $table->foreignId('interactive_question_id')->constrained('lms_interactive_questions')->onDelete('cascade');
                $table->string('option_label', 1); // 'A', 'B', 'C', 'D'
                $table->string('option_text');
                $table->boolean('is_correct')->default(false);
                $table->timestamps();
            });
        }

        // 4. Update Tabel lms_submissions (Sesi Pengerjaan Siswa)
        Schema::table('lms_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('lms_submissions', 'status')) {
                $table->string('status')->default('pending')->after('teacher_feedback');
            }
            if (!Schema::hasColumn('lms_submissions', 'highest_watched_second')) {
                $table->integer('highest_watched_second')->default(0)->after('status');
            }
        });

        // 5. Buat Tabel Jawaban Kuis Video (interactive_answers)
        if (!Schema::hasTable('lms_interactive_answers')) {
            Schema::create('lms_interactive_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('submission_id')->constrained('lms_submissions')->onDelete('cascade');
                $table->foreignId('interactive_question_id')->constrained('lms_interactive_questions')->onDelete('cascade');
                $table->foreignId('selected_option_id')->constrained('lms_interactive_options')->onDelete('cascade');
                $table->boolean('is_correct');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('lms_interactive_answers');
        Schema::dropIfExists('lms_interactive_options');
        Schema::dropIfExists('lms_interactive_questions');
        
        Schema::table('lms_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('lms_submissions', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('lms_submissions', 'highest_watched_second')) {
                $table->dropColumn('highest_watched_second');
            }
        });

        Schema::table('lms_assignments', function (Blueprint $table) {
            if (Schema::hasColumn('lms_assignments', 'youtube_url')) {
                $table->dropColumn('youtube_url');
            }
        });
        
        // Kembalikan ke VARCHAR jika di-rollback agar tidak memicu error ENUM lagi
        DB::statement("ALTER TABLE lms_assignments MODIFY COLUMN assignment_type VARCHAR(50) NOT NULL DEFAULT 'file_upload'");
    }
};