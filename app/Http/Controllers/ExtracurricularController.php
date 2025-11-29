<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Extracurricular;
use App\Models\ExtracurricularMember;
use App\Models\ExtracurricularAttendance;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ExtracurricularController extends Controller
{
    // --- 1. MANAJEMEN DATA & JADWAL ---
    public function index()
    {
        $extracurriculars = Extracurricular::withCount('members')->get();
        return view('extracurriculars.index', compact('extracurriculars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'coach_name' => 'nullable|string|max:255',
            'schedule' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|max:2048', 
            'icon_text' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['name', 'coach_name', 'schedule']);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('extracurriculars', 'public');
            $data['icon'] = 'storage/' . $path;
        } elseif ($request->filled('icon_text')) {
            $data['icon'] = $request->icon_text;
        } else {
            $data['icon'] = 'ph-fill ph-star'; 
        }

        Extracurricular::create($data);

        return redirect()->back()->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // Validasi Update
        $request->validate([
            'name' => 'required|string|max:255',
            'image_file' => 'nullable|image|max:2048',
        ]);

        $ekskul = Extracurricular::findOrFail($id);
        $data = $request->only(['name', 'coach_name', 'schedule']);

        // LOGIKA UPDATE GAMBAR
        if ($request->hasFile('image_file')) {
            // Hapus gambar lama jika ada (dan bukan teks/url luar)
            if ($ekskul->icon && str_starts_with($ekskul->icon, 'storage/')) {
                $oldPath = str_replace('storage/', '', $ekskul->icon);
                Storage::disk('public')->delete($oldPath);
            }
            
            // Simpan gambar baru
            $path = $request->file('image_file')->store('extracurriculars', 'public');
            $data['icon'] = 'storage/' . $path;
            
        } elseif ($request->filled('icon_text')) {
            // Jika user menginput kode ikon baru
            $data['icon'] = $request->icon_text;
        }
        // Jika tidak ada input file/text baru, biarkan icon lama ($data['icon'] tidak diset)

        $ekskul->update($data);
        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ekskul = Extracurricular::findOrFail($id);
        
        if ($ekskul->icon && str_starts_with($ekskul->icon, 'storage/')) {
            $filePath = str_replace('storage/', '', $ekskul->icon);
            Storage::disk('public')->delete($filePath);
        }

        $ekskul->delete();
        return redirect()->back()->with('success', 'Ekstrakurikuler dihapus.');
    }

    // ... (Method members, storeMember, destroyMember, reports TETAP SAMA, tidak perlu diubah) ...
    public function members(Request $request)
    {
        $selectedEkskulId = $request->get('ekskul_id');
        $extracurriculars = Extracurricular::all();
        
        $members = collect();
        $students = collect();

        if ($selectedEkskulId) {
            $members = ExtracurricularMember::with(['student.schoolClass']) 
                ->where('extracurricular_id', $selectedEkskulId)
                ->get()
                ->sortBy(function($member) {
                    $className = $member->student->schoolClass->name ?? $member->student->class_name ?? 'Z';
                    return $className . $member->student->name;
                });
            
            $existingMemberIds = $members->pluck('student_id')->toArray();
            
            $students = Student::with('schoolClass')
                ->whereNotIn('student_id', $existingMemberIds)
                ->get()
                ->sortBy(function($student) {
                    $className = $student->schoolClass->name ?? $student->class_name ?? 'Z';
                    return $className . $student->name;
                });
        }

        return view('extracurriculars.members', compact('extracurriculars', 'selectedEkskulId', 'members', 'students'));
    }

    public function storeMember(Request $request)
    {
        $request->validate([
            'extracurricular_id' => 'required|exists:extracurriculars,id',
            'student_id' => 'required|exists:students,student_id',
        ]);

        ExtracurricularMember::create($request->only('extracurricular_id', 'student_id'));
        return redirect()->back()->with('success', 'Siswa berhasil ditambahkan ke ekskul.');
    }

    public function destroyMember($id)
    {
        ExtracurricularMember::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Siswa dikeluarkan dari ekskul.');
    }

    public function reports(Request $request)
    {
        $selectedEkskulId = $request->get('ekskul_id');
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $extracurriculars = Extracurricular::all();
        $attendances = collect();

        if ($selectedEkskulId) {
            $attendances = ExtracurricularAttendance::with(['student.schoolClass'])
                ->where('extracurricular_id', $selectedEkskulId)
                ->whereBetween('date', [$startDate, $endDate])
                ->get()
                ->sortByDesc('date')
                ->sortBy(function($log) {
                    $className = $log->student->schoolClass->name ?? $log->student->class_name ?? 'Z';
                    return $className . $log->student->name;
                });
        }

        return view('extracurriculars.reports', compact('extracurriculars', 'selectedEkskulId', 'attendances', 'startDate', 'endDate'));
    }
}