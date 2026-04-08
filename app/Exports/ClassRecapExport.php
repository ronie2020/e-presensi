<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClassRecapExport implements FromView, ShouldAutoSize
{
    protected $reportData;
    protected $startDate;
    protected $endDate;

    // Menerima data dari Controller
    public function __construct($reportData, $startDate, $endDate)
    {
        $this->reportData = $reportData;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        // Menggunakan view/blade yang sudah ada
        return view('reports.excel_class_recap', [
            'reportData' => $this->reportData,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
        ]);
    }
}