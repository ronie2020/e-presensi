<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ScheduleSpecial;
use App\Models\PiketSchedule;
use App\Models\PiketGuest;
use App\Models\Student;
use App\Models\AttendanceSiswa;
use App\Models\Agenda;
use App\Models\Announcement;

class DisplayController extends Controller
{
    /**
     * Menampilkan halaman UI Display.
     */
    public function index()
    {
        return view('display.schedules');
    }

    /**
     * Endpoint API JSON untuk ditarik secara real-time oleh Alpine.js.
     */
    public function getData()
    {
        $dayNames = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
        $today = Carbon::now(config('app.timezone', 'Asia/Jakarta'));
        $todayName = $dayNames[$today->dayOfWeek];
        $todayDate = $today->toDateString();

        // 1. Cek apakah hari ini ditandai sebagai hari libur di Jadwal Khusus
        $todaySpecial = ScheduleSpecial::whereDate('date', $todayDate)->first();
        $isHoliday = (bool) ($todaySpecial->is_holiday ?? false);
        $holidayReason = $todaySpecial->description ?? null;

        // 2. Jadwal Bel & Jam Pembelajaran
        $schedules = collect();
        if (!$isHoliday) {
            $schedules = DB::table('learning_schedules')
                ->where('day_type', $todayName)
                ->orderBy('trigger_time', 'asc')
                ->get();
        }

        // 3. Guru Piket Hari Ini
        $dutyTeachers = PiketSchedule::with('teacher')
            ->where('day_name', $todayName)
            ->orderBy('order_number')
            ->get()
            ->map(function ($duty) {
                return [
                    'name'          => $duty->teacher->name ?? 'Guru Piket',
                    'nip'           => $duty->teacher->nip ?? null,
                    'post_location' => $duty->post_location ?: 'Lobi Utama',
                    'is_coordinator'=> (bool) $duty->is_coordinator,
                    'photo_url'     => $duty->teacher->photo_path ? asset('storage/' . $duty->teacher->photo_path) : null,
                ];
            });

        // 4. Statistik Presensi Siswa Real-Time Hari Ini
        $totalActiveStudents = Student::where('status', 'active')->count();
        $attendanceToday = AttendanceSiswa::where('attendance_date', $todayDate)
            ->selectRaw("
                COUNT(CASE WHEN status IN ('Hadir', 'Terlambat') THEN 1 END) as hadir,
                COUNT(CASE WHEN status = 'Terlambat' THEN 1 END) as terlambat,
                COUNT(CASE WHEN status = 'Sakit' THEN 1 END) as sakit,
                COUNT(CASE WHEN status = 'Izin' THEN 1 END) as izin
            ")->first();

        $hadirCount = (int) ($attendanceToday->hadir ?? 0);
        $attendanceRate = $totalActiveStudents > 0 
            ? round(($hadirCount / $totalActiveStudents) * 100, 1) 
            : 0;

        // 5. Agenda & Kegiatan Terdekat (7 hari ke depan)
        $upcomingAgendas = Agenda::whereDate('event_date', '>=', $todayDate)
            ->orderBy('event_date', 'asc')
            ->limit(3)
            ->get()
            ->map(function ($agenda) {
                return [
                    'title'    => $agenda->title,
                    'location' => $agenda->location ?: 'Kampus Sekolah',
                    'date'     => $agenda->event_date ? $agenda->event_date->translatedFormat('d M Y') : '-',
                ];
            });

        // 6. Siswa Teladan / Poin Pembiasaan Karakter Teratas (Top 4)
        $topStudents = Student::with('schoolClass')
            ->where('status', 'active')
            ->orderBy('score', 'desc')
            ->limit(4)
            ->get()
            ->map(function ($std) {
                return [
                    'name'      => $std->name,
                    'class'     => $std->schoolClass->name ?? '-',
                    'score'     => $std->score ?? 100,
                    'photo_url' => $std->photo_path ? asset('storage/' . $std->photo_path) : null,
                ];
            });

        // 7. Pesan Ticker / Running Text
        $runningTexts = collect();

        // Cek apakah ada Tamu VIP hari ini di meja piket
        $vipGuests = PiketGuest::whereDate('created_at', $todayDate)
            ->where('is_vip', true)
            ->get();

        foreach ($vipGuests as $vip) {
            $runningTexts->push("SELAMAT DATANG: Yth. {$vip->guest_name} ({$vip->institution}) di Sekolah Kami. Keperluan: {$vip->purpose}");
        }

        // Pengumuman Aktif
        $announcements = Announcement::where('is_active', true)
            ->latest()
            ->limit(4)
            ->pluck('title');

        foreach ($announcements as $ann) {
            $runningTexts->push("PENGUMUMAN: {$ann}");
        }

        // Default Slogan & Motivasi jika pengumuman kosong
        if ($runningTexts->isEmpty()) {
            $runningTexts->push("Selamat datang di Sistem Informasi Sekolah Terpadu — Wujudkan generasi beriman, berakhlak mulia, dan berprestasi unggul.");
            $runningTexts->push("Budayakan 5S: Senyum, Salam, Sapa, Sopan, dan Santun di lingkungan sekolah.");
            $runningTexts->push("Tepat Waktu Adalah Cermin Disiplin — Hadir sebelum bel berbunyi.");
        }

        return response()->json([
            'server_time'       => $today->toIso8601String(),
            'day_name'          => $todayName,
            'is_holiday'        => $isHoliday,
            'holiday_reason'    => $holidayReason,
            'schedules'         => $schedules,
            'duty_teachers'     => $dutyTeachers,
            'attendance_stats'  => [
                'total'     => $totalActiveStudents,
                'hadir'     => $hadirCount,
                'terlambat' => (int) ($attendanceToday->terlambat ?? 0),
                'sakit'     => (int) ($attendanceToday->sakit ?? 0),
                'izin'      => (int) ($attendanceToday->izin ?? 0),
                'rate'      => $attendanceRate,
            ],
            'upcoming_agendas'  => $upcomingAgendas,
            'top_students'      => $topStudents,
            'running_texts'     => $runningTexts,
        ]);
    }
}