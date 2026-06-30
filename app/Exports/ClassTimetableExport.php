<?php

namespace App\Exports;

use App\Models\SchoolClass;
use App\Models\Timeslot;
use App\Models\Timetable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClassTimetableExport implements FromView, ShouldAutoSize
{
    protected $class_id;

    public function __construct($class_id)
    {
        $this->class_id = $class_id;
    }

    public function view(): View
    {
        $class = SchoolClass::findOrFail($this->class_id);
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $timeslots = Timeslot::orderBy('order_sequence')->get();

        $allSchedules = Timetable::with(['timeslot', 'teacher', 'subject'])
            ->where('class_id', $this->class_id)
            ->get();

        $timetables = [];
        foreach ($allSchedules as $schedule) {
            $timetables[$schedule->day_of_week][$schedule->timeslot_id] = $schedule;
        }

        return view('timetable.export_class', [
            'class' => $class,
            'days' => $days,
            'timeslots' => $timeslots,
            'timetables' => $timetables
        ]);
    }
}