<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\DisciplineRecord;
use App\Models\DisciplineType;
use Carbon\Carbon;

class ProcessPointDecay extends Command
{
    // Nama perintah yang dijalankan di terminal
    protected $signature = 'discipline:point-decay';
    protected $description = 'Memberikan bonus pemulihan poin otomatis bagi siswa tanpa pelanggaran dalam 30 hari';

    public function handle()
    {
        $this->info('Memulai pengecekan Point Decay...');

        // 1. Cari atau buat tipe Point Decay
        $decayType = DisciplineType::firstOrCreate(
            ['name' => 'Bonus Perilaku Baik (Otomatis Monthly Decay)'],
            ['type' => 'Kebaikan', 'point_value' => 20] 
        );

        $students = Student::where('status', 'active')->get();
        $count = 0;

        foreach ($students as $student) {
            // Cek apakah ada record pelanggaran dalam 30 hari terakhir
            $hasRecentViolation = DisciplineRecord::where('student_id', $student->id)
                ->whereHas('disciplineType', fn($q) => $q->where('type', 'Pelanggaran'))
                ->where('date', '>=', Carbon::now()->subDays(30))
                ->exists();

            if (!$hasRecentViolation) {
                // Pastikan bulan ini belum diberikan bonus agar tidak double
                $alreadyDecayed = DisciplineRecord::where('student_id', $student->id)
                    ->where('discipline_type_id', $decayType->id)
                    ->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year)
                    ->exists();

                if (!$alreadyDecayed) {
                    DisciplineRecord::create([
                        'student_id' => $student->id,
                        'discipline_type_id' => $decayType->id,
                        'notes' => 'Sistem: Bonus pemulihan poin (Point Decay) karena berkelakuan baik selama 30 hari.',
                        'date' => Carbon::today(),
                    ]);
                    $count++;
                }
            }
        }

        $this->info("Selesai! Berhasil memberikan bonus pemulihan kepada {$count} siswa.");
    }
}