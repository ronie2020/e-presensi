<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Timeslot;
use App\Models\Timetable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TeacherTimetableExport implements FromView, ShouldAutoSize
{
    protected $teacher_id;

    public function __construct($teacher_id)
    {
        $this->teacher_id = $teacher_id;
    }

    public function view(): View
    {
        $teacher = User::findOrFail($this->teacher_id);
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $timeslots = Timeslot::orderBy('order_sequence')->get();

        $allSchedules = Timetable::with(['timeslot', 'studentClass', 'subject'])
            ->where('teacher_id', $this->teacher_id)
            ->get();

        $timetables = [];
        foreach ($allSchedules as $schedule) {
            $timetables[$schedule->day_of_week][$schedule->timeslot_id] = $schedule;
        }

        return view('timetable.export_teacher', [
            'teacher' => $teacher,
            'days' => $days,
            'timeslots' => $timeslots,
            'timetables' => $timetables
        ]);
    }
}