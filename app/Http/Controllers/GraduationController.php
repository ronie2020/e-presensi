<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Graduation;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class GraduationController extends Controller
{
    // --- HALAMAN PUBLIK (Cek Kelulusan) ---

    public function index()
    {
        return view('graduation.index');
    }

    public function check(Request $request)
    {
        $request->validate(['nisn' => 'required|numeric']);
        
        $student = Student::where('student_id', $request->nisn)->with(['graduation', 'schoolClass'])->first();

        if (!$student) return back()->with('error', 'NISN tidak ditemukan.');
        
        // Validasi tambahan: Pastikan siswa ini memang Kelas 9 (Opsional, tapi aman)
        // Asumsi nama kelas dimulai dengan angka '9'
        if ($student->schoolClass && !str_starts_with($student->schoolClass->name, '9')) {
             return back()->with('error', 'Siswa bukan tingkat akhir (Kelas 9).');
        }

        if (!$student->graduation) {
            return back()->with('error', 'Data kelulusan untuk siswa ini belum dirilis.');
        }
        
        if ($student->graduation->announcement_date && now() < $student->graduation->announcement_date) {
            return back()->with('error', 'Pengumuman belum dibuka. Silakan cek kembali nanti.');
        }

        return view('graduation.index', compact('student'));
    }

    public function printSkl($id)
    {
        $student = Student::with('graduation')->findOrFail($id);

        if ($student->graduation->status !== 'LULUS') {
            return back()->with('error', 'SKL hanya dapat dicetak bagi siswa yang LULUS.');
        }

        $pdf = Pdf::loadView('graduation.pdf_skl', compact('student'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('SKL_' . $student->student_id . '.pdf');
    }

    // --- HALAMAN ADMIN (Manajemen) ---

    public function adminIndex(Request $request)
    {
        // 1. FILTER DROPDOWN: Hanya ambil kelas yang namanya diawali "9"
        $classes = SchoolClass::where('name', 'LIKE', '9%')
                    ->orderBy('name')
                    ->get();
        
        // 2. QUERY SISWA: Default hanya ambil siswa yang kelasnya diawali "9"
        $query = Student::with(['schoolClass', 'graduation'])
            ->whereHas('schoolClass', function($q) {
                $q->where('name', 'LIKE', '9%');
            })
            ->orderBy('name');

        // Filter spesifik (misal: hanya 9A)
        if ($request->has('class_id') && $request->class_id != '') {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->paginate(20)->withQueryString();

        return view('admin.graduation.index', compact('students', 'classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'status' => 'required|in:LULUS,TIDAK LULUS,DITUNDA',
            'average_score' => 'nullable|numeric',
            'skl_number' => 'nullable|string',
            'announcement_date' => 'nullable|date'
        ]);

        Graduation::updateOrCreate(
            ['student_id' => $data['student_id']],
            [
                'status' => $data['status'],
                'average_score' => $data['average_score'],
                'skl_number' => $data['skl_number'],
                'academic_year' => date('Y') . '/' . (date('Y') + 1),
                'announcement_date' => $data['announcement_date']
            ]
        );

        return back()->with('success', 'Data kelulusan siswa berhasil disimpan.');
    }

    public function setGlobalDate(Request $request)
    {
        $request->validate([
            'global_date' => 'required|date',
            'class_filter' => 'nullable|exists:school_classes,id'
        ]);

        // Mulai query siswa
        $query = Student::query();

        // PENTING: Batasi update massal HANYA untuk kelas 9
        // Agar kelas 7 & 8 tidak sengaja ter-set lulus
        $query->whereHas('schoolClass', function($q) {
            $q->where('name', 'LIKE', '9%');
        });

        if($request->class_filter) {
            $query->where('class_id', $request->class_filter);
        }
        
        $students = $query->get();
        
        if ($students->isEmpty()) {
            return back()->with('error', 'Tidak ada siswa kelas 9 yang ditemukan untuk diupdate.');
        }

        DB::transaction(function () use ($students, $request) {
            foreach($students as $student) {
                Graduation::updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'announcement_date' => $request->global_date,
                        'status' => $student->graduation->status ?? 'LULUS', 
                        'academic_year' => date('Y') . '/' . (date('Y') + 1)
                    ]
                );
            }
        });

        return back()->with('success', 'Waktu pengumuman berhasil diupdate untuk ' . $students->count() . ' siswa Kelas 9.');
    }
}