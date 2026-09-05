<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\TeachingLoad;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TeachingLoadImport;

class TeachingLoadController extends Controller
{
    public function index()
    {
        // Ambil data Guru (Spatie Permission)
        $teachers = User::role(['Guru', 'Guru Mata Pelajaran', 'Wali Kelas'])->orderBy('name')->get();
        
        // Ambil data Kelas & Mapel
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        
        // Ambil data beban mengajar yang sudah tersimpan
        $teachingLoads = TeachingLoad::with(['teacher', 'subject', 'studentClass'])
            ->orderBy('class_id')
            ->get();

        return view('teaching-loads.index', compact('teachers', 'classes', 'subjects', 'teachingLoads'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'hours_per_week' => 'required|integer|min:1|max:40',
        ]);

        // Cek duplikasi agar tidak ada guru yang mengajar mapel yang sama di kelas yang sama 2x
        $exists = TeachingLoad::where('teacher_id', $request->teacher_id)
            ->where('subject_id', $request->subject_id)
            ->where('class_id', $request->class_id)
            ->first();

        if ($exists) {
            return redirect()->back()->with('error', 'Beban mengajar untuk Guru, Mapel, dan Kelas ini sudah ada!');
        }

        TeachingLoad::create($request->all());

        return redirect()->back()->with('success', 'Beban mengajar berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $load = TeachingLoad::findOrFail($id);
        $load->delete();

        return redirect()->back()->with('success', 'Beban mengajar berhasil dihapus.');
    }

    /**
     * Fitur Generate Template CSV Beban Mengajar
     */
    public function template()
    {
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Template_Beban_Mengajar.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        $columns = ['nama_guru', 'nama_mapel', 'nama_kelas', 'jp'];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            // Contoh format pengisian (baris 1)
            fputcsv($file, ['Budi Santoso', 'Matematika', 'X-A', '4']);
            // Contoh format pengisian (baris 2)
            fputcsv($file, ['Siti Aminah', 'Bahasa Indonesia', 'X-B', '2']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Memproses File Upload dari form Kurikulum
     */
    public function import(Request $request)
    {
        // PERBAIKAN: Tambahkan 'txt' agar Laravel tidak memblokir file CSV yang terdeteksi sebagai plain-text
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120'
        ], [
            'file.mimes' => 'Format file ditolak! Pastikan Anda mengupload file dengan format Excel (.xlsx, .xls) atau CSV (.csv).',
            'file.max' => 'Ukuran file maksimal adalah 5MB.'
        ]);

        try {
            Excel::import(new TeachingLoadImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data beban mengajar dari Excel berhasil diproses!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal meng-import data. Pastikan format kolom sesuai dengan template. Detail Error: ' . $e->getMessage());
        }
    }

    /**
     * Memproses Update Data Beban Mengajar (Edit)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'hours_per_week' => 'required|integer|min:1|max:40',
        ]);

        $load = TeachingLoad::findOrFail($id);

        // Cek duplikasi agar tidak bentrok (tapi abaikan ID miliknya sendiri)
        $exists = TeachingLoad::where('teacher_id', $request->teacher_id)
            ->where('subject_id', $request->subject_id)
            ->where('class_id', $request->class_id)
            ->where('id', '!=', $id)
            ->first();

        if ($exists) {
            return redirect()->back()->with('error', 'Gagal update! Beban mengajar untuk Guru, Mapel, dan Kelas ini sudah ada.');
        }

        $load->update($request->all());

        return redirect()->back()->with('success', 'Data beban mengajar berhasil diperbarui.');
    }

    /**
     * Memproses Hapus Massal Data Beban Mengajar (Hanya Admin)
     */
    public function massDestroy(Request $request)
    {
        // Validasi array ids
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:teaching_loads,id'
        ]);

        // Eksekusi hapus massal
        TeachingLoad::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', count($request->ids) . ' beban mengajar berhasil dihapus secara massal.');
    }
    
}