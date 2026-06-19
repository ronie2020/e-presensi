<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Models\AcademicYear;
use App\Models\GradeRecord;

// IMPORT FORM REQUEST YANG BARU DIBUAT
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;

class StudentController extends Controller
{
    /**
     * Menampilkan halaman daftar siswa (CRUD).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        
        $search = $request->get('search');
        $filter_class_id = $request->get('filter_class_id');
        $filter_status = $request->get('filter_status'); 

        $query = Student::with('schoolClass')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->where(function($q) {
                $q->where('students.status', '!=', 'graduated')
                  ->orWhereNull('students.status');
            })
            ->select('students.*');

        if ($user->role == 'Wali Kelas') {
            $homeroomClass = $user->homeroomClass;
            if ($homeroomClass) {
                $query->where('students.class_id', $homeroomClass->id);
            } else {
                $query->where('students.class_id', -1);
            }
        }

        $query->when($search, function ($q) use ($search) {
            return $q->where('students.name', 'like', '%' . $search . '%')
                     ->orWhere('students.student_id', 'like', '%' . $search . '%');
        });

        $query->when($filter_class_id, function ($q) use ($filter_class_id) {
            return $q->where('students.class_id', $filter_class_id);
        });
        
        $query->when($filter_status, function ($q) use ($filter_status) {
            if ($filter_status == 'lengkap') {
                return $q->whereNotNull('pob')
                         ->whereNotNull('dob')
                         ->whereNotNull('address')
                         ->whereNotNull('father_name');
            } elseif ($filter_status == 'belum_lengkap') {
                return $q->where(function($query) {
                    $query->whereNull('pob')
                          ->orWhereNull('dob')
                          ->orWhereNull('address')
                          ->orWhereNull('father_name');
                });
            }
        });

        $students = $query->orderBy('classes.name', 'asc') 
                          ->orderBy('students.name', 'asc')
                          ->paginate(10)
                          ->appends(request()->query());

        return view('students.index', [
            'classes' => $classes,
            'students' => $students
        ]);
    }

    /**
     * Menampilkan Form Tambah Siswa.
     */
    public function create()
    {
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        return view('students.create', [
            'classes' => $classes
        ]);
    }

    /**
     * Menyimpan data siswa baru (Quick Register & Full).
     * PERUBAHAN: Menggunakan StoreStudentRequest
     */
    public function store(StoreStudentRequest $request)
    {
        // AMAN! $data hanya berisi field yang sudah lolos validasi di StoreStudentRequest.
        // Data 'siluman' akan otomatis dibuang.
        $data = $request->validated();

        // Generate Default Password (NISN)
        $data['password'] = Hash::make($request->student_id);

        // Proses Upload Foto (Jika Ada)
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('students', 'public');
            $data['photo_path'] = $path;
        }

        Student::create($data);

