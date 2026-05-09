<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('literacy_journals', function (Blueprint $table) {
            // Menambahkan kolom rating (1-5 bintang)
            $table->tinyInteger('rating')->default(0)->after('pages_read');
            
            // Menambahkan kolom tokoh favorit (opsional)
            $table->string('favorite_character')->nullable()->after('rating');
            
            // Menambahkan kolom kosakata baru (opsional)
            $table->string('new_vocabulary')->nullable()->after('favorite_character');
            
            // Menambahkan status validasi (pending, verified, rejected)
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending')->after('proof_image');
            
            // Menambahkan alasan penolakan jika status = rejected
            $table->text('rejection_reason')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('literacy_journals', function (Blueprint $table) {
            $table->dropColumn([
                'rating',
                'favorite_character',
                'new_vocabulary',
                'status',
                'rejection_reason'
            ]);
        });
    }
};