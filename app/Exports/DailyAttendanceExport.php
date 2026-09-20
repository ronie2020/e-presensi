<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DailyAttendanceExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $attendances;
    protected $dateLabel;
    protected $className;
    protected $startDate;
    protected $endDate;

    public function __construct($attendances, $dateLabel = '', $className = '', $startDate = '', $endDate = '')
    {
        $this->attendances = $attendances;
        $this->dateLabel   = $dateLabel;
        $this->className   = $className;
        $this->startDate   = $startDate;
        $this->endDate     = $endDate;
    }

    public function view(): View
    {
        return view('reports.excel_daily', [
            'attendances' => $this->attendances,
            'dateLabel'   => $this->dateLabel,
            'className'   => $this->className,
            'startDate'   => $this->startDate,
            'endDate'     => $this->endDate,
        ]);
    }

    public function title(): string
    {
        return 'Presensi Harian';
    }
}