        return redirect()->route('students.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan profil siswa beserta filter nilai yang dinamis.
     */
    public function show(Request $request, Student $student)
    {
        $years = AcademicYear::select('name')->distinct()->orderBy('name', 'desc')->get();
        $activeYear = AcademicYear::where('is_active', true)->first();

        $selectedYear = $request->query('academic_year', $activeYear ? $activeYear->name : '2024/2025');
        
        $defaultSemester = 1;
        if ($activeYear && $activeYear->semester == 'Genap') {
            $defaultSemester = 2;
        }
        $selectedSemester = $request->query('semester', $defaultSemester);

        $academic_record = GradeRecord::with('items.subject')
                            ->where('student_id', $student->id)
                            ->where('academic_year', $selectedYear)
                            ->where('semester', $selectedSemester)
                            ->first();

        // --- PINDAHAN LOGIKA DARI VIEW (Separation of Concerns) ---
        // 1. Kalkulasi Tahun Ajaran untuk Raport
        $activeYearStr = $activeYear->name ?? '2024/2025';
        $activeStartYear = (int) substr($activeYearStr, 0, 4);

        $ta7 = ''; $ta8 = ''; $ta9 = '';
        if ($student->status === 'graduated' || !empty($student->graduated_date)) {
            $gradYear = !empty($student->graduated_date) 
                ? (int) \Carbon\Carbon::parse($student->graduated_date)->format('Y') 
                : $activeStartYear;
            
            $ta9 = ($gradYear - 1) . '/' . $gradYear;
            $ta8 = ($gradYear - 2) . '/' . ($gradYear - 1);
            $ta7 = ($gradYear - 3) . '/' . ($gradYear - 2);
        } else {
            $level = 7;
            $className = $student->schoolClass->name ?? '';
            if (preg_match('/^VIII|^8/i', $className)) $level = 8;
            if (preg_match('/^IX|^9/i', $className)) $level = 9;

            $ta7 = ($activeStartYear - ($level - 7)) . '/' . ($activeStartYear - ($level - 7) + 1);
            $ta8 = ($activeStartYear - ($level - 8)) . '/' . ($activeStartYear - ($level - 8) + 1);
            $ta9 = ($activeStartYear - ($level - 9)) . '/' . ($activeStartYear - ($level - 9) + 1);
        }

        // 2. Pemetaan Nilai Berdasarkan Mapel, Tahun, dan Semester
        $mappedScores = [];
        $allGrades = GradeRecord::with('items.subject')->where('student_id', $student->id)->get();
        foreach($allGrades as $rec) {
            foreach($rec->items as $item) {
                if ($item->subject) {
                    $subjName = strtolower(trim($item->subject->name));
                    $mappedScores[$rec->academic_year][$rec->semester][$subjName] = $item->score;
                }
            }
        }

        // 3. Menghitung Rata-rata dan Menyusun Baris Raport
        $mapelInduk = \App\Models\Subject::orderBy('order')->get();
        $raportRows = [];
        $totals = ['71' => 0, '72' => 0, '81' => 0, '82' => 0, '91' => 0, '92' => 0];
        $counts = ['71' => 0, '72' => 0, '81' => 0, '82' => 0, '91' => 0, '92' => 0];

        foreach($mapelInduk as $mapel) {
            $mName = strtolower(trim($mapel->name));
            $v71 = $mappedScores[$ta7][1][$mName] ?? '-';
            $v72 = $mappedScores[$ta7][2][$mName] ?? '-';
            $v81 = $mappedScores[$ta8][1][$mName] ?? '-';
            $v82 = $mappedScores[$ta8][2][$mName] ?? '-';
            $v91 = $mappedScores[$ta9][1][$mName] ?? '-';
            $v92 = $mappedScores[$ta9][2][$mName] ?? '-';

            if(is_numeric($v71)) { $totals['71'] += (float)$v71; $counts['71']++; }
            if(is_numeric($v72)) { $totals['72'] += (float)$v72; $counts['72']++; }
            if(is_numeric($v81)) { $totals['81'] += (float)$v81; $counts['81']++; }
            if(is_numeric($v82)) { $totals['82'] += (float)$v82; $counts['82']++; }
            if(is_numeric($v91)) { $totals['91'] += (float)$v91; $counts['91']++; }
            if(is_numeric($v92)) { $totals['92'] += (float)$v92; $counts['92']++; }

            $raportRows[] = [
                'name' => $mapel->name,
                '71' => $v71, '72' => $v72,
                '81' => $v81, '82' => $v82,
                '91' => $v91, '92' => $v92,
            ];
        }

        $averages = [
            '71' => $counts['71'] > 0 ? round($totals['71'] / $counts['71'], 1) : '-',
            '72' => $counts['72'] > 0 ? round($totals['72'] / $counts['72'], 1) : '-',
            '81' => $counts['81'] > 0 ? round($totals['81'] / $counts['81'], 1) : '-',
            '82' => $counts['82'] > 0 ? round($totals['82'] / $counts['82'], 1) : '-',
            '91' => $counts['91'] > 0 ? round($totals['91'] / $counts['91'], 1) : '-',
            '92' => $counts['92'] > 0 ? round($totals['92'] / $counts['92'], 1) : '-',
        ];

        // 4. Perhitungan Akumulasi Kehadiran
        $s_sakit = \App\Models\AttendanceSiswa::where('student_id', $student->id)
                    ->where('status', 'Sakit')
                    ->where(function($q) {
                        $q->whereIn('type', ['Harian', 'Masuk', 'Pulang'])->orWhereNull('type');
                    })->count();
                    
        $s_izin  = \App\Models\AttendanceSiswa::where('student_id', $student->id)
                    ->where('status', 'Izin')
                    ->where(function($q) {
                        $q->whereIn('type', ['Harian', 'Masuk', 'Pulang'])->orWhereNull('type');
                    })->count();
                    
        $s_alfa  = \App\Models\AttendanceSiswa::where('student_id', $student->id)
                    ->whereIn('status', ['Alfa', 'Alpa', 'Alpha'])
                    ->where(function($q) {
                        $q->whereIn('type', ['Harian', 'Masuk', 'Pulang'])->orWhereNull('type');
                    })->count();
                    
        $attendanceStats = ['sakit' => $s_sakit, 'izin' => $s_izin, 'alfa' => $s_alfa];
        // --- SELESAI PINDAHAN LOGIKA ---

        return view('students.show', compact(
            'student', 
            'academic_record', 
            'years', 
            'selectedYear', 
            'selectedSemester',
            'raportRows',
            'averages',
            'attendanceStats'
        ));
    }

    /**
     * Menampilkan Form Edit (Buku Induk).
     */
    public function edit(Student $student)
    {
        if ($student->status === 'graduated' && !Auth::user()->hasAnyRole(['Admin', 'TU'])) {
            return redirect()->route('students.index')->with('error', 'Akses ditolak! Arsip alumni dikunci dan hanya dapat diperbarui oleh Admin TU.');
        }

        $classes = SchoolClass::orderBy('name', 'asc')->get();
        return view('students.edit', [
            'student' => $student,
            'classes' => $classes
        ]);
    }
    
    /**
     * Update data siswa (Buku Induk + Foto).
     * PERUBAHAN: Menggunakan UpdateStudentRequest
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        if ($student->status === 'graduated' && !Auth::user()->hasAnyRole(['Admin', 'TU'])) {
            return redirect()->route('students.index')->with('error', 'Akses ditolak! Arsip alumni tidak dapat diedit melalui jalur ini.');
        }

        // AMAN! Hanya mengambil field yang terdaftar di UpdateStudentRequest
        $data = $request->validated(); 

        // Proses Upload Foto
        if ($request->hasFile('photo')) {
            if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)) {
                Storage::disk('public')->delete($student->photo_path);
            }
            $path = $request->file('photo')->store('students', 'public');
            $data['photo_path'] = $path;
        }

        // Update Database
        $student->update($data);
        
        if ($student->status === 'graduated') {
            return redirect()->route('admin.alumni.index')->with('success', 'Data Buku Induk alumni berhasil diperbarui.');
        }

        return redirect()->route('students.index', request()->query())->with('success', 'Data Buku Induk siswa berhasil diperbarui.');
    }

    /**
     * Menghapus data siswa.
     */
    public function destroy(Student $student)
    {
        if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)) {
           Storage::disk('public')->delete($student->photo_path);
        }

