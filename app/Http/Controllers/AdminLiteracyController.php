<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LiteracyJournal;
use App\Models\SchoolClass;
use App\Models\Student; // Jangan lupa import ini
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Untuk query aggregate

class AdminLiteracyController extends Controller
{
    public function index(Request $request)
    {
        // Filter Data
        $classId = $request->class_id;
        $date = $request->date; // Default null jika tidak dipilih

        // 1. BASE QUERY JURNAL
        $journalQuery = LiteracyJournal::with(['student.schoolClass'])
            ->when($classId, function($q) use ($classId) {
                $q->whereHas('student.schoolClass', function($sq) use ($classId) {
                    $sq->where('id', $classId);
                });
            })
            ->when($date, function($q) use ($date) {
                $q->whereDate('created_at', $date);
            });

        // 2. DATA UTAMA (TABEL)
        $journals = (clone $journalQuery)->latest()->paginate(10);

        // 3. STATISTIK PARTISIPASI
        // A. Hitung Total Siswa (Sesuai Filter Kelas)
        $totalStudents = Student::query()
            ->when($classId, function($q) use ($classId) {
                $q->whereHas('schoolClass', fn($sq) => $sq->where('id', $classId));
            })
            ->whereNull('deleted_at') // Asumsi pakai soft delete atau filter status aktif
            ->count();

        // B. Hitung Siswa yang Sudah Mengisi (Unik ID)
        $submittedStudentCount = (clone $journalQuery)
            ->distinct('student_id')
            ->count('student_id');

        // C. Siswa Belum Mengisi
        $notSubmittedCount = max(0, $totalStudents - $submittedStudentCount);
        
        // D. Persentase
        $participationRate = $totalStudents > 0 ? round(($submittedStudentCount / $totalStudents) * 100) : 0;

        // 4. TOP 5 BUKU TERPOPULER (Sesuai Filter)
        $topBooks = LiteracyJournal::select('title', 'author', DB::raw('count(*) as total_read'))
            ->when($classId, function($q) use ($classId) {
                $q->whereHas('student.schoolClass', function($sq) use ($classId) {
                    $sq->where('id', $classId);
                });
            })
            // Jika filter tanggal aktif, top book juga menyesuaikan tanggal
            ->when($date, function($q) use ($date) {
                $q->whereDate('created_at', $date);
            })
            ->groupBy('title', 'author')
            ->orderByDesc('total_read')
            ->take(5)
            ->get();

        $classes = SchoolClass::orderBy('name')->get();

        return view('literacy.index', compact(
            'journals', 
            'classes', 
            'submittedStudentCount', 
            'notSubmittedCount', 
            'participationRate',
            'topBooks'
        ));
    }

    public function verify($id)
    {
        $journal = LiteracyJournal::findOrFail($id);
        $journal->update(['verified_at' => Carbon::now()]);
        return back()->with('success', 'Jurnal berhasil diverifikasi.');
    }

    public function destroy($id)
    {
        $journal = LiteracyJournal::findOrFail($id);
        if ($journal->proof_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($journal->proof_image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($journal->proof_image);
        }
        $journal->delete();
        return back()->with('success', 'Data dihapus.');
    }
}