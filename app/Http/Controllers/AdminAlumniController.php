<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AlumniProfile;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; 

class AdminAlumniController extends Controller
{
    /**
     * Menampilkan Daftar Alumni
     */
    public function index(Request $request)
    {
        $graduationYears = Student::select(DB::raw('YEAR(graduated_date) as year'))
                            ->whereNotNull('graduated_date')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');

        $query = Student::with(['alumniProfile', 'graduation'])
            ->where(function($q) {
                $q->where('status', 'graduated')
                  ->orWhereHas('graduation', fn($g) => $g->where('status', 'LULUS'));
            });

        // Filter Pencarian
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('student_id', 'like', '%'.$request->search.'%');
            });
        }

        // Filter Tahun
        if ($request->filled('year')) {
            $query->whereYear('graduated_date', $request->year);
        }

        // Filter Aktivitas
        if ($request->filled('activity')) {
            $query->whereHas('alumniProfile', function($q) use ($request) {
                $q->where('activity_status', $request->activity);
            });
        }

        $alumni = $query->orderBy('name', 'asc')->paginate(20)->withQueryString();

        // Statistik (DIPERBAIKI)
        $stats = [
            'total' => Student::where('status', 'graduated')->count(),            
           
            'kuliah' => AlumniProfile::whereIn('activity_status', ['SMA', 'SMK', 'MA', 'Pesantren'])->count(),
            
            'bekerja' => AlumniProfile::whereIn('activity_status', ['Bekerja', 'Wirausaha'])->count(),
            
            // Menghitung yang mencari kerja atau memutuskan tidak lanjut
            'mencari' => AlumniProfile::whereIn('activity_status', ['Mencari Kerja', 'Tidak Lanjut'])->count(),
        ];

        // Kirim variabel 'years' untuk filter tahun
        $years = $graduationYears; 

        return view('admin.alumni.index', compact('alumni', 'graduationYears', 'years', 'stats'));
    }

    /**
     * Halaman Rekap Testimoni Alumni (FITUR BARU)
     */
    public function testimonials()
    {
        // Ambil data profil alumni yang testimoninya TIDAK KOSONG
        $testimonials = AlumniProfile::with('student')
            ->whereNotNull('testimony')
            ->where('testimony', '!=', '')
            ->latest('updated_at') 
            ->paginate(12);

        return view('admin.alumni.testimonials', compact('testimonials'));
    }

    /**
     * Detail Alumni
     */
    public function show($id)
    {
        $student = Student::with(['alumniProfile', 'graduation', 'achievements'])->findOrFail($id);
        return view('admin.alumni.show', compact('student'));
    }

    /**
     * Export PDF Laporan
     */
    public function exportPdf(Request $request)
    {
        $query = Student::with(['alumniProfile'])
            ->where('status', 'graduated');
            
        if ($request->filled('year')) {
            $query->whereYear('graduated_date', $request->year);
        }

        $alumni = $query->orderBy('name')->get();
        $year = $request->year ?? 'Semua Angkatan';

        $pdf = Pdf::loadView('admin.alumni.pdf_report', compact('alumni', 'year'));
        return $pdf->stream('Laporan_Alumni_'.$year.'.pdf');
    }

    /**
     * Form Edit Data Alumni (Manual Input oleh Admin)
     */
    public function edit($id)
    {
        $student = Student::with('alumniProfile')->findOrFail($id);
        return view('admin.alumni.edit', compact('student'));
    }

    /**
     * Proses Simpan Data Alumni (Oleh Admin)
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'activity_status' => 'required|string',
            'phone_number' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        AlumniProfile::updateOrCreate(
            ['student_id' => $student->id],
            [
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'activity_status' => $request->activity_status,
                'campus_name' => $request->campus_name,
                'campus_major' => $request->campus_major,
                'campus_entry_year' => $request->campus_entry_year,
                'company_name' => $request->company_name,
                'position' => $request->position,
                'testimony' => $request->testimony,
            ]
        );

        return redirect()->route('admin.alumni.index')->with('success', 'Data alumni berhasil diperbarui.');
    }

    // =========================================================================
    //  FITUR IMPORT ALUMNI (LEGACY DATA)
    // =========================================================================

    /**
     * Menampilkan Halaman Import
     */
    public function import()
    {
        return view('admin.alumni.import');
    }

    /**
     * Download Template CSV
     */
    public function downloadTemplate()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=template_import_alumni.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Nama Lengkap', 'NISN (Username)', 'Tahun Lulus', 'Jenis Kelamin (L/P)'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, ['Budi Santoso', '0012345678', '2020', 'L']);
            fputcsv($file, ['Siti Aminah', '0012345679', '2021', 'P']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Proses Import CSV Data Alumni
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();
        
        // 1. Deteksi Separator (Koma atau Titik Koma untuk Excel Indo)
        $firstLine = fgets(fopen($filePath, 'r'));
        $separator = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        // 2. Baca File CSV
        $csvData = array_map(function($line) use ($separator) {
            return str_getcsv($line, $separator);
        }, file($filePath));
        
        // Hapus Header
        $header = array_shift($csvData);

        if (count($csvData) == 0) {
            return back()->with('error', 'File CSV kosong atau format tidak terbaca.');
        }

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($csvData as $row) {
                if (count($row) < 3) continue;

                $name = $row[0];
                $nisn = preg_replace('/[^0-9]/', '', $row[1]); // Hanya angka
                $year = $row[2];
                $gender = isset($row[3]) ? strtoupper(trim($row[3])) : 'L';

                if (empty($nisn)) continue;

                // Simpan Data
                Student::updateOrCreate(
                    ['student_id' => $nisn], 
                    [
                        'nis' => $nisn,      
                        'student_id' => $nisn, 
                        'name' => $name,
                        'gender' => $gender,
                        'status' => 'graduated',
                        'graduated_date' => $year . '-05-20',
                        'graduation_year' => $year,
                        'password' => Hash::make($nisn), // Password Default = NISN
                        'email' => null, 
                        'class_id' => null, 
                    ]
                );
                
                $count++;
            }

            DB::commit();
            
            if ($count == 0) {
                return back()->with('error', 'Tidak ada data yang berhasil diimport. Cek format CSV Anda.');
            }

            return redirect()->route('admin.alumni.index')->with('success', "Berhasil mengimport {$count} data alumni baru!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}