        if ($student->user) {
            $student->user->delete();
        }

        $student->delete();
        return redirect()->route('students.index')->with('success', 'Siswa berhasil dihapus.');
    }

    /**
     * Menghapus data siswa secara massal.
     */
    public function destroyBatch(Request $request)
    {
        $ids = explode(',', $request->ids);
        $students = Student::whereIn('id', $ids)->get();

        foreach($students as $student) {
            if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)) {
                Storage::disk('public')->delete($student->photo_path);
            }

            if ($student->user) {
                $student->user->delete();
            }

            $student->delete();
        }
        
        return back()->with('success', count($ids) . ' Data siswa berhasil dihapus.');
    }

    /**
     * Import data dari Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        try {
            Excel::import(new StudentsImport, $request->file('file'));
            return redirect()->route('students.index')->with('success', 'Data siswa berhasil diimpor.');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return redirect()->route('students.index')->with('error', 'Gagal impor: ' . implode(' | ', $errorMessages));
        } catch (\Exception $e) {
            return redirect()->route('students.index')->with('error', 'Kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export data ke Excel.
     */
    public function export()
    {
        return Excel::download(new StudentsExport, 'data_siswa_buku_induk.xlsx');
    }

    public function printBatch(Request $request)
    {
        $request->validate([
            'class_id' => 'required_without:ids|exists:classes,id',
            'ids' => 'required_without:class_id|string'
        ]);

        $query = Student::with('schoolClass')
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')
                  ->orWhereNull('status');
            });

        if ($request->has('ids')) {
            $ids = explode(',', $request->ids);
            $students = $query->whereIn('id', $ids)->orderBy('name', 'asc')->get();
            $className = 'Siswa Terpilih (' . count($students) . ' Orang)';
        } 
        else {
            $class = SchoolClass::find($request->class_id);
            $students = $query->where('class_id', $request->class_id)->orderBy('name', 'asc')->get();
            $className = $class ? $class->name : 'Semua Kelas';
        }

        if ($students->isEmpty()) {
            return back()->with('error', 'Tidak ada siswa aktif yang dipilih untuk dicetak.');
        }

        return view('students.osis_card_batch', [
            'students' => $students,
            'className' => $className
        ]);
    }

    public function card(Student $student)
    {
        return view('students.osis_card', compact('student')); 
    }

    public function exportAttendance(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        $class = SchoolClass::find($request->class_id);
        
        $cleanClassName = preg_replace('/[^A-Za-z0-9\-]/', '_', $class->name);
        $fileName = 'Daftar_Hadir_Kelas_' . $cleanClassName . '.xlsx';

        return Excel::download(new AttendanceExport($class->id), $fileName);
    }
}