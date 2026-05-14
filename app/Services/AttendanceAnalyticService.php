<?php

namespace App\Services;

use App\Models\AttendanceSiswa;
use Carbon\Carbon;

class AttendanceAnalyticService
{
    /**
     * Dapatkan tanggal 1 Juli tahun ajaran berjalan
     */
    public function getAcademicYearStart()
    {
        return Carbon::now('Asia/Jakarta')->month >= 7 
            ? Carbon::create(Carbon::now('Asia/Jakarta')->year, 7, 1)->toDateString() 
            : Carbon::create(Carbon::now('Asia/Jakarta')->year - 1, 7, 1)->toDateString();
    }

    /**
     * Dapatkan statistik kehadiran tahun ajaran ini
     */
    public function getCurrentYearStats($studentId, $academicYearStart)
    {
        $stats = [
            'hadir' => 0, 'terlambat' => 0, 'sakit' => 0, 'izin' => 0, 'alpa' => 0,
            'attendance_history' => collect([]), 'raw_records' => collect([]),
            'chart_data' => [], 'percentage' => 0,
            'sholat_dhuha' => 0, 'sholat_dhuhur' => 0
        ];

        if (!class_exists(AttendanceSiswa::class)) return $stats;

        // Ambil semua data kehadiran tahun ajaran ini
        $records = AttendanceSiswa::where('student_id', $studentId)
                    ->whereDate('attendance_date', '>=', $academicYearStart)
                    ->orderBy('attendance_date', 'desc')
                    ->get();

        $stats['raw_records'] = $records;
        $stats['attendance_history'] = $records->take(10);
        
        $stats['hadir'] = $records->whereInStrict('status', ['Hadir', 'Masuk', 'Terlambat', 'hadir', 'masuk', 'terlambat'])->count();
        $stats['terlambat'] = $records->whereInStrict('status', ['Terlambat', 'terlambat'])->count();
        $stats['sakit'] = $records->whereInStrict('status', ['Sakit', 'sakit'])->count();
        $stats['izin'] = $records->whereInStrict('status', ['Izin', 'izin'])->count();
        $stats['alpa'] = $records->whereInStrict('status', ['Alfa', 'Alpa', 'Alpha', 'alfa', 'alpa', 'alpha', 'Tanpa Keterangan'])->count();

        $stats['chart_data'] = ['hadir' => $stats['hadir'], 'sakit' => $stats['sakit'], 'izin' => $stats['izin'], 'alpa' => $stats['alpa']];
        
        $total_hari_efektif = $stats['hadir'] + $stats['sakit'] + $stats['izin'] + $stats['alpa'];
        $stats['percentage'] = $total_hari_efektif > 0 ? round(($stats['hadir'] / $total_hari_efektif) * 100) : 0;

        // Statistik Sholat
        $stats['sholat_dhuha'] = $records->where('type', 'Keagamaan')->where('activity', 'Dhuha')->count();
        $stats['sholat_dhuhur'] = $records->where('type', 'Keagamaan')->whereInStrict('activity', ['Dhuhur', 'Dzuhur'])->count();

        return $stats;
    }

    /**
     * Dapatkan Arsip (History) tahun-tahun sebelumnya
     */
    public function getPastYearsArchive($studentId, $academicYearStart)
    {
        $archive = ['attendance' => collect([]), 'religion' => collect([])];

        if (!class_exists(AttendanceSiswa::class)) return $archive;

        $pastRecords = AttendanceSiswa::where('student_id', $studentId)
                        ->whereDate('attendance_date', '<', $academicYearStart)
                        ->get();

        // Grouping Kehadiran
        $archive['attendance'] = $pastRecords->groupBy(function($item) {
            $d = Carbon::parse($item->attendance_date);
            $y = $d->month >= 7 ? $d->year : $d->year - 1;
            return $y . '/' . ($y + 1);
        })->map(function($group, $year) {
            return (object)[
                'academic_year' => $year,
                'hadir' => $group->whereInStrict('status', ['Hadir', 'Masuk', 'Terlambat', 'hadir', 'masuk', 'terlambat'])->count(),
                'sakit' => $group->whereInStrict('status', ['Sakit', 'sakit'])->count(),
                'izin'  => $group->whereInStrict('status', ['Izin', 'izin'])->count(),
                'alpa'  => $group->whereInStrict('status', ['Alfa', 'Alpa', 'Alpha', 'alfa', 'alpa', 'alpha', 'Tanpa Keterangan'])->count(),
            ];
        })->sortByDesc('academic_year');

        // Grouping Keagamaan
        $pastReligion = $pastRecords->filter(fn($att) => strtolower($att->type ?? '') === 'keagamaan');
        $archive['religion'] = $pastReligion->groupBy(function($item) {
            $d = Carbon::parse($item->attendance_date);
            $y = $d->month >= 7 ? $d->year : $d->year - 1;
            return $y . '/' . ($y + 1);
        })->map(function($group, $year) {
            return (object)[
                'academic_year' => $year,
                'dhuha' => $group->filter(fn($q) => str_contains(strtolower($q->activity ?? ''), 'dhuha'))->count(),
                'dhuhur' => $group->filter(fn($q) => str_contains(strtolower($q->activity ?? ''), 'dhuhur') || str_contains(strtolower($q->activity ?? ''), 'dzuhur'))->count(),
            ];
        })->sortByDesc('academic_year');

        return $archive;
    }
}