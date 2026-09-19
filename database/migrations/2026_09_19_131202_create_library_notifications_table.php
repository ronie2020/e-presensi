<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['due_soon', 'overdue', 'reservation_ready', 'system'])->default('system');
            $table->text('message');
            $table->date('date');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['student_id', 'is_read']);
            $table->index(['type', 'date']);
            $table->unique(['borrowing_id', 'type', 'date'], 'unique_daily_notif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_notifications');
    }
};
