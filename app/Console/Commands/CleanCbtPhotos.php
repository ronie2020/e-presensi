<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanCbtPhotos extends Command
{
    /**
     * Nama perintah yang dijalankan di terminal:
     * php artisan cbt:clean-photos --days=30
     */
    protected $signature = 'cbt:clean-photos {--days=30 : Jumlah hari batas usia foto yang disimpan}';

    /**
     * Deskripsi perintah console.
     */
    protected $description = 'Menghapus foto pengawas (proctoring webcam) CBT lama dari disk storage dan database';

    public function handle()
    {
        $days = (int) $this->option('days');
        if ($days < 1) $days = 30;

        $cutoffDate = Carbon::now()->subDays($days);
        $this->info("Memulai pembersihan foto webcam CBT yang lebih tua dari {$days} hari (sebelum {$cutoffDate->format('Y-m-d H:i:s')})...");

        // Ambil data foto tua dari DB
        $photos = DB::table('cbt_exam_photos')
            ->where('captured_at', '<', $cutoffDate)
            ->get();

        if ($photos->isEmpty()) {
            $this->info('Tidak ada foto webcam lama yang perlu dihapus.');
            return 0;
        }

        $deletedFilesCount = 0;
        $idsToDelete = [];

        foreach ($photos as $photo) {
            if (!empty($photo->photo_path)) {
                if (Storage::disk('public')->exists($photo->photo_path)) {
                    Storage::disk('public')->delete($photo->photo_path);
                    $deletedFilesCount++;
                }
            }
            $idsToDelete[] = $photo->id;
        }

        // Hapus record dari DB
        if (!empty($idsToDelete)) {
            DB::table('cbt_exam_photos')->whereIn('id', $idsToDelete)->delete();
        }

        $this->info("Selesai! Berhasil menghapus {$deletedFilesCount} file foto dan " . count($idsToDelete) . " record database.");
        return 0;
    }
}
