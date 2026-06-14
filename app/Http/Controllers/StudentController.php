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
use Maatwebsite\Excel\Validators\ValidationException;

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
        $filter_status = $request->get('filter_status'); // Variabel ini sebelumnya belum didefinisikan
        // -------------------------

        $query = Student::with('schoolClass')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
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
            'student_id' => ['required', 'string', 'max:255', Rule::unique('students', 'student_id')],
            'name' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'rfid_id' => ['nullable', 'string', 'max:255', Rule::unique('students', 'rfid_id')->whereNotNull('rfid_id')],
            'parent_wa_number' => 'nullable|string|max:20',
            // Validasi Foto
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            // Gender wajib diisi (dari form quick register)
            'gender' => 'required|in:L,P',
        ];

        // Validasi field tambahan jika ada (nullable agar tidak error di quick register)
        $request->validate($rules);

        // 2. Ambil semua data input kecuali token, method, dan photo
        $data = $request->except(['_token', '_method', 'photo']);

        // 3. FIX: Generate Default Password (NISN)
        // Agar siswa manual bisa login nantinya jika sistem password diaktifkan
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

    public function show(Student $student)
    {
        return view('students.show', [
            'student' => $student
        ]);
    }

    /**
     * Menampilkan Form Edit (Buku Induk).
     */
    public function edit(Student $student)
    {
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
        // 1. Validasi data
        $rules = [
            'student_id' => ['required', Rule::unique('students', 'student_id')->ignore($student->id)],
            'name' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'rfid_id' => ['nullable', Rule::unique('students', 'rfid_id')->ignore($student->id)->whereNotNull('rfid_id')],
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
        
        $request->validate($rules);

        // Ambil data input
        $data = $request->except(['_token', '_method', 'photo', 'password']); // Jangan update password sembarangan

        // 2. Proses Upload Foto
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)) {
                Storage::disk('public')->delete($student->photo_path);
            }
            // Simpan foto baru
            $path = $request->file('photo')->store('students', 'public');
            $data['photo_path'] = $path;
        }

        // 3. Update Database
        $student->update($data);
        
        return redirect()->route('students.index', request()->query())->with('success', 'Data Buku Induk siswa berhasil diperbarui.');
    }

    /**
     * Menghapus data siswa.
     */
    public function destroy(Student $student)
    {
        // Hapus foto jika ada (opsional, tergantung kebijakan soft delete)
        if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)) {
           Storage::disk('public')->delete($student->photo_path);
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
        // Hapus foto jika ada
        $students = Student::whereIn('id', $ids)->get();
        foreach($students as $student) {
            if ($student->photo_path && \Storage::disk('public')->exists($student->photo_path)) {
                \Storage::disk('public')->delete($student->photo_path);
            }
        }
        
        Student::whereIn('id', $ids)->delete();
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
     * =========================================================
     * FUNGSI BARU: Cetak Kartu OSIS Massal berdasarkan Kelas
     * Atau Spesifik berdasarkan ID yang dipilih via Checkbox
     * =========================================================
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

        // 1. Jika URL memiliki parameter 'ids' (Cetak Terpilih via Checkbox)
        if ($request->has('ids')) {
            $ids = explode(',', $request->ids);
            $students = $query->whereIn('id', $ids)->orderBy('name', 'asc')->get();
            $className = 'Siswa Terpilih (' . count($students) . ' Orang)';
        } 
        // 2. Jika URL memiliki parameter 'class_id' (Cetak 1 Kelas Penuh)
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
}