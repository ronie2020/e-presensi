<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration
    {
        public function up(): void
        {
            Schema::table('users', function (Blueprint $table) {
                // Cek satu per satu, jika belum ada, tambahkan!
                
                if (!Schema::hasColumn('users', 'photo_path')) {
                    $table->string('photo_path')->nullable()->after('email');
                }
                
                if (!Schema::hasColumn('users', 'position')) {
                    $table->string('position')->nullable()->after('email');
                }
                
                if (!Schema::hasColumn('users', 'bio')) {
                    $table->text('bio')->nullable()->after('position');
                }
                
                if (!Schema::hasColumn('users', 'nip')) {
                    $table->string('nip')->nullable()->after('bio');
                }
                
                // Tambahkan kolom sosmed sekalian biar aman
                if (!Schema::hasColumn('users', 'phone')) $table->string('phone')->nullable()->after('nip');
                if (!Schema::hasColumn('users', 'instagram')) $table->string('instagram')->nullable()->after('phone');
                if (!Schema::hasColumn('users', 'tiktok')) $table->string('tiktok')->nullable()->after('instagram');
                if (!Schema::hasColumn('users', 'facebook')) $table->string('facebook')->nullable()->after('tiktok');
            });
        }
    
        public function down(): void
        {
            // Tidak perlu rollback kompleks untuk fix ini
        }
    };