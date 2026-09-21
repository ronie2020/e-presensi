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
            if (!Schema::hasColumn('students', 'nik')) {
                $table->string('nik', 16)->nullable()->after('nisn');
            }
            if (!Schema::hasColumn('students', 'birth_place')) {
                $table->string('birth_place')->nullable()->after('pob');
            }
            if (!Schema::hasColumn('students', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('dob');
            }
            if (!Schema::hasColumn('students', 'guardian_phone')) {
                $table->string('guardian_phone')->nullable();
            }
            if (!Schema::hasColumn('students', 'parent_phone')) {
                $table->string('parent_phone')->nullable();
            }
            if (!Schema::hasColumn('students', 'parent_income')) {
                $table->string('parent_income')->nullable();
            }
            if (!Schema::hasColumn('students', 'join_date')) {
                $table->date('join_date')->nullable();
            }
            if (!Schema::hasColumn('students', 'password')) {
                $table->string('password')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'birth_place',
                'birth_date',
                'guardian_phone',
                'parent_phone',
                'parent_income',
                'join_date',
                'password',
            ]);
        });
    }
};
