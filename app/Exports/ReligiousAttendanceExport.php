<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReligiousAttendanceExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $attendances;
    protected $activity;
    protected $dateLabel;
    protected $className;
    protected $date;

    public function __construct($attendances, $activity = 'Dhuha', $dateLabel = '', $className = '', $date = '')
    {
        $this->attendances = $attendances;
        $this->activity    = $activity;
        $this->dateLabel   = $dateLabel;
        $this->className   = $className;
        $this->date        = $date;
    }

    public function view(): View
    {
        return view('reports.excel_religious', [
            'attendances' => $this->attendances,
            'activity'    => $this->activity,
            'dateLabel'   => $this->dateLabel,
            'className'   => $this->className,
            'date'        => $this->date,
        ]);
    }

    public function title(): string
    {
        return 'Ibadah ' . $this->activity;
    }
}
