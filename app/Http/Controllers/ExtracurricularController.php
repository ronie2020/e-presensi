<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Extracurricular;
use App\Models\ExtracurricularMember;
use App\Models\ExtracurricularAttendance;
use App\Models\Student;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ExtracurricularController extends Controller
{
    // ... Method index, store, update, destroy TETAP SAMA ... 
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
        $request->validate([
            'name' => 'required|string|max:255',
            'image_file' => 'nullable|image|max:2048',
        ]);

        $ekskul = Extracurricular::findOrFail($id);
        $data = $request->only(['name', 'coach_name', 'schedule']);

        if ($request->hasFile('image_file')) {
            if ($ekskul->icon && str_starts_with($ekskul->icon, 'storage/')) {
                $oldPath = str_replace('storage/', '', $ekskul->icon);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image_file')->store('extracurriculars', 'public');
            $data['icon'] = 'storage/' . $path;
        } elseif ($request->filled('icon_text')) {
            $data['icon'] = $request->icon_text;
        }

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

    // --- MANAJEMEN ANGGOTA ---

    public function members(Request $request)
    {
        $selectedEkskulId = $request->get('ekskul_id');
        $extracurriculars = Extracurricular::all();
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        
        $members = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        $students = collect();
        $studentsForJs = collect();

        if ($selectedEkskulId) {
            $members = ExtracurricularMember::select('extracurricular_members.*')
                ->join('students', 'extracurricular_members.student_id', '=', 'students.id')
                ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
                ->where('extracurricular_members.extracurricular_id', $selectedEkskulId)
                ->whereHas('student') 
                ->orderBy('classes.name', 'asc')
                ->orderBy('students.name', 'asc')
                ->with(['student.schoolClass'])
                ->paginate(20)
                ->withQueryString();
            
            $existingMemberIds = ExtracurricularMember::where('extracurricular_id', $selectedEkskulId)
                                    ->pluck('student_id')
                                    ->toArray();
            
            $students = Student::with('schoolClass')
                ->whereNotIn('id', $existingMemberIds)
                ->get()
                ->sortBy(function($student) {
                    $className = optional($student->schoolClass)->name ?? 'Z';
                    return $className . $student->name;
                });

            $studentsForJs = $students->map(function($s) {
                return [
                    'id' => $s->id,
                    'name' => $s->name,
                    'nis' => $s->nis,
                    'class_id' => $s->class_id, 
                    'class_name' => optional($s->schoolClass)->name ?? '-'
                ];
            })->values();
        }

        return view('extracurriculars.members', compact('extracurriculars', 'selectedEkskulId', 'members', 'students', 'classes', 'studentsForJs'));
    }

    // [DIPERBAIKI] Hapus joined_at agar tidak error
    public function storeMember(Request $request)
    {
        $request->validate([
            'extracurricular_id' => 'required|exists:extracurriculars,id',
            'student_ids'        => 'required|array',
            'student_ids.*'      => 'exists:students,id', 
        ]);

        $count = 0;
        
        foreach ($request->student_ids as $studentId) {
            $exists = ExtracurricularMember::where('extracurricular_id', $request->extracurricular_id)
                        ->where('student_id', $studentId)
                        ->exists();

            if (!$exists) {
                ExtracurricularMember::create([
                    'extracurricular_id' => $request->extracurricular_id,
                    'student_id'         => $studentId,
                    // 'joined_at' => now() <--- BARIS INI SUDAH DIHAPUS
                ]);
                $count++;
            }
        }

        if ($count > 0) {
            return redirect()->back()->with('success', "Berhasil menambahkan $count siswa ke ekskul.");
        } else {
            return redirect()->back()->with('info', 'Semua siswa yang dipilih sudah terdaftar sebelumnya.');
        }
    }

    public function destroyMember($id)
    {
        ExtracurricularMember::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Siswa dikeluarkan dari ekskul.');
    }

    // --- LAPORAN ---

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
                ->whereHas('student')
                ->get()
                ->sortByDesc('date')
                ->sortBy(function($log) {
                    $className = optional(optional($log->student)->schoolClass)->name ?? 'Z';
                    return $className . optional($log->student)->name;
                });
        }

        return view('extracurriculars.reports', compact('extracurriculars', 'selectedEkskulId', 'attendances', 'startDate', 'endDate'));
    }

    public function exportReports(Request $request)
    {
        $selectedEkskulId = $request->get('ekskul_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $ekskul = null;
        $attendances = collect();

        if ($selectedEkskulId) {
            $ekskul = Extracurricular::find($selectedEkskulId);
            
            $attendances = ExtracurricularAttendance::with(['student.schoolClass'])
                ->where('extracurricular_id', $selectedEkskulId)
                ->whereBetween('date', [$startDate, $endDate])
                ->whereHas('student')
                ->get()
                ->sortByDesc('date')
                ->sortBy(function($log) {
                    $className = optional(optional($log->student)->schoolClass)->name ?? 'Z';
                    return $className . optional($log->student)->name;
                });
        }

        return view('extracurriculars.print_reports', compact('ekskul', 'attendances', 'startDate', 'endDate'));
    }
}