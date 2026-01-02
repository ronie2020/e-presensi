<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Graduation;
use App\Models\SchoolClass;
use App\Models\AlumniProfile; // Pastikan import model ini
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GraduationController extends Controller
{
    // --- HALAMAN SISWA (Cek Kelulusan & SKL) ---
    public function index()
    {
        $firstGraduation = Graduation::whereNotNull('announcement_date')->orderBy('announcement_date', 'asc')->first();
        $announcementDate = ($firstGraduation && $firstGraduation->announcement_date) ? Carbon::parse($firstGraduation->announcement_date) : Carbon::now()->addYear();
        return view('graduation.index', compact('announcementDate'));
    }

    public function check(Request $request)
    {
        $request->validate(['nisn' => 'required|numeric']);
        $student = Student::where('student_id', $request->nisn)->with(['graduation', 'schoolClass'])->first();

        if (!$student) return back()->with('error', 'NISN tidak ditemukan.');
        if ($student->schoolClass && !str_starts_with($student->schoolClass->name, '9')) return back()->with('error', 'Siswa bukan tingkat akhir (Kelas 9).');
        if (!$student->graduation) return back()->with('error', 'Data kelulusan belum dirilis.');
        if ($student->graduation->announcement_date && now() < $student->graduation->announcement_date) return back()->with('error', 'Pengumuman belum dibuka.');

        $announcementDate = $student->graduation->announcement_date ? Carbon::parse($student->graduation->announcement_date) : Carbon::now()->addYear();
        return view('graduation.index', compact('student', 'announcementDate'));
    }

    public function printSkl($id)
    {
        $student = Student::with('graduation')->findOrFail($id);
        if ($student->graduation->status !== 'LULUS') return back()->with('error', 'SKL hanya untuk siswa LULUS.');
        $pdf = Pdf::loadView('graduation.pdf_skl', compact('student'))->setPaper('a4', 'portrait');
        return $pdf->stream('SKL_' . $student->student_id . '.pdf');
    }

    // --- HALAMAN ADMIN (Manajemen) ---

    public function adminIndex(Request $request)
    {
        $classes = SchoolClass::where('name', 'LIKE', '9%')->orderBy('name')->get();
        
        $query = Student::with(['schoolClass', 'graduation'])
            ->whereHas('schoolClass', fn($q) => $q->where('name', 'LIKE', '9%'))
            ->orderBy('name');

        if ($request->filled('class_id')) $query->where('class_id', $request->class_id);
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')->orWhere('student_id', 'like', '%'.$request->search.'%');
            });
        }

        $students = $query->paginate(20)->withQueryString();
        return view('admin.graduation.index', compact('students', 'classes'));
    }
    
    public function store(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'status' => 'required|in:LULUS,TIDAK LULUS,DITUNDA',
            'average_score' => 'nullable|numeric',
            'skl_number' => 'nullable|string',
            'announcement_date' => 'nullable'
        ]);

        // Parsing tanggal agar formatnya benar
        $announcementDate = null;
        if (!empty($data['announcement_date'])) {
            try {
                $announcementDate = Carbon::parse($data['announcement_date'])->format('Y-m-d H:i:s');
            } catch (\Exception $e) { /* ignore */ }
        }

        Graduation::updateOrCreate(
            ['student_id' => $data['student_id']],
            [
                'status' => $data['status'],
                'average_score' => $data['average_score'],
                'skl_number' => $data['skl_number'],
                'academic_year' => date('Y') . '/' . (date('Y') + 1),
                'announcement_date' => $announcementDate
            ]
        );
        
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Data berhasil disimpan!', 'success' => true]);
        }

        return back()->with('success', 'Data kelulusan siswa berhasil disimpan.');
    }

    // --- IMPORT CSV ---
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt|max:2048']);
        $file = $request->file('file');
        $handle = fopen($file->getPathname(), "r");
        fgetcsv($handle); 

        $count = 0;
        $defaultDate = Carbon::parse('2025-05-05 10:00:00')->format('Y-m-d H:i:s'); 

        DB::transaction(function () use ($handle, &$count, $defaultDate) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($data) < 2) continue;
                $nisn = trim($data[0]);
                $status = strtoupper(trim($data[1]));
                $nilai = isset($data[2]) ? floatval(str_replace(',', '.', $data[2])) : 0;
                
                $student = Student::where('student_id', $nisn)->first();
                if ($student) {
                    Graduation::updateOrCreate(
                        ['student_id' => $student->id],
                        [
                            'status' => in_array($status, ['LULUS', 'TIDAK LULUS']) ? $status : 'DITUNDA',
                            'average_score' => $nilai,
                            'academic_year' => date('Y') . '/' . (date('Y') + 1),
                            'announcement_date' => $student->graduation->announcement_date ?? $defaultDate
                        ]
                    );
                    $count++;
                }
            }
        });
        fclose($handle);
        return back()->with('success', "Import selesai. $count data diperbarui.");
    }
    
    // --- AUTO GENERATE ---
    public function autoGenerate(Request $request) { 
       return back()->with('success', 'Fitur Auto Generate dipanggil');
    }
    public function downloadTemplate() { /* ... */ }
    
    // --- BULK UPDATE ---
    public function bulkUpdate(Request $request) {
        $data = $request->input('students');
        if ($data && is_array($data)) {
            DB::transaction(function () use ($data) {
                foreach ($data as $studentId => $fields) {
                    if(!isset($fields['status'])) continue;
                    $date = !empty($fields['announcement_date']) ? Carbon::parse($fields['announcement_date'])->format('Y-m-d H:i:s') : null;
                    Graduation::updateOrCreate(['student_id' => $studentId], [
                        'status' => $fields['status'],
                        'average_score' => $fields['average_score'] ?? 0,
                        'skl_number' => $fields['skl_number'] ?? null,
                        'announcement_date' => $date,
                        'academic_year' => date('Y') . '/' . (date('Y') + 1),
                    ]);
                }
            });
            return back()->with('success', 'Semua data berhasil diperbarui.');
        }
        return back()->with('error', 'Tidak ada data.');
    }

    public function setGlobalDate(Request $request) {
        $request->validate(['global_date' => 'required|date']);
        $date = Carbon::parse($request->global_date)->format('Y-m-d H:i:s');
        $query = Student::whereHas('schoolClass', fn($q) => $q->where('name', 'LIKE', '9%'));
        if($request->class_filter) $query->where('class_id', $request->class_filter);
        $students = $query->get();
        foreach($students as $student) {
            Graduation::updateOrCreate(['student_id' => $student->id], ['announcement_date' => $date, 'academic_year' => date('Y').'/'.(date('Y')+1)]);
        }
        return back()->with('success', 'Jadwal berhasil diupdate.');
    }

    // ===> PINDAHKAN SISWA LULUS KE ALUMNI <===
    public function processAlumni(Request $request)
    {
        // 1. Cari siswa yang status kelulusannya "LULUS" dan masih "active"
        $students = Student::whereHas('graduation', function($q) {
                $q->where('status', 'LULUS');
            })
            ->where('status', '!=', 'graduated') 
            ->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'Tidak ada siswa berstatus LULUS yang belum diproses.');
        }

        $count = 0;
        DB::transaction(function () use ($students, &$count) {
            foreach ($students as $student) {
                // Update status siswa ALUMNI
                $student->update([
                    'status' => 'graduated',
                    'class_id' => null,
                    'graduated_date' => $student->graduation->announcement_date ?? now(),
                ]);

                if (!AlumniProfile::where('student_id', $student->id)->exists()) {
                    AlumniProfile::create(['student_id' => $student->id]);
                }

                $count++;
            }
        });

        return back()->with('success', "Berhasil memindahkan $count siswa ke Database Alumni.");
    }
}