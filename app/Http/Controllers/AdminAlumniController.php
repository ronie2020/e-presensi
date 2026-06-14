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
                if ($request->activity == 'Tidak Lanjut') {
                    $q->whereIn('activity_status', ['Tidak Lanjut', 'Mencari Kerja', 'Bekerja', 'Lainnya']);
                } else {
                    $q->where('activity_status', $request->activity);
                }
            });
        }

        $alumni = $query->orderBy('name', 'asc')->paginate(20)->withQueryString();

         // Statistik Diperbarui untuk Lulusan SMP
        $stats = [
            'total'             => Student::where('status', 'graduated')->count(),            
            'lanjut_sekolah'    => AlumniProfile::whereIn('activity_status', ['SMA', 'SMK', 'MA', 'Pesantren', 'Paket C'])->count(),
            'tidak_lanjut'      => AlumniProfile::whereIn('activity_status', ['Tidak Lanjut', 'Mencari Kerja', 'Bekerja', 'Lainnya'])
                                    ->whereNotNull('student_id')->count(),
        ];

        // Kirim variabel 'years' untuk filter tahun
        $years = $graduationYears; 

        return view('admin.alumni.index', compact('alumni', 'graduationYears', 'years', 'stats'));
    }

    /**
     * Halaman Rekap Testimoni Alumni 
     */
     public function testimonials()
    {
        // Ambil data profil alumni 
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
        // Menambahkan eager loading 'classHistories.schoolClass'
        $student = Student::with(['alumniProfile', 'graduation', 'achievements', 'classHistories.schoolClass'])->findOrFail($id);
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
            'campus_entry_year' => 'nullable|integer|min:2000',
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
        // --- batas waktu eksekusi agar tidak timeout ---
        set_time_limit(300); 
        ini_set('max_execution_time', 300); 

        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();
        
        // 1. Buka file dengan fopen untuk efisiensi memori (Streaming)
        $handle = fopen($filePath, 'r');
        $firstLine = fgets($handle);
        $separator = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
        rewind($handle); // Kembalikan pointer ke awal file

        // Lewati baris pertama (Header)
        $header = fgetcsv($handle, 0, $separator);

        if (!$header) {
            fclose($handle);
            return back()->with('error', 'File CSV kosong atau format tidak terbaca.');
        }

        DB::beginTransaction();
        try {
            $count = 0;
            // 2. Baca baris demi baris menggunakan while loop
            while (($row = fgetcsv($handle, 0, $separator)) !== FALSE) {
                if (count($row) < 3) continue;

                $name = $row[0];
                $nisn = preg_replace('/[^0-9]/', '', $row[1]); 
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
                        'password' => Hash::make($nisn), 
                        'email' => null, 
                        'class_id' => null, 
                    ]
                );
                
                $count++;
            }
            fclose($handle); // Jangan lupa tutup file saat selesai

            DB::commit();
            
            if ($count == 0) {
                return back()->with('error', 'Tidak ada data yang berhasil diimport. Cek format CSV Anda.');
            }

            return redirect()->route('admin.alumni.index')->with('success', "Berhasil mengimport {$count} data alumni baru!");

        } catch (\Exception $e) {
            if (isset($handle) && is_resource($handle)) fclose($handle);
            DB::rollBack();
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}