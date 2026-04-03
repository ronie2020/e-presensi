<?php

namespace App\Http\Controllers;

use App\Models\SchoolActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        // 1. TANGKAP VALIDASI MANUAL: Agar error bisa ditampilkan ke SweetAlert
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240', // Limit diperbesar jadi 10MB per foto
            'video_url' => 'nullable|url',
        ]);

        // Jika validasi gagal (misal: file terlalu besar/bukan gambar)
        if ($validator->fails()) {
            $errorMsg = $validator->errors()->first();
            return redirect()->back()->with('success', 'Gagal: ' . $errorMsg)->withInput();
        }

        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'video_url' => $request->video_url,
            ];

            $imagePaths = [];

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $file) {
                    $path = $file->store('activities', 'public');
                    $imagePaths[] = $path;
                }
            }

            if (!empty($imagePaths)) {
                // 2. MENCEGAH CRASH DATABASE: Kita ubah paksa Array menjadi JSON String
                // Ini menyelamatkan sistem jika Model SchoolActivity tidak di-setting $casts
                $data['image_path'] = json_encode($imagePaths);
            }

            SchoolActivity::create($data);

            return redirect()->back()->with('success', 'Kegiatan berhasil ditambahkan!');

        } catch (\Exception $e) {
            // Tangkap jika ada error sistem/database agar tidak blank putih
            return redirect()->back()->with('success', 'Error Sistem: ' . $e->getMessage())->withInput();
        }
    }

    // TAMBAHAN: FUNGSI UPDATE DATA
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240', 
            'video_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('success', 'Gagal Edit: ' . $validator->errors()->first())->withInput();
        }

        try {
            $activity = SchoolActivity::findOrFail($id);

            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'video_url' => $request->video_url,
            ];

            // Jika ada upload foto baru dari form Edit
            if ($request->hasFile('photos')) {
                
                // 1. Hapus semua foto lama terlebih dahulu
                if (!empty($activity->image_path)) {
                    $oldImages = [];
                    if (is_array($activity->image_path)) {
                        $oldImages = $activity->image_path;
                    } elseif (is_string($activity->image_path)) {
                        $decoded = json_decode($activity->image_path, true);
                        $oldImages = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [$activity->image_path];
                    }

                    foreach ($oldImages as $path) {
                        if (Storage::disk('public')->exists($path)) {
                            Storage::disk('public')->delete($path);
                        }
                    }
                }

                // 2. Simpan sekumpulan foto baru
                $imagePaths = [];
                foreach ($request->file('photos') as $file) {
                    $path = $file->store('activities', 'public');
                    $imagePaths[] = $path;
                }

                // 3. Konversi array ke format JSON 
                $data['image_path'] = json_encode($imagePaths);
            }

            $activity->update($data);

            return redirect()->back()->with('success', 'Kegiatan berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'Error Sistem: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $activity = SchoolActivity::findOrFail($id);

        if (!empty($activity->image_path)) {
            // 3. HAPUS FOTO AMAN: Ekstrak data JSON dengan aman
            $images = [];
            if (is_array($activity->image_path)) {
                $images = $activity->image_path;
            } elseif (is_string($activity->image_path)) {
                $decoded = json_decode($activity->image_path, true);
                $images = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [$activity->image_path];
            }

            // Hapus setiap foto fisik di folder storage
            foreach ($images as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $activity->delete();

        return redirect()->back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}