<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. TABEL KATEGORI BUKU
        Schema::create('book_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Misal: Fiksi, Pelajaran, Ensiklopedia
            $table->string('code')->nullable(); // Misal: FKS, PLJ
            $table->timestamps();
        });

        // 2. TABEL BUKU
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('book_code')->unique(); // Kode Buku / Barcode
            $table->string('title'); // Judul
            $table->foreignId('category_id')->nullable()->constrained('book_categories')->onDelete('set null');
            $table->string('author')->nullable(); // Pengarang
            $table->string('publisher')->nullable(); // Penerbit
            $table->year('year')->nullable(); // Tahun Terbit
            $table->string('isbn')->nullable(); 
            $table->integer('stock')->default(0); // Jumlah Stok
            $table->string('shelf_location')->nullable(); // Lokasi Rak (Misal: Rak A-1)
            $table->text('description')->nullable(); // Sinopsis singkat
            $table->string('cover_path')->nullable(); // Foto Cover Buku
            $table->timestamps();
        });

        // 3. TABEL TRANSAKSI PEMINJAMAN
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            // Relasi ke Siswa (Peminjam)
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            // Relasi ke Buku
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            
            $table->date('borrow_date'); // Tanggal Pinjam
            $table->date('due_date'); // Tanggal Jatuh Tempo (Harus Kembali)
            $table->date('return_date')->nullable(); // Tanggal Kembali Sebenarnya (Diisi saat kembali)
            
            $table->enum('status', ['borrowed', 'returned', 'lost', 'damaged'])->default('borrowed'); // Status
            
            $table->integer('fine_amount')->default(0); // Jumlah Denda (Rp)
            $table->text('notes')->nullable(); // Catatan (misal: buku sobek sedikit)
            
            // Mencatat siapa petugas yang melayani (User)
            $table->foreignId('served_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
        Schema::dropIfExists('books');
        Schema::dropIfExists('book_categories');
    }
};