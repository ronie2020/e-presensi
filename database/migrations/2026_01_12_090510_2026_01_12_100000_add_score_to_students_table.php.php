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
            Schema::table('students', function (Blueprint $table) {
                // Menambahkan kolom score dengan default 0
                // Anda bisa letakkan setelah kolom tertentu jika mau, misal 'status'
                if (!Schema::hasColumn('students', 'score')) {
                    $table->integer('score')->default(0)->after('status');
                }
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('students', function (Blueprint $table) {
                if (Schema::hasColumn('students', 'score')) {
                    $table->dropColumn('score');
                }
            });
        }
    };