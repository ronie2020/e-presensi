<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use App\Models\AcademicCalendar;
use Carbon\Carbon;

class AcademicCalendarController extends Controller
{
    public function index()
    {
        // Ambil semua data kalender, urutkan dari yang terbaru
        $events = AcademicCalendar::orderBy('start_date', 'desc')->get();
                
        return view('admin.academic-calendar.index', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'required|in:kegiatan,libur,ujian,nasional',
            'is_all_day' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_all_day'] = $request->has('is_all_day');

        AcademicCalendar::create($data);

        return back()->with('success', 'Agenda kalender berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'required|in:kegiatan,libur,ujian,nasional',
            'is_all_day' => 'boolean'
        ]);

        $event = AcademicCalendar::findOrFail($id);
        
        $data = $request->all();
        $data['is_all_day'] = $request->has('is_all_day');

        $event->update($data);

        return back()->with('success', 'Agenda kalender berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $event = AcademicCalendar::findOrFail($id);
        $event->delete();

        return back()->with('success', 'Agenda kalender berhasil dihapus!');
    }
}