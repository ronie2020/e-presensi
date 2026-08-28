<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // CEK UNTUK TABEL MATERI (Bisa bernama 'materials' atau 'lms_materials')
        $materialTable = Schema::hasTable('lms_materials') ? 'lms_materials' : 'materials';
        
        if (Schema::hasTable($materialTable)) {
            Schema::table($materialTable, function (Blueprint $table) {
                // Menambahkan kolom tanpa constraint foreign key yang kaku dulu untuk menghindari error
                $table->unsignedBigInteger('topic_id')->nullable()->after('subject_id');
                $table->integer('order_in_topic')->default(1)->after('topic_id');
            });
        }

        // CEK UNTUK TABEL TUGAS (Bisa bernama 'assignments' atau 'lms_assignments')
        $assignmentTable = Schema::hasTable('lms_assignments') ? 'lms_assignments' : 'assignments';
        
        if (Schema::hasTable($assignmentTable)) {
            Schema::table($assignmentTable, function (Blueprint $table) {
                $table->unsignedBigInteger('topic_id')->nullable()->after('subject_id');
                $table->integer('order_in_topic')->default(99)->after('topic_id');
            });
        }
    }

    public function down()
    {
        $materialTable = Schema::hasTable('lms_materials') ? 'lms_materials' : 'materials';
        if (Schema::hasTable($materialTable)) {
            Schema::table($materialTable, function (Blueprint $table) {
                $table->dropColumn(['topic_id', 'order_in_topic']);
            });
        }

        $assignmentTable = Schema::hasTable('lms_assignments') ? 'lms_assignments' : 'assignments';
        if (Schema::hasTable($assignmentTable)) {
            Schema::table($assignmentTable, function (Blueprint $table) {
                $table->dropColumn(['topic_id', 'order_in_topic']);
            });
        }
    }
};