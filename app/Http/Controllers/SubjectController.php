<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('order', 'asc')->get();
        // PERBAIKAN: Sesuaikan dengan folder 'settings'
        return view('settings.subjects', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'order' => 'required|integer',
            'group' => 'required|in:A,B,C,P5',
        ]);

        Subject::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'order' => $request->order,
            'group' => $request->group,
        ]);

        return redirect()->back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'order' => 'required|integer',
            'group' => 'required|in:A,B,C,P5',
        ]);

        $subject->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'order' => $request->order,
            'group' => $request->group,
        ]);

        return redirect()->back()->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->back()->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}