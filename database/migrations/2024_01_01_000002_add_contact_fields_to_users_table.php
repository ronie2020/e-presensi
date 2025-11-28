<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek satu per satu agar tidak error "Duplicate column"
            
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('nip');
            }
            
            if (!Schema::hasColumn('users', 'instagram')) {
                $table->string('instagram')->nullable()->after('phone');
            }
            
            if (!Schema::hasColumn('users', 'tiktok')) {
                $table->string('tiktok')->nullable()->after('instagram');
            }
            
            if (!Schema::hasColumn('users', 'facebook')) {
                $table->string('facebook')->nullable()->after('tiktok');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'instagram', 'tiktok', 'facebook']);
        });
    }
};