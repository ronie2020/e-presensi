<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ScheduleSpecial;

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

        // Cek apakah hari ini ditandai sebagai hari libur di Jadwal Khusus
        $todaySpecial = ScheduleSpecial::whereDate('date', $today->toDateString())->first();
        $isHoliday = (bool) ($todaySpecial->is_holiday ?? false);
        $holidayReason = $todaySpecial->description ?? null;

        $schedules = collect();
        if (!$isHoliday) {
            $schedules = DB::table('learning_schedules')
                ->where('day_type', $todayName)
                ->orderBy('trigger_time', 'asc')
                ->get();
        }

        return response()->json([
            'server_time'    => $today->toIso8601String(),
            'day_name'       => $todayName,
            'is_holiday'     => $isHoliday,
            'holiday_reason' => $holidayReason,
            'schedules'      => $schedules
        ]);
    }
}