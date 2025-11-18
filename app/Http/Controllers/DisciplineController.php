<?php

namespace App\Http\Controllers;

use App\Models\DisciplineRecord;
use App\Models\DisciplineType;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mengambil ID user yang login
use Carbon\Carbon; // Untuk tanggal

class DisciplineController extends Controller
{
    /**
     * Menampilkan halaman utama Catatan Disiplin.
     */
    public function index()
    {
        // 1. Ambil data untuk dropdown
        $students = Student::orderBy('name', 'asc')->get();
        
        // 2. Pisahkan data Tipe Disiplin
        $violationTypes = DisciplineType::where('type', 'Pelanggaran')->orderBy('name', 'asc')->get();
        $meritTypes = DisciplineType::where('type', 'Kebaikan')->orderBy('name', 'asc')->get();

        // 3. Ambil data log/catatan terakhir
        $records = DisciplineRecord::with(['student.schoolClass', 'disciplineType', 'recorder'])
            ->latest() // Urutkan dari yang terbaru
            ->paginate(10); // Tampilkan 10 per halaman

        // 4. Kirim semua data ke view
        return view('discipline.index', [
            'students' => $students,
            'violationTypes' => $violationTypes,
            'meritTypes' => $meritTypes,
            'records' => $records,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * Kita tidak pakai ini karena form ada di 'index'
     */
    public function create()
    {
        return redirect()->route('discipline.index');
    }

    /**
     * Menyimpan catatan baru (baik Pelanggaran maupun Kebaikan).
     */
    public function store(Request $request)
    {
        // 1. Validasi data
        $validatedData = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'discipline_type_id' => 'required|integer|exists:discipline_types,id',
            'notes' => 'nullable|string',
            'date' => 'required|date',
        ]);

        // 2. Tambahkan siapa yang mencatat (user yang sedang login)
        $validatedData['recorded_by_user_id'] = Auth::id();

        // 3. Simpan ke database
        DisciplineRecord::create($validatedData);

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->route('discipline.index')->with('success', 'Catatan disiplin berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DisciplineRecord $disciplineRecord)
    {
        // Tidak kita gunakan
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DisciplineRecord $disciplineRecord)
    {
        // Tidak kita gunakan
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DisciplineRecord $disciplineRecord)
    {
        // Tidak kita gunakan
    }

    /**
     * Menghapus catatan disiplin.
     */
    public function destroy(DisciplineRecord $discipline)
    {
        // $discipline didapat dari Route-Model Binding
        $discipline->delete();
        
        return redirect()->route('discipline.index')->with('success', 'Catatan disiplin berhasil dihapus.');
    }
}