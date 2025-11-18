<?php

namespace App\Http\Controllers;

// Impor Model yang kita butuhkan
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Validators\ValidationException;

class StudentController extends Controller
{
    /**
     * Menampilkan halaman daftar siswa (CRUD).
     * Ini adalah fungsi 'index'
     */
    public function index(Request $request) // <-- MEMBUTUHKAN Request $request
    {
        $user = Auth::user(); // Dapatkan user yang sedang login

        // 1. Ambil semua data kelas untuk dropdown form
        $classes = SchoolClass::orderBy('name', 'asc')->get();

        // 2. Ambil input dari form pencarian
        $search = $request->get('search');
        $filter_class_id = $request->get('filter_class_id');

        // 3. Siapkan query dasar untuk mengambil siswa
        $query = Student::with('schoolClass')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->select('students.*'); // Penting untuk menghindari konflik ID

        // 4. LOGIKA FILTER WALI KELAS
        // Jika user yang login adalah 'Wali Kelas'
        if ($user->role == 'Wali Kelas') {
            // Cari kelas yang dipegang oleh user ini
            $homeroomClass = $user->homeroomClass; // Gunakan relasi yang kita buat di User.php
            
            if ($homeroomClass) {
                // Filter query agar hanya menampilkan siswa dari kelasnya
                $query->where('students.class_id', $homeroomClass->id);
            } else {
                // Jika dia Wali Kelas tapi tidak punya kelas, jangan tampilkan apa-apa
                $query->where('students.class_id', -1); // Trik agar query kosong
            }
        }

        // 5. LOGIKA FILTER PENCARIAN & KELAS (YANG HILANG)
        // Jika ada input 'search'
        $query->when($search, function ($q) use ($search) {
            // Cari berdasarkan nama siswa ATAU student_id
            return $q->where('students.name', 'like', '%' . $search . '%')
                     ->orWhere('students.student_id', 'like', '%' . $search . '%');
        });

        // Jika ada input 'filter_class_id'
        $query->when($filter_class_id, function ($q) use ($filter_class_id) {
            return $q->where('students.class_id', $filter_class_id);
        });
        
        // 6. LOGIKA SORTING (URUTAN)
        // Urutkan berdasarkan nama kelas, LALU berdasarkan nama siswa
        $students = $query->orderBy('classes.name', 'asc') 
                         ->orderBy('students.name', 'asc')
                         ->paginate(10) // Kita ubah kembali ke 10 agar pas dengan 'appends'
                         ->appends(request()->query()); // Tambahkan ini agar pagination ingat filternya

        // 7. Tampilkan view 'students.index' dan kirimkan data $classes dan $students
        return view('students.index', [
            'classes' => $classes,
            'students' => $students
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Kita tidak pakai fungsi ini karena form 'create' sudah ada di halaman 'index'
        return redirect()->route('students.index');
    }

    /**
     * Menyimpan data siswa baru dari form.
     * Ini adalah fungsi 'store'
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk
        $validatedData = $request->validate([
            'student_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('students', 'student_id') // Pastikan ID Siswa unik
            ],
            'name' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id', // Pastikan class_id ada di tabel 'classes'
            'rfid_id' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('students', 'rfid_id')->whereNotNull('rfid_id') // Pastikan RFID unik jika diisi
            ],
            'parent_wa_number' => 'nullable|string|max:20',
        ]);

        // 2. Jika validasi berhasil, buat data siswa baru
        Student::create($validatedData);

        // 3. Kembali ke halaman index dengan pesan sukses
        return redirect()->route('students.index')->with('success', 'Siswa baru berhasil ditambahkan.');
    }

    /**
     * 2. TAMBAHKAN FUNGSI BARU INI UNTUK MEMPROSES FILE IMPORT
     */
    public function import(Request $request)
    {
        // 1. Validasi file yang di-upload
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ], [
            'file.required' => 'Silakan pilih file untuk diimpor.',
            'file.mimes' => 'File harus berupa CSV, XLS, atau XLSX.',
        ]);

        try {
            // 2. Lakukan impor
            Excel::import(new StudentsImport, $request->file('file'));
            
            // 3. Jika sukses, kembali dengan pesan sukses
            return redirect()->route('students.index')->with('success', 'Data siswa berhasil diimpor.');

        } catch (ValidationException $e) {
            // 4. Jika ada error validasi di dalam file CSV
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return redirect()->route('students.index')->with('error', 'Gagal mengimpor data: ' . implode(' | ', $errorMessages));
        
        } catch (\Exception $e) {
            // 5. Jika ada error lain
            return redirect()->route('students.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * 3. TAMBAHKAN FUNGSI BARU INI UNTUK MEMPROSES EXPORT
     */
    public function export()
    {
        return Excel::download(new StudentsExport, 'data_siswa.xlsx');
    }


    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        // (Bisa dikembangkan nanti untuk melihat detail siswa)
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        // $student adalah data siswa yang akan diedit (via Route-Model Binding)
        
        // Kita juga butuh daftar semua kelas untuk dropdown
        $classes = SchoolClass::orderBy('name', 'asc')->get();

        return view('students.edit', [
            'student' => $student,
            'classes' => $classes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, Student $student)
    {
        // 1. Validasi data (mirip 'store', tapi 'unique' harus mengabaikan ID siswa ini sendiri)
        $validatedData = $request->validate([
            'student_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('students', 'student_id')->ignore($student->id) // Abaikan ID saat ini
            ],
            'name' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'rfid_id' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('students', 'rfid_id')->ignore($student->id)->whereNotNull('rfid_id') // Abaikan ID saat ini
            ],
            'parent_wa_number' => 'nullable|string|max:20',
        ]);

        // 2. Update data di database
        $student->update($validatedData);

        // 3. Kembali ke halaman index dengan pesan sukses
        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }
    /**
     * Menghapus data siswa.
     * Ini adalah fungsi 'destroy'
     */
    public function destroy(Student $student)
    {
        // Gunakan soft delete (karena kita pakai SoftDeletes di Model)
        $student->delete();
        
        // Kembali ke halaman index dengan pesan sukses
        return redirect()->route('students.index')->with('success', 'Siswa berhasil dihapus.');
    }
}
