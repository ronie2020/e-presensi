<?php

namespace App\Exports;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AttendanceExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $class_id;

    public function __construct($class_id)
    {
        $this->class_id = $class_id;
    }

    public function view(): View
    {
        $class = SchoolClass::findOrFail($this->class_id);
        
        // Ambil data siswa aktif berdasarkan kelas, urutkan nama A-Z
        $students = Student::where('class_id', $this->class_id)
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')->orWhereNull('status');
            })
            ->orderBy('name', 'asc')
            ->get();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $tahunPelajaran = $activeYear ? $activeYear->name : date('Y') . '/' . (date('Y') + 1);

        return view('students.attendance_export', [
            'class' => $class,
            'students' => $students,
            'tahunPelajaran' => $tahunPelajaran
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                // Ada 4 kolom identitas + 31 kolom tanggal + 3 kolom rekap = 38 kolom. 
                // Kolom ke-38 dalam huruf Excel adalah 'AL'
                $highestCol = 'AL'; 

                // 1. Set Border Hitam untuk seluruh area tabel data (Mulai dari baris 6 yaitu Header Tabel)
                $sheet->getStyle('A6:' . $highestCol . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ]
                ]);

                // 2. Kecilkan lebar kolom tanggal (E = Tgl 1, sampai AI = Tgl 31) agar jadi kotak kecil ceklis
                // Menggunakan iterasi string char di PHP
                for ($col = 'E'; $col !== 'AJ'; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(3.5);
                }
                
                // 3. Kolom Rekap (S = AJ, I = AK, A = AL) beri ukuran sedikit lebih besar
                $sheet->getColumnDimension('AJ')->setWidth(4.5); 
                $sheet->getColumnDimension('AK')->setWidth(4.5); 
                $sheet->getColumnDimension('AL')->setWidth(4.5); 
            },
        ];
    }
}