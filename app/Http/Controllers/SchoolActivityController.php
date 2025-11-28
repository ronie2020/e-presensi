<?php

namespace App\Http\Controllers;

use App\Models\SchoolActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolActivityController extends Controller
{
    public function index()
    {
        // Ambil data terbaru
        $activities = SchoolActivity::latest()->paginate(6);
        return view('school-activities.index', compact('activities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'video_url' => 'nullable|url',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $request->video_url,
        ];

        // Handle Upload Foto
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('activities', 'public');
            $data['image_path'] = $path;
        }

        SchoolActivity::create($data);

        return redirect()->back()->with('success', 'Kegiatan berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $activity = SchoolActivity::findOrFail($id);

        // Hapus file foto jika ada
        if ($activity->image_path && Storage::disk('public')->exists($activity->image_path)) {
            Storage::disk('public')->delete($activity->image_path);
        }

        $activity->delete();

        return redirect()->back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}