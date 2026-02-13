<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LiteracyJournal;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminLiteracyController extends Controller
{
    public function index(Request $request)
    {
        // Filter Data
        $classId = $request->class_id;
        $date = $request->date;

        $journals = LiteracyJournal::with(['student.schoolClass'])
            ->when($classId, function($q) use ($classId) {
                // PERBAIKAN: Gunakan nested relationship 'student.schoolClass'
                // Ini akan otomatis menggunakan foreign key yang benar (class_id atau school_class_id)
                // tanpa kita harus menebak nama kolomnya secara manual.
                $q->whereHas('student.schoolClass', function($sq) use ($classId) {
                    $sq->where('id', $classId);
                });
            })
            ->when($date, function($q) use ($date) {
                $q->whereDate('created_at', $date);
            })
            ->latest()
            ->paginate(10); 

        $classes = SchoolClass::orderBy('name')->get();

        return view('literacy.index', compact('journals', 'classes'));
    }

    public function verify($id)
    {
        $journal = LiteracyJournal::findOrFail($id);
        
        // Tandai diverifikasi hari ini
        $journal->update([
            'verified_at' => Carbon::now()
        ]);

        return back()->with('success', 'Jurnal berhasil diverifikasi.');
    }

    public function destroy($id)
    {
        $journal = LiteracyJournal::findOrFail($id);
        
        // Hapus file gambar jika ada
        if ($journal->proof_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($journal->proof_image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($journal->proof_image);
        }

        $journal->delete();

        return back()->with('success', 'Jurnal berhasil dihapus.');
    }
}