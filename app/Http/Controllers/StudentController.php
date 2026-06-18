<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

class StudentController extends Controller
{
    /**
     * Menampilkan halaman daftar siswa (CRUD).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        
        // --- PERBAIKAN DI SINI ---
        $search = $request->get('search');
        $filter_class_id = $request->get('filter_class_id');
        $filter_status = $request->get('filter_status'); 
        // -------------------------

        $query = Student::with('schoolClass')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->where(function($q) {
                // GEMBOK DEPAN: Sembunyikan siswa yang sudah lulus dari Buku Induk Aktif
                $q->where('students.status', '!=', 'graduated')
                  ->orWhereNull('students.status');
            })
            ->select('students.*');

        // Filter Wali Kelas
        if ($user->role == 'Wali Kelas') {
            $homeroomClass = $user->homeroomClass;
            if ($homeroomClass) {
                $query->where('students.class_id', $homeroomClass->id);
            } else {
                $query->where('students.class_id', -1);
            }
        }

        // Filter Pencarian
        $query->when($search, function ($q) use ($search) {
            return $q->where('students.name', 'like', '%' . $search . '%')
                     ->orWhere('students.student_id', 'like', '%' . $search . '%');
        });

        // Filter Kelas Dropdown
        $query->when($filter_class_id, function ($q) use ($filter_class_id) {
            return $q->where('students.class_id', $filter_class_id);
        });
        
        // Filter Status Kelengkapan Data
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
     */
    public function store(Request $request)
    {
        // 1. Validasi Data Lengkap
        $rules = [
            // Cek Unik namun abaikan data yang sudah dihapus (whereNull deleted_at)
            'student_id' => ['required', 'string', 'max:255', Rule::unique('students', 'student_id')->whereNull('deleted_at')],
            'name' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            // Cek Unik RFID abaikan yang sudah dihapus
            'rfid_id' => ['nullable', 'string', 'max:255', Rule::unique('students', 'rfid_id')->whereNotNull('rfid_id')->whereNull('deleted_at')],
            'parent_wa_number' => 'nullable|string|max:20',
            // Validasi Foto
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            // Gender wajib diisi (dari form quick register)
            'gender' => 'required|in:L,P',
        ];

        // Custom Error Message
        $messages = [
            'student_id.unique' => 'NIS/NISN ini sudah terdaftar pada siswa aktif (belum dihapus).',
            'rfid_id.unique' => 'Kartu RFID ini sudah digunakan oleh siswa lain.',
            'photo.max' => 'Ukuran foto terlalu besar, maksimal 2MB.',
        ];

        // Validasi field tambahan jika ada (nullable agar tidak error di quick register)
        $request->validate($rules, $messages);

        // 2. Ambil semua data input kecuali token, method, dan photo
        $data = $request->except(['_token', '_method', 'photo']);

        // 3. FIX: Generate Default Password (NISN)
        $data['password'] = Hash::make($request->student_id);

        // 4. Proses Upload Foto (Jika Ada)
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('students', 'public');
            $data['photo_path'] = $path;
        }

        // 5. Simpan ke Database
        Student::create($data);

        return redirect()->route('students.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan profil siswa beserta filter nilai yang dinamis.
     */
    public function show(Request $request, Student $student)
    {
        // 1. Ambil daftar semua tahun ajaran untuk Dropdown (secara dinamis dari database)
        $years = AcademicYear::select('name')->distinct()->orderBy('name', 'desc')->get();
        $activeYear = AcademicYear::where('is_active', true)->first();

        // 2. Tangkap filter dari URL (Jika tidak ada, gunakan tahun aktif saat ini)
        $selectedYear = $request->query('academic_year', $activeYear ? $activeYear->name : '2024/2025');
        
        // 3. Konversi Ganjil/Genap ke angka 1 atau 2 untuk default
        $defaultSemester = 1;
        if ($activeYear && $activeYear->semester == 'Genap') {
            $defaultSemester = 2;
        }
        $selectedSemester = $request->query('semester', $defaultSemester);

        // 4. Ambil nilai (Grade Record) beserta item mapelnya berdasarkan filter
        $academic_record = GradeRecord::with('items.subject')
                            ->where('student_id', $student->id)
                            ->where('academic_year', $selectedYear)
                            ->where('semester', $selectedSemester)
                            ->first();

        return view('students.show', [
            'student' => $student,
            'academic_record' => $academic_record,
            'years' => $years,
            'selectedYear' => $selectedYear,
            'selectedSemester' => $selectedSemester,
        ]);
    }

    /**
     * Menampilkan Form Edit (Buku Induk).
     */
    public function edit(Student $student)
    {
        // Gembok Belakang: Blokir jika sudah Alumni, KECUALI untuk Admin dan TU
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
     */
    public function update(Request $request, Student $student)
    {
        // Gembok Belakang: Blokir jika sudah Alumni
        if ($student->status === 'graduated' && !Auth::user()->hasAnyRole(['Admin', 'TU'])) {
            return redirect()->route('students.index')->with('error', 'Akses ditolak! Arsip alumni tidak dapat diedit melalui jalur ini.');
        }

       // 1. Validasi data
        $rules = [
            'student_id' => ['required', Rule::unique('students', 'student_id')->ignore($student->id)->whereNull('deleted_at')],
            'name' => 'required|string|max:255',
            'class_id' => $student->status === 'graduated' ? 'nullable|integer|exists:classes,id' : 'required|integer|exists:classes,id',
            'rfid_id' => ['nullable', Rule::unique('students', 'rfid_id')->ignore($student->id)->whereNotNull('rfid_id')->whereNull('deleted_at')],
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        $messages = [
            'student_id.unique' => 'NIS/NISN ini sudah terdaftar pada siswa aktif.',
            'rfid_id.unique' => 'Kartu RFID ini sudah digunakan oleh siswa lain.',
        ];
        
        $request->validate($rules, $messages);

        // Ambil data input
        $data = $request->except(['_token', '_method', 'photo', 'password']); 

        // 2. Proses Upload Foto
        if ($request->hasFile('photo')) {
            if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)) {
                Storage::disk('public')->delete($student->photo_path);
            }
            $path = $request->file('photo')->store('students', 'public');
            $data['photo_path'] = $path;
        }

       // 3. Update Database
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

        // FIX: Hapus juga akun user agar NISN/Username tidak bentrok saat didaftarkan ulang
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

            // FIX: Hapus akun user
            if ($student->user) {
                $student->user->delete();
            }

            // Hapus di dalam loop agar soft deletes model dan observer tereksekusi dengan benar
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

    /**
     * Cetak Kartu OSIS Massal
     */
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

    /**
     * Cetak Kartu OSIS Satuan (Individu)
     */
    public function card(Student $student)
    {
        return view('students.osis_card', compact('student')); 
    }

     /**
     * Mengekspor Format Daftar Hadir Siswa Kosong per Kelas
     */
    public function exportAttendance(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        $class = SchoolClass::find($request->class_id);
        
        // Membersihkan nama kelas dari karakter yang mungkin tidak didukung oleh file system
        $cleanClassName = preg_replace('/[^A-Za-z0-9\-]/', '_', $class->name);
        $fileName = 'Daftar_Hadir_Kelas_' . $cleanClassName . '.xlsx';

        return Excel::download(new AttendanceExport($class->id), $fileName);
    }
}