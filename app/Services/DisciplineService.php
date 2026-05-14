<?php

namespace App\Services;

use App\Models\DisciplineRecord;
use App\Models\Achievement;
use App\Models\BkSession;
use Carbon\Carbon;

class DisciplineService
{
    /**
     * Hitung profil kedisiplinan (Pelanggaran & Kebaikan)
     */
    public function getDisciplineProfile($studentId, $academicYearStart, $rawAttendanceRecords)
    {
        $profile = [
            'violations' => collect([]), 'achievements' => collect([]),
            'total_violation_points' => 0, 'total_merit_points' => 0,
            'finalScore' => 100, 'alerts' => collect([]), 'amnestyTasks' => []
        ];

        // 1. DATA PELANGGARAN MANUAL & OTOMATIS
        $manualViolations = collect([]);
        if (class_exists(DisciplineRecord::class)) {
            $manualViolations = DisciplineRecord::with(['disciplineType', 'recorder']) 
                ->where('student_id', $studentId)->whereDate('created_at', '>=', $academicYearStart)
                ->get()->filter(function($record) {
                    return in_array(strtolower(optional($record->disciplineType)->type ?? $record->type ?? ''), ['violation', 'pelanggaran']);
                })->map(function($item) {
                    return (object) [
                        'date' => $item->date, 'notes' => $item->notes ?? optional($item->disciplineType)->name ?? 'Pelanggaran',
                        'point' => optional($item->disciplineType)->point_value ?? $item->point ?? 0, 'type' => 'manual',
                        'recorder' => $item->recorder ?? (object)['name' => 'Admin/Guru'], 'disciplineType' => $item->disciplineType ?? (object)['name' => 'Pelanggaran', 'point_value' => 0]
                    ];
                });
        }

        $manualAlpaDates = $manualViolations->filter(function($record) {
            $text = strtolower(($record->notes ?? '') . ' ' . optional($record->disciplineType)->name);
            return str_contains($text, 'alfa') || str_contains($text, 'alpa') || str_contains($text, 'bolos');
        })->map(fn($record) => Carbon::parse($record->date)->toDateString())->toArray();

        $alpaViolations = $rawAttendanceRecords->filter(function ($att) use ($manualAlpaDates) {
            $isAlfa = in_array(strtolower($att->status), ['alfa', 'alpa', 'alpha']);
            return $isAlfa && !in_array(Carbon::parse($att->attendance_date)->toDateString(), $manualAlpaDates);
        })->map(fn($att) => (object) ['date' => $att->attendance_date, 'notes' => 'Ketidakhadiran Tanpa Keterangan (Alpa)', 'point' => 10, 'type' => 'auto', 'recorder' => (object) ['name' => 'Sistem Otomatis'], 'disciplineType' => (object) ['name' => 'Absensi (Alpha)', 'point_value' => 10]]);

        $lateViolations = $rawAttendanceRecords->filter(fn($att) => in_array(strtolower($att->status), ['terlambat']))
            ->map(fn($att) => (object) ['date' => $att->attendance_date, 'notes' => $att->notes ?? 'Terlambat Datang Sekolah', 'point' => 5, 'type' => 'auto_late', 'recorder' => (object) ['name' => 'Sistem Otomatis'], 'disciplineType' => (object) ['name' => 'Keterlambatan', 'point_value' => 5]]);

        $profile['violations'] = $manualViolations->concat($alpaViolations)->concat($lateViolations)->sortByDesc('date');

        // 2. DATA KEBAIKAN & PRESTASI MANUAL & OTOMATIS
        $manualMerits = collect([]);
        if (class_exists(DisciplineRecord::class)) {
            $manualMerits = DisciplineRecord::with(['disciplineType', 'recorder'])->where('student_id', $studentId)->whereDate('created_at', '>=', $academicYearStart)
                ->get()->filter(fn($record) => in_array(strtolower(optional($record->disciplineType)->type ?? $record->type ?? ''), ['merit', 'prestasi', 'kebaikan']))
                ->map(fn($item) => (object) [
                        'date' => $item->date, 'notes' => $item->notes ?? optional($item->disciplineType)->name ?? 'Prestasi',
                        'point' => optional($item->disciplineType)->point_value ?? $item->point ?? 0, 'type' => 'manual_merit', 'photo' => null,
                        'recorder' => $item->recorder ?? (object)['name' => 'Admin/Guru'], 'disciplineType' => $item->disciplineType ?? (object)['name' => 'Prestasi', 'point_value' => 0]
                    ]);
        }

        $prayerAchievements = $rawAttendanceRecords->filter(function ($att) {
            $isPrayer = str_contains(strtolower($att->activity ?? ''), 'dhuha') || str_contains(strtolower($att->activity ?? ''), 'dhuhur') || str_contains(strtolower($att->activity ?? ''), 'dzuhur');
            return (strtolower($att->type ?? '') === 'keagamaan') && $isPrayer;
        })->map(fn($att) => (object) ['date' => $att->attendance_date, 'notes' => "Melaksanakan Shalat " . ucfirst($att->activity ?? 'Ibadah') . " Berjamaah", 'point' => 5, 'type' => 'auto_prayer', 'photo' => null, 'recorder' => (object) ['name' => 'Sistem Otomatis'], 'disciplineType' => (object) ['name' => 'Kegiatan Keagamaan', 'point_value' => 5]]);

        $realAchievements = collect([]);
        if (class_exists(Achievement::class)) {
            $realAchievements = Achievement::where('student_id', $studentId)->whereDate('created_at', '>=', $academicYearStart)->get()
                ->map(fn($item) => (object) ['date' => $item->date, 'notes' => $item->description ?? $item->title, 'point' => 0, 'type' => 'achievement_record', 'title' => $item->title, 'level' => $item->level, 'photo' => $item->photo_path, 'certificate_path' => $item->certificate_path, 'status' => $item->status, 'recorder' => (object) ['name' => 'Laporan Prestasi'], 'disciplineType' => (object) ['name' => 'Kejuaraan / Prestasi', 'point_value' => 0]]);
        }

        $profile['achievements'] = $realAchievements->concat($manualMerits)->concat($prayerAchievements)->sortByDesc('date');

        // 3. KALKULASI SKOR
        $profile['total_violation_points'] = $profile['violations']->sum(fn($v) => $v->point ?? $v->disciplineType->point_value ?? 0);
        $profile['total_merit_points'] = $manualMerits->sum(fn($a) => $a->point ?? $a->disciplineType->point_value ?? 0) + $prayerAchievements->sum(fn($a) => $a->point ?? 0);
        $profile['finalScore'] = 100 - $profile['total_violation_points'] + $profile['total_merit_points'];

        // 4. ALERTS & TUGAS AMNESTI
        if (class_exists(BkSession::class)) {
            $upcomingBk = BkSession::where('student_id', $studentId)->where('status', 'approved')->whereBetween('scheduled_at', [Carbon::now(), Carbon::now()->addHours(24)])->first();
            if ($upcomingBk) {
                $profile['alerts']->push(['type' => 'bk_schedule', 'title' => 'Jadwal BK Mendatang', 'color' => 'blue', 'icon' => 'ph-fill ph-clock-countdown', 'message' => 'Kamu memiliki jadwal konseling pada ' . Carbon::parse($upcomingBk->scheduled_at)->translatedFormat('H:i') . ' WIB.']);
            }
        }
        if ($profile['finalScore'] < 60) {
            $profile['alerts']->push(['type' => 'character_warning', 'title' => 'Skor Perilaku Kritis', 'color' => 'rose', 'icon' => 'ph-fill ph-warning-octagon', 'message' => 'Skor karakter kamu berada di zona merah (' . $profile['finalScore'] . '). Segera lakukan tindakan pemulihan poin.']);
        }

        $profile['amnestyTasks'] = [
            ['title' => 'Membantu Inventaris Perpustakaan', 'points' => 10, 'icon' => 'ph-book-open'],
            ['title' => 'Piket Kebersihan Masjid Sekolah', 'points' => 15, 'icon' => 'ph-mosque'],
            ['title' => 'Membantu Administrasi Tata Usaha', 'points' => 10, 'icon' => 'ph-files'],
            ['title' => 'Partisipasi Penanaman Pohon', 'points' => 20, 'icon' => 'ph-leaf'],
        ];

        return $profile;
    }
}