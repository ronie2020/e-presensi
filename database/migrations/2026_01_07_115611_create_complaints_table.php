<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('complaints', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->nullable()->constrained()->onDelete('cascade'); // Nullable jika ingin super anonim (opsional)
        $table->string('category'); // Bullying, Fasilitas, Kehilangan, Lainnya
        $table->text('description');
        $table->string('location')->nullable(); // Lokasi kejadian
        $table->date('incident_date');
        $table->string('evidence_path')->nullable(); // Foto bukti
        $table->boolean('is_anonymous')->default(false);
        $table->enum('status', ['pending', 'investigating', 'resolved', 'rejected'])->default('pending');
        $table->text('admin_note')->nullable(); // Catatan penanganan
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
