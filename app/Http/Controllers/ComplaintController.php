<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    // --- ADMIN / GURU METHODS ---

    /**
     * Menampilkan semua pengaduan masuk (Admin/Guru).
     * Mendukung filter pencarian, tanggal, dan kategori.
     */
    public function index(Request $request)
    {
        // 1. Ambil input filter
        $search   = $request->query('search');
        $date     = $request->query('date');
        $category = $request->query('category');

        // 2. Query dasar dengan Eager Loading student dan class (jika ada)
        $query = Complaint::with(['student.schoolClass'])->latest();

        // 3. Terapkan Filter Pencarian (Nama Siswa atau Deskripsi)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('student', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 4. Filter Tanggal
        if ($date) {
            $query->whereDate('incident_date', $date);
        }

        // 5. Filter Kategori
        if ($category) {
            $query->where('category', $category);
        }

        // 6. Eksekusi Pagination
        $complaints = $query->paginate(10)->withQueryString();

        // 7. Kirim data ke view (Termasuk variabel $date agar tidak error)
        return view('admin.complaints.index', compact('complaints', 'date', 'search', 'category'));
    }

    /**
     * Menyelesaikan/Menutup pengaduan (Admin).
     */
    public function markAsResolved($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->update(['status' => 'resolved']);
        
        return back()->with('success', 'Laporan berhasil ditandai sebagai selesai.');
    }

    // --- SISWA METHODS ---

    public function create()
    {
        return view('complaints.create');
    }

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

    public function history()
    {
        $complaints = Complaint::where('student_id', Auth::guard('student')->id())
                        ->latest()
                        ->get();

        return view('complaints.index', compact('complaints'));
    }
}