<?php

namespace App\Console\Commands;

use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendBorrowingReminders extends Command
{
    protected $signature   = 'library:send-reminders';
    protected $description = 'Kirim notifikasi pengingat jatuh tempo & overdue peminjaman buku perpustakaan';

    public function handle()
    {
        $today    = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // ─────────────────────────────────────────────
        // 1. JATUH TEMPO BESOK (H-1 Reminder)
        // ─────────────────────────────────────────────
        $dueTomorrow = Borrowing::with(['student', 'book'])
            ->where('status', 'borrowed')
            ->whereDate('due_date', $tomorrow)
            ->get();

        $this->info("📅 Ditemukan {$dueTomorrow->count()} peminjaman jatuh tempo besok.");

        foreach ($dueTomorrow as $loan) {
            $this->recordNotification($loan, 'due_soon',
                "Pengingat: Buku \"{$loan->book?->title}\" yang dipinjam oleh {$loan->student?->name} akan jatuh tempo besok ({$tomorrow->format('d/m/Y')})."
            );
        }

        // ─────────────────────────────────────────────
        // 2. SUDAH OVERDUE (Terlambat)
        // ─────────────────────────────────────────────
        $overdue = Borrowing::with(['student', 'book'])
            ->where('status', 'borrowed')
            ->whereDate('due_date', '<', $today)
            ->get();

        $this->info("⚠️  Ditemukan {$overdue->count()} peminjaman terlambat.");

        foreach ($overdue as $loan) {
            $days = $today->diffInDays(Carbon::parse($loan->due_date));
            $fine = $days * 500;

            $this->recordNotification($loan, 'overdue',
                "TERLAMBAT {$days} hari! Buku \"{$loan->book?->title}\" (peminjam: {$loan->student?->name}) belum dikembalikan. Estimasi denda: Rp " . number_format($fine, 0, ',', '.')
            );
        }

        $this->info('✅ Notifikasi berhasil diproses.');
        $this->table(
            ['Status', 'Jumlah'],
            [
                ['Jatuh Tempo Besok', $dueTomorrow->count()],
                ['Overdue / Terlambat', $overdue->count()],
            ]
        );

        return self::SUCCESS;
    }

    /**
     * Simpan notifikasi ke tabel library_notifications (upsert per hari)
     */
    private function recordNotification(Borrowing $loan, string $type, string $message): void
    {
        try {
            DB::table('library_notifications')->updateOrInsert(
                [
                    'borrowing_id' => $loan->id,
                    'type'         => $type,
                    'date'         => Carbon::today()->toDateString(),
                ],
                [
                    'student_id' => $loan->student_id,
                    'message'    => $message,
                    'is_read'    => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        } catch (\Exception $e) {
            $this->warn("Gagal simpan notifikasi borrowing #{$loan->id}: " . $e->getMessage());
        }
    }
}
