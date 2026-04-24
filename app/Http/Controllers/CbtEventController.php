<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CbtEvent;
use App\Models\CbtExam;

class CbtEventController extends Controller
{
    /**
     * Menampilkan Dashboard Utama CBT (Daftar Folder/Kegiatan)
     */
    public function index(Request $request)
    {
        $stats = [
            'active_exams' => CbtExam::where('is_active', true)->count(),
            'total_questions' => DB::table('cbt_questions')->count(),
            'students_working' => DB::table('cbt_student_exams')->where('status', 'ongoing')->count(),
            'avg_score' => DB::table('cbt_student_exams')->whereNotNull('total_score')->avg('total_score') ?? 0,
        ];
        
        $query = CbtEvent::withCount('exams'); 

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $events = $query->latest()->paginate(12)->withQueryString();

        return view('cbt.index', compact('stats', 'events'));
    }

    /**
     * Menyimpan Folder / Kegiatan Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        CbtEvent::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true
        ]);

        return back()->with('success', 'Kegiatan / Folder Ujian berhasil dibuat!');
    }

    /**
     * Memperbarui Nama/Deskripsi Folder
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $event = CbtEvent::findOrFail($id);
        $event->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Kegiatan / Folder berhasil diperbarui!');
    }

    /**
     * Menampilkan detail isi folder (Daftar Jadwal Ujian)
     */
    public function show(Request $request, $id)
    {
        $event = CbtEvent::findOrFail($id);
        
        $stats = [
            'active_exams' => CbtExam::where('cbt_event_id', $id)->where('is_active', true)->count(),
            'total_questions' => DB::table('cbt_questions')->whereIn('cbt_exam_id', CbtExam::where('cbt_event_id', $id)->pluck('id'))->count(),
            'students_working' => DB::table('cbt_student_exams')
                                    ->whereIn('cbt_exam_id', CbtExam::where('cbt_event_id', $id)->pluck('id'))
                                    ->where('status', 'ongoing')->count(),
            'avg_score' => DB::table('cbt_student_exams')
                            ->whereIn('cbt_exam_id', CbtExam::where('cbt_event_id', $id)->pluck('id'))
                            ->whereNotNull('total_score')->avg('total_score') ?? 0,
        ];

        $query = CbtExam::where('cbt_event_id', $id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subject_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter') && $request->filter != 'all') {
            $query->where('is_active', $request->filter == 'active');
        }
       
        $exams = $query->latest()->paginate(12)->withQueryString();

        return view('cbt.show_event', compact('stats', 'exams', 'event'));
    }
}