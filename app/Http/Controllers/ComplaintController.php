<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    // --- ADMIN / GURU METHODS ---

    /**
     * Menampilkan semua pengaduan masuk (Admin).
     */
    public function index()
    {
        $complaints = Complaint::latest()->paginate(10);
        // PERUBAHAN DISINI: Path view disesuaikan dengan folder baru
        return view('admin.complaints.index', compact('complaints'));
    }

    /**
     * Menyelesaikan/Menutup pengaduan (Admin).
     */
    public function markAsResolved($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->update(['status' => 'resolved']);
        
        return back()->with('success', 'Pengaduan ditandai selesai.');
    }

    // --- SISWA METHODS ---

    /**
     * Form Buat Pengaduan (Siswa).
     */
    public function create()
    {
        return view('complaints.create');
    }

    /**
     * Simpan Pengaduan (Siswa).
     */
    public function store(Request $request)
    {
        $request->validate([
            'category'      => 'required',
            'description'   => 'required',
            'incident_date' => 'required|date',
            'location'      => 'required',
            'evidence'      => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['evidence', 'is_anonymous']);
        $data['student_id'] = Auth::guard('student')->id();
        $data['is_anonymous'] = $request->has('is_anonymous');
        $data['status'] = 'pending';

        if ($request->hasFile('evidence')) {
            $data['evidence_path'] = $request->file('evidence')->store('complaints', 'public');
        }

        Complaint::create($data);

        return redirect()->route('student.complaints.index')
            ->with('success', 'Laporan berhasil dikirim.');
    }

    /**
     * Riwayat Pengaduan (Siswa).
     */
    public function history()
    {
        $complaints = Complaint::where('student_id', Auth::guard('student')->id())
                        ->latest()
                        ->get();

        return view('complaints.index', compact('complaints'));
    }
